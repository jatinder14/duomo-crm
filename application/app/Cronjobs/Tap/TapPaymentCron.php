<?php

/** -------------------------------------------------------------------------------------------------
 * TEMPLATE
 * This cronjob is envoked by by the task scheduler which is in 'application/app/Console/Kernel.php'
 * @package    Grow CRM
 * @author     NextLoop
 *---------------------------------------------------------------------------------------------------*/

namespace App\Cronjobs\Tap;

use App\Repositories\EventRepository;
use App\Repositories\EventTrackingRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TapPaymentCron {

    public function __invoke(
        EventRepository $eventrepo,
        EventTrackingRepository $trackingrepo,
        InvoiceRepository $invoicerepo,
        UserRepository $userrepo
    ) {

        //[MT] - tenants only
        if (env('MT_TPYE')) {
            if (\Spatie\Multitenancy\Models\Tenant::current() == null) {
                return;
            }
            //boot system settings
            middlwareBootSystem();
            middlewareBootMail();
        }

        /**
         *   - Find webhhoks waiting to be completed
         *   - connect to TAP API and validate the payment
         *   - mark invoice as paid
         *   - add new payment
         *   - email the customer
         */
        //Get the emails marked as [pdf] and [invoice] - limit 5
        $limit = 2;
        if ($webhooks = \App\Models\Webhook::Where('webhooks_type', 'tap-gateway-payment')
            ->where('webhooks_status', 'new')
            ->where('webhooks_attempts', '<=', 3)
            ->take($limit)
            ->get()) {

                
            Log::info("found applicable webhooks", ['process' => '[tap-complete-payment-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            
            //mark all emails in the batch as processing - to avoid batch duplicates/collisions
            foreach ($webhooks as $webhook) {
                $webhook->update([
                    'webhooks_status' => 'processing',
                    'webhooks_started_at' => now(),
                ]);
                Log::info("foo bar", ['payload' => $webhook]);
            }


            //process each webhook
            foreach ($webhooks as $webhook) {


                //see if there is matching payment session
                if (!$session = \App\Models\PaymentSession::Where('session_gateway_ref', $webhook->webhooks_matching_attribute)->first()) {
                    $webhook->update([
                        'webhooks_status' => 'failed',
                        'webhooks_completed_at' => now(),
                    ]);
                    Log::error("matching payment session could not be found", ['process' => '[tap-complete-payment-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'webhooks_id' => $webhook->webhooks_id]);
                    continue;
                }

                //validate the payment with Tap
                try {
                    $response = Http::withToken(config('system.settings2_tap_secret_key'))
                        ->get('https://api.tap.company/v2/charges/' . $webhook->webhooks_payment_transactionid);

                    //success
                    if ($response->successful()) {
                        $result = $response->json();
                        if (!isset($result['id']) || (isset($result['id']) && $result['id'] != $webhook->webhooks_payment_transactionid)) {
                            $webhook->update([
                                'webhooks_status' => 'failed',
                                'webhooks_completed_at' => now(),
                            ]);
                            Log::error("validating payment failed - payment not found at Tap", ['process' => '[tap-complete-payment-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'webhooks_id' => $webhook->webhooks_id]);
                            continue;
                        }
                    } else {
                        //invalid
                        $webhook->update([
                            'webhooks_status' => 'failed',
                            'webhooks_completed_at' => now(),
                        ]);
                        Log::error("validating payment failed - payment not found at Tap", ['process' => '[tap-complete-payment-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'webhooks_id' => $webhook->webhooks_id]);
                        continue;
                    }
                } catch (exception $e) {
                    //failed to connect or authenticate
                    $webhook->update([
                        'webhooks_attempts' => $webhook->webhooks_attempts + 1,
                    ]);
                    Log::error("connecting to Tap API failed. error: " . $e->getMessage(), ['process' => '[tap-complete-payment-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'webhooks_id' => $webhook->webhooks_id]);
                    continue;
                }

                //make sure there is no payment already
                if (\App\Models\Payment::Where('payment_transaction_id', $webhook->webhooks_payment_transactionid)->exists()) {
                    $webhook->update([
                        'webhooks_status' => 'completed',
                        'webhooks_completed_at' => now(),
                    ]);
                    continue;
                }

                //check if there is a corresponding invoice for the payment session
                if (!$invoice = \App\Models\Invoice::Where('bill_invoiceid', $session->session_invoices)->first()) {
                    //log error
                    Log::critical("no corresponding (invoice) (Invoice ID: $session->session_invoices) record was found for this payment session", ['process' => '[mollie-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payment_session' => $session]);
                    $webhook->update([
                        'webhooks_status' => 'failed',
                    ]);
                    continue;
                }

                //create new payment
                $payment = new \App\Models\Payment();
                $payment->payment_creatorid = $session->session_creatorid;
                $payment->payment_date = now();
                $payment->payment_invoiceid = $invoice->bill_invoiceid;
                $payment->payment_clientid = $invoice->bill_clientid;
                $payment->payment_projectid = $invoice->bill_projectid;
                $payment->payment_amount = $session->session_amount;
                $payment->payment_transaction_id = $webhook->webhooks_payment_transactionid;
                $payment->payment_gateway = $webhook->webhooks_gateway_name;
                $payment->save();

                //get refreshed invoice
                $invoices = $invoicerepo->search($invoice->bill_invoiceid);
                $invoice = $invoices->first();

                //refresh the invoice
                $invoicerepo->refreshInvoice($invoice);

                /** ----------------------------------------------
                 * record event [comment]
                 * ----------------------------------------------*/
                $data = [
                    'event_creatorid' => $session->session_creatorid,
                    'event_item' => 'invoice',
                    'event_item_id' => $invoice->bill_invoiceid,
                    'event_item_lang' => 'event_paid_invoice',
                    'event_item_content' => __('lang.invoice') . ' - ' . $invoice->formatted_bill_invoiceid,
                    'event_item_content2' => '',
                    'event_parent_type' => 'invoice',
                    'event_parent_id' => $invoice->bill_invoiceid,
                    'event_parent_title' => $invoice->project_title,
                    'event_clientid' => $invoice->bill_clientid,
                    'event_show_item' => 'yes',
                    'event_show_in_timeline' => 'yes',
                    'eventresource_type' => 'project',
                    'eventresource_id' => $invoice->bill_projectid,
                    'event_notification_category' => 'notifications_billing_activity',

                ];
                //record event
                if ($event_id = $eventrepo->create($data)) {
                    //get invoice/payments team users, with billing app notifications enabled
                    $users = $userrepo->mailingListInvoices('app');
                    //record notification
                    $trackingrepo->recordEvent($data, $users, $event_id);
                }

                //additional data for emails
                $data = [
                    'paid_by_name' => $session->session_creator_fullname,
                    'payment_amount' => runtimeMoneyFormat($session->session_amount),
                    'payment_transaction_id' => $webhook->webhooks_payment_transactionid,
                    'payment_gateway' => $session->webhooks_gateway_name,
                ];

                /** --------------------------------------------------------------------------
                 * send email [team] [queued]
                 * - invoice & payments users, with biling email preference enabled
                 * --------------------------------------------------------------------------*/
                $users = $userrepo->mailingListInvoices('email');
                foreach ($users as $user) {
                    $mail = new \App\Mail\PaymentReceived($user, $data, $invoice);
                    $mail->build();
                }

                /** --------------------------------------------------------------------------
                 * send email [client] [queued]
                 * - thank you email to user who placed order
                 * --------------------------------------------------------------------------*/
                if ($user = \App\Models\User::Where('id', $session->session_creatorid)->first()) {
                    $mail = new \App\Mail\PaymentReceived($user, $data, $invoice);
                    $mail->build();
                }

                //mark webhook cronjob as done
                $webhook->update([
                    'webhooks_status' => 'completed',
                    'webhooks_completed_at' => now(),
                ]);

                //reset last cron run data
                \App\Models\Settings::where('settings_id', 1)
                    ->update([
                        'settings_cronjob_has_run' => 'yes',
                        'settings_cronjob_last_run' => now(),
                    ]);
            }

        }

    }

}