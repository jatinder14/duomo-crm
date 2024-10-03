<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;
use Illuminate\Support\Facades\Http;
use Log;

class PaystackPaymentRepository {

    /**
     * The fooo repository instance.
     */
    protected $fooo;

    /**
     * Inject dependecies
     */
    public function __construct() {

    }

    /** ----------------------------------------------------
     * [onetime payment]
     * Start the process for a single paystack payment
     * @param array $data information payload
     * @return int session id
     * ---------------------------------------------------*/
    public function onetimePayment($data = []) {

        Log::info("paystack onetime payment request initiated", ['process' => '[paystack-payment]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);

        //validate
        if (!is_array($data)) {
            Log::error("invalid paymment payload data", ['process' => '[paystack-payment]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
            return false;
        }

        //create our own checkout session id
        $checkout_session_id = str_unique();

        // payment information
        $payload = [
            'email' => $data['email'],
            'amount' => $data['amount'] * 100,
            'currency' => $data['currency'],
            'callback_url ' => $data['thank_you_url'],
            'metadata' => [
                'custom_fields' => [
                    [
                        'display_name' => 'checkout_session_id',
                        'variable_name' => 'checkout_session_id',
                        'value' => $checkout_session_id,
                    ],
                ],
            ],
        ];

        Log::info("testing paystack payload", ['payload' => $payload]);

        try {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('system.settings2_paystack_secret_key'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post('https://api.paystack.co/transaction/initialize', $payload);

            //get response from paystack
            if ($response->successful()) {

                //response
                $response = $response->json();

                //was a payment url provided
                if (isset($response['data']['authorization_url'])) {
                    Log::info("paystack onetime payment initiated - success", ['process' => '[paystack-payment]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'response' => $response]);

                    //save session id in sessions database
                    $payment_session = new \App\Models\PaymentSession();
                    $payment_session->session_creatorid = auth()->id();
                    $payment_session->session_creator_fullname = auth()->user()->first_name . ' ' . auth()->user()->last_name;
                    $payment_session->session_creator_email = auth()->user()->email;
                    $payment_session->session_gateway_name = 'paystack';
                    $payment_session->session_gateway_ref = $checkout_session_id;
                    $payment_session->session_amount = $data['amount'];
                    $payment_session->session_invoices = $data['invoice_id'];
                    $payment_session->save();

                    //return the checkout url
                    return $response['data']['authorization_url'];
                    
                } else {
                    Log::error("paystack onetime payment initiated - failed", ['process' => '[paystack-payment]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'response' => $response]);
                    return false;
                }
            } else {
                $error = $response->json();
                $error_message = $error['message'];
                Log::error("paystack onetime payment initiated - failed - [error]: $error_message", ['process' => '[paystack-payment]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
                return false;
            }
        } catch (Exception $e) {
            $error_message = $e->getMessage();
            Log::error("paystack onetime payment initiated - failed - [error] $error_message ", ['process' => '[paystack-payment]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
            return false;
        }

    }

}