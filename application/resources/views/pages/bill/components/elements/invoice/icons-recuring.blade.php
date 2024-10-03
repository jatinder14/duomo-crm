@if(auth()->check() && auth()->user()->is_team)
@if(auth()->check() && auth()->user()->role->role_invoices >= 2)
<!--show editing icon (recurring)-->
<a class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form" href="javascript:void(0)"
    data-toggle="modal" data-target="#commonModal"
    data-url="{{ urlResource('/invoices/'.$bill->bill_invoiceid.'/recurring-settings?source=page') }}"
    data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.recurring_settings')) }}"
    data-action-url="{{ urlResource('/invoices/'.$bill->bill_invoiceid.'/recurring-settings?source=page') }}"
    data-action-method="POST" data-action-ajax-loading-target="invoices-td-container">
    <i class="sl-icon-refresh text-danger cursor-pointer {{ runtimeVisibility('invoice-recurring-icon', $bill->bill_recurring) }}"
        data-toggle="tooltip" id="invoice-recurring-icon" title="{{ cleanLang(__('lang.recurring_invoice')) }}"></i>
</a>
@else
<!--show plain icon (recurring)-->
<i class="sl-icon-refresh text-danger cursor-pointer {{ runtimeVisibility('invoice-recurring-icon', $bill->bill_recurring) }}"
    data-toggle="tooltip" id="invoice-recurring-icon" title="{{ cleanLang(__('lang.recurring_invoice')) }}"></i>
@endif
<!--child invoice-->
@if($bill->bill_recurring_child == 'yes')
<a href="{{ url('invoices/'.$bill->bill_recurring_parent_id) }}">
    <i class="ti-back-right text-success" data-toggle="tooltip" data-html="true"
        title="{{ cleanLang(__('lang.invoice_automatically_created_from_recurring')) }} <br>(#{{ runtimeInvoiceIdFormat($bill->bill_recurring_parent_id) }})"></i>
</a>
@endif
@endif