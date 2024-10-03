"use strict";

var $tap_button = $("#gateway-button-tap");

/** --------------------------------------------------------------------------------------------------
 *  get data for this payment
 * --------------------------------------------------------------------------------------------------*/
var tap_payload = {
    'tap_publishable_key': $tap_button.attr('data-payload-publishable-key'),
    'language': $tap_button.attr('data-payload-language'),
    'customer_first_name': $tap_button.attr('data-payload-first-name'),
    'customer_last_name': $tap_button.attr('data-payload-last-name'),
    'customer_email': $tap_button.attr('data-payload-email'),
    'customer_phone_country_code': $tap_button.attr('data-payload-phone-code'),
    'customer_phone_number': $tap_button.attr('data-payload-phone-number'),
    'amount': $tap_button.attr('data-payload-amount'),
    'currency_code': $tap_button.attr('data-payload-currency'),
    'item_title': $tap_button.attr('data-payload-title'),
    'item_description': $tap_button.attr('data-payload-description'),
    'invoice_id': $tap_button.attr('data-payload-invoice-id'),
    'session_id': $tap_button.attr('data-payload-session-id'),
    'redirect_url': $tap_button.attr('data-payload-redirect-url'),
};

/** --------------------------------------------------------------------------------------------------
 *  pay button clicked - initiate
 * --------------------------------------------------------------------------------------------------*/
$(document).on('click', '#gateway-button-tap', function(){
    goSell.openPaymentPage()
});

/** --------------------------------------------------------------------------------------------------
 *  trigger print dialogue
 * --------------------------------------------------------------------------------------------------*/
goSell.config({
    containerID: "tap-payment-container",
    gateway: {
        publicKey: tap_payload.tap_publishable_key,
        language: "en",
        contactInfo: true,
        supportedCurrencies: "all",
        supportedPaymentMethods: "all",
        saveCardOption: false,
        customerCards: true,
        notifications: 'standard',
        callback: (response) => {
            console.log('response', response);
        },
        onClose: () => {
            console.log("onClose Event");
        },
    },
    customer: {
        id: "",
        first_name: tap_payload.customer_first_name,
        middle_name: "",
        last_name: tap_payload.customer_last_name,
        email: tap_payload.customer_email,
        phone: {
            country_code: "965",
            number: "99999999"
        }
    },
    order: {
        amount: tap_payload.amount,
        currency: tap_payload.currency_code,
        shipping: null,
        taxes: null
    },
    transaction: {
        mode: 'charge',
        charge: {
            saveCard: false,
            threeDSecure: true,
            description: tap_payload.item_title,
            statement_descriptor: tap_payload.item_description,
            reference: {
                transaction: tap_payload.session_id,
                order: tap_payload.invoice_id
            },
            metadata: {},
            receipt: {
                email: false,
                sms: true
            },
            redirect: tap_payload.redirect_url,
            post: null,
        }
    }
});