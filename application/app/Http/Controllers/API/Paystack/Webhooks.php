<?php

/** --------------------------------------------------------------------------------
 * This controller receives and processes webhook calls from Stripe
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\API\Paystack;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Webhooks extends Controller {

    public function __construct() {

        //parent
        parent::__construct();

        $this->middleware('guest');
    }

    /**
     * Receive and process paystack webhook
     * @return null
     */
    public function index() {

        // Verify webhook signature
        if (!$this->verifyWebhook()) {
            return response('Invalid Paystack signature', 400);
        }

        //get the payload data
        $payload = json_decode(request()->getContent());

        Log::info("paystack webhook", ['payload' => $payload]);

        // Route to the correct method based on event name
        switch ($payload->event) {
        case 'charge.success':
            $this->onetimePayment($payload->data);
            return;
        default:
            Log::info("webhook [$payload->event] is not on the expected list - will now exit'", ['process' => '[paystack-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return response('No expected webhook was found', 200);
        }

    }

    /**
     * process onetime payment
     *
     * @return null
     */
    public function onetimePayment($payload = []) {

        Log::info("webhook is for a onetime payment'", ['process' => '[paystack-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $payload]);

        //get the data from the payload
        try {
            $transaction_id = $payload->reference;
            $amount = $payload->amount / 100;
            $currency = $payload->currency;
            $checkout_session_id = $payload->metadata->custom_fields[0]->value;
        } catch (Exception $e) {
            $error_message = $e->getMessage();
            Log::error("webhook could not be processed. [error] $error_message", ['process' => '[paystack-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //make sure we do not already recorded this payment
        if (\App\Models\Payment::Where('payment_transaction_id', $transaction_id)->exists()) {
            Log::info("this transaction ($transaction_id) has already been recorded", ['process' => '[mollie-webhook]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //make sure we do not already have this queued for processing
        if (\App\Models\Webhook::Where('webhooks_payment_transactionid', $transaction_id)->exists()) {
            Log::info("this transaction ($transaction_id) is already queued for processing", ['process' => '[mollie-webhook]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //record the webhook for processing later
        $webhook = new \App\Models\Webhook();
        $webhook->webhooks_gateway_name = 'paystack';
        $webhook->webhooks_type = 'payment_completed';
        $webhook->webhooks_payment_type = 'onetime';
        $webhook->webhooks_payment_amount = $amount;
        $webhook->webhooks_payment_transactionid = $transaction_id;
        $webhook->webhooks_matching_reference = $checkout_session_id;
        $webhook->webhooks_payload = json_encode($payload);
        $webhook->webhooks_status = 'new';
        $webhook->save();

    }

    /**
     * Verify Paystack webhook signature
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function verifyWebhook() {

        //TEMP
        return true;

    }

}