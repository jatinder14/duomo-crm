<div class="payment-gateways" id="gateway-mollie">
    <!--MOLLIE BUTTONS-->
    <div class="x-button">
        <a class="btn btn-danger disable-on-click-loading"
            href="{{ $payload['mollie_payment_page_url'] }}">
            {{ cleanLang(__('lang.pay_now')) }} -
            {{ config('system.settings_mollie_display_name') }}</a>
    </div>
</div>