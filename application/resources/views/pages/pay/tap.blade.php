<div class="payment-gateways" id="gateway-tap">
    <!--TAP BUTTONS-->
    <div class="x-button">
        <button class="btn btn-danger disable-on-click-loading"
            data-payload-publishable-key="{{ config('system.settings2_tap_publishable_key') }}"
            data-payload-language="{{ config('system.settings2_tap_language') }}"
            data-payload-first-name="{{ $tap['first_name'] }}"
            data-payload-last-name="{{ $tap['last_name'] }}"
            data-payload-email="{{ $tap['email'] }}"
            data-payload-phone-code="{{ $tap['country_code'] }}"
            data-payload-phone-number="{{ $tap['phone'] }}"
            data-payload-amount="{{ $tap['amount'] }}"
            data-payload-currency="{{ $tap['currency'] }}"
            data-payload-title="{{ $tap['item_name'] }}"
            data-payload-description="{{ $tap['item_name'] }}"
            data-payload-invoice-id="{{ $tap['invoice_id'] }}"
            data-payload-session-id="{{ $tap['session_id'] }}"
            data-payload-redirect-url="{{ $tap['thank_you_url'] }}"
            id="gateway-button-tap">
            {{ cleanLang(__('lang.pay_now')) }} -
            {{ config('system.settings2_tap_display_name') }}</button>
    </div>
</div>

<!--tap config-->
<div id="tap-payment-container"></div>
<script src="public/js/dynamic/tap.js?v={{ config('system.versioning') }}"></script>
