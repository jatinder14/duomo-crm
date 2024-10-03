<div class="payment-gateways" id="gateway-tap">
    <!--TAP BUTTONS-->
    <div class="x-button">
        <a class="btn btn-danger disable-on-click-loading" href="{{ $checkout_url }}"  id="gateway-button-paystack">
            {{ cleanLang(__('lang.pay_now')) }} -
            {{ config('system.settings2_paystack_display_name') }}</a>
    </div>
</div>