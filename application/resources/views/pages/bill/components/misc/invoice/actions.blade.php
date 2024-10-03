<!--CRUMBS CONTAINER (RIGHT)-->
<div class="col-md-12  col-lg-5 align-self-center text-right p-b-9 {{ $page['list_page_actions_size'] ?? '' }} {{ $page['list_page_container_class'] ?? '' }}"
    id="list-page-actions-container">
    <div id="list-page-actions">
        @if(auth()->user()->is_team && auth()->user()->role->role_invoices >= 2)
        <!--reminder-->
        @if(config('visibility.modules.reminders'))
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.reminder')) }}"
            id="reminders-panel-toggle-button"
            class="reminder-toggle-panel-button list-actions-button btn btn-page-actions waves-effect waves-dark js-toggle-reminder-panel ajax-request {{ $bill->reminder_status }}"
            data-url="{{ url('reminders/start?resource_type=invoice&resource_id='.$bill->bill_invoiceid) }}"
            data-loading-target="reminders-side-panel-body" data-progress-bar='hidden'
            data-target="reminders-side-panel" data-title="@lang('lang.my_reminder')">
            <i class="ti-alarm-clock"></i>
        </button>
        @endif
        <!--publish invoice-->
        @if($bill->bill_status == 'draft')
        <span class="dropdown">
            <button type="button" data-toggle="dropdown" title="{{ cleanLang(__('lang.publish_invoice')) }}"
                aria-haspopup="true" aria-expanded="false"
                class="data-toggle-tooltip  list-actions-button btn btn-page-actions waves-effect waves-dark">
                <i class="sl-icon-share-alt"></i>
            </button>
            <div class="dropdown-menu w-px-250 p-t-20 p-l-20 p-r-20 js-stop-propagation"
                aria-labelledby="listTableAction">
                <div class="form-group form-group-checkbox row m-b-0">
                    <div class="col-12">
                        <input type="checkbox" id="publishing_option_now" name="publishing_option_now"
                            class="filled-in chk-col-light-blue publishing_option"
                            data-url="{{ urlResource('/invoices/'.$bill->bill_invoiceid.'/publish') }}" {{ runtimePreChecked2($bill->bill_publishing_type, 'instant') }}>
                        <label class="p-l-30" for="publishing_option_now">@lang('lang.publish_now') <span
                                class="align-middle text-info font-16" data-toggle="tooltip"
                                title="@lang('lang.it_will_be_sent_now')" data-placement="top"><i
                                    class="ti-info-alt"></i></span></label>
                    </div>
                </div>

                <div class="modal-selector m-l--20 m-r--20 p-t-5 p-b-5 m-t-10 p-l-20 p-r-20 p-t-10" id="publishing_option_later_container">

                    <div class="form-group form-group-checkbox row  m-b-0">
                        <div class="col-12">
                            <input type="checkbox" id="publishing_option_later" name="publishing_option_later"
                                class="filled-in chk-col-light-blue publishing_option" 
                                data-url="{{ urlResource('/invoices/'.$bill->bill_invoiceid.'/publish/scheduled') }}"
                                data-type="form" data-form-id="publishing_option_later_container" data-ajax-type="post"  {{ runtimePreChecked2($bill->bill_publishing_type, 'scheduled') }}>
                            <label class="p-l-30" for="publishing_option_later">@lang('lang.publish_later') <span
                                    class="align-middle text-info font-16" data-toggle="tooltip"
                                    title="@lang('lang.it_will_be_sent_schedule')" data-placement="top"><i
                                        class="ti-info-alt"></i></span></label>
                        </div>
                    </div>

                    <!--date-->
                    <div class="form-group row m-b-10">
                        <div class="col-sm-12">
                            <input type="text" class="form-control form-control-sm pickadate publishing_option_date"
                                autocomplete="off" name="publishing_option_date"
                                value="{{ runtimeDatepickerDate($bill->bill_publishing_scheduled_date ?? '') }}" {{ runtimePublihItemDate($bill->bill_publishing_type) }}>
                            <input class="mysql-date" type="hidden" name="publishing_option_date"
                                id="publishing_option_date" value="{{ $bill->bill_publishing_scheduled_date	 ?? '' }}">
                        </div>
                    </div>
                </div>
                <!--form buttons-->
                <div class="text-right p-t-5 m-b-10">
                    <button type="submit" id="publishing_option_button" class="btn btn-sm btn-info waves-effect text-left"
                        data-url="" data-loading-target="" data-ajax-type="POST"
                        data-lang-error-message="@lang('lang.schedule_date_is_requried')"
                        data-lang-publish="@lang('lang.publish_now')"
                        data-lang-schedule="@lang('lang.schedule')"
                        data-on-start-submit-button="disable">{{ runtimePublihItemButtonLang($bill->bill_publishing_type) }}</button>
                </div>
            </div>
        </span>
        @endif

        <!--email invoice-->
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.send_email')) }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark confirm-action-info"
            href="javascript:void(0)" data-confirm-title="{{ cleanLang(__('lang.send_email')) }}"
            data-confirm-text="{{ cleanLang(__('lang.confirm')) }}"
            data-url="{{ urlResource('/invoices/'.$bill->bill_invoiceid.'/resend') }}"
            id="invoice-action-email-invoice"><i class="ti-email"></i></button>
        <!--add payment-->
        <button type="button" title="{{ cleanLang(__('lang.add_a_payment')) }}" id="invoiceAddPaymentButton"
            class="data-toggle-tooltip list-actions-button btn btn-page-actions waves-effect waves-dark js-ajax-ux-request reset-target-modal-form edit-add-modal-button"
            data-toggle="modal" data-target="#commonModal" data-modal-title="{{ cleanLang(__('lang.add_a_payment')) }}"
            data-url="{{ url('/payments/create?bill_invoiceid='.$bill->bill_invoiceid) }}"
            data-action-url="{{ url('/payments?ref=invoice&source=page&bill_invoiceid='.$bill->bill_invoiceid) }}"
            data-loading-target="actionsModalBody" data-action-method="POST">
            <i class="ti-credit-card"></i>
        </button>
        <!--recurring options-->
        <span class="dropdown">
            <button type="button" data-toggle="dropdown" title="{{ cleanLang(__('lang.recurring_options')) }}"
                aria-haspopup="true" aria-expanded="false"
                class="data-toggle-tooltip  list-actions-button btn btn-page-actions waves-effect waves-dark">
                <i class="sl-icon-refresh"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="listTableAction">
                <!--recurring settings-->
                <a class="dropdown-item edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                    href="javascript:void(0)" data-toggle="modal" data-target="#commonModal"
                    data-url="{{ urlResource('/invoices/'.$bill->bill_invoiceid.'/recurring-settings?source=page') }}"
                    data-loading-target="commonModalBody"
                    data-modal-title="{{ cleanLang(__('lang.recurring_settings')) }}"
                    data-action-url="{{ urlResource('/invoices/'.$bill->bill_invoiceid.'/recurring-settings?source=page') }}"
                    data-action-method="POST" data-action-ajax-loading-target="invoices-td-container"><i
                        class="sl-icon-settings display-inline-block p-r-5"></i>{{ cleanLang(__('lang.recurring_settings')) }}</a>

                <!--view child invoices-->
                <a class="dropdown-item {{ runtimeVisibility('invoice-view-child-invoices', $bill->bill_recurring) }}"
                    href="{{ url('invoices?filter_recurring_parent_id=').$bill->bill_invoiceid }}"
                    id="invoice-action-view-children"><i
                        class="sl-icon-docs display-inline-block p-r-5"></i>{{ cleanLang(__('lang.view_child_invoices')) }}</a>

                <!--stop recurring-->
                <a class="dropdown-item confirm-action-info display-block {{ runtimeVisibility('invoice-stop-recurring', $bill->bill_recurring) }}"
                    href="javascript:void(0)" data-confirm-title="{{ cleanLang(__('lang.stop_recurring')) }}"
                    data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                    data-url="{{ urlResource('/invoices/'.$bill->bill_invoiceid.'/stop-recurring') }}"
                    id="invoice-action-stop-recurring">
                    <i class="sl-icon-ban display-inline-block p-r-5"></i>{{ cleanLang(__('lang.stop_recurring')) }}</a>
            </div>
        </span>
        <!--clone-->
        <span class="dropdown">
            <button type="button" class="data-toggle-tooltip list-actions-button btn btn-page-actions waves-effect waves-dark 
                        actions-modal-button js-ajax-ux-request reset-target-modal-form edit-add-modal-button"
                title="{{ cleanLang(__('lang.clone_invoice')) }}" data-toggle="modal" data-target="#commonModal"
                data-modal-title="{{ cleanLang(__('lang.clone_invoice')) }}"
                data-url="{{ url('/invoices/'.$bill->bill_invoiceid.'/clone') }}"
                data-action-url="{{ url('/invoices/'.$bill->bill_invoiceid.'/clone') }}"
                data-loading-target="actionsModalBody" data-action-method="POST">
                <i class=" mdi mdi-content-copy"></i>
            </button>
        </span>
        <!--edit-->
        <span class="dropdown">
            <button type="button" data-toggle="dropdown" title="{{ cleanLang(__('lang.edit')) }}" aria-haspopup="true"
                aria-expanded="false"
                class="data-toggle-tooltip list-actions-button btn btn-page-actions waves-effect waves-dark">
                <i class="sl-icon-note"></i>
            </button>

            <div class="dropdown-menu" aria-labelledby="listTableAction">
                <!--edit-->
                <a class="dropdown-item" href="{{ url('/invoices/'.$bill->bill_invoiceid.'/edit-invoice') }}"><i
                        class="sl-icon-note display-inline-block p-r-5"></i>{{ cleanLang(__('lang.edit_invoice')) }}</a>

                <!--attach project-->
                <a class="dropdown-item confirm-action-danger {{ runtimeVisibility('dettach-invoice', $bill->bill_projectid)}}"
                    href="javascript:void(0)" data-confirm-title="{{ cleanLang(__('lang.detach_from_project')) }}"
                    id="bill-actions-dettach-project" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                    data-url="{{ urlResource('/invoices/'.$bill->bill_invoiceid.'/detach-project') }}">
                    <i
                        class="sl-icon-docs display-inline-block p-r-5"></i>{{ cleanLang(__('lang.detach_from_project')) }}</a>

                <!--deattach project-->
                <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form {{ runtimeVisibility('attach-invoice', $bill->bill_projectid)}}"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    id="bill-actions-attach-project" data-modal-title="{{ cleanLang(__('lang.attach_to_project')) }}"
                    data-url="{{ urlResource('/invoices/'.$bill->bill_invoiceid.'/attach-project?client_id='.$bill->bill_clientid) }}"
                    data-action-url="{{ urlResource('/invoices/'.$bill->bill_invoiceid.'/attach-project') }}"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    <i
                        class="sl-icon-doc display-inline-block p-r-5"></i>{{ cleanLang(__('lang.attach_to_project')) }}</a>
            </div>

        </span>
        <!--delete-->
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.delete_invoice')) }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark confirm-action-danger"
            data-confirm-title="{{ cleanLang(__('lang.delete_invoice')) }}"
            data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
            data-url="{{ url('/') }}/invoices/{{ $bill->bill_invoiceid }}?source=page"><i
                class="sl-icon-trash"></i></button>

        @endif

        <!--reminder-->
        @if(auth()->check() && auth()->user()->is_client)
        @if(config('visibility.modules.reminders'))
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.reminder')) }}"
            id="reminders-panel-toggle-button"
            class="reminder-toggle-panel-button list-actions-button btn btn-page-actions waves-effect waves-dark js-toggle-reminder-panel ajax-request {{ $bill->reminder_status }}"
            data-url="{{ url('reminders/start?resource_type=invoice&resource_id='.$bill->bill_invoiceid) }}"
            data-loading-target="reminders-side-panel-body" data-progress-bar='hidden'
            data-target="reminders-side-panel" data-title="@lang('lang.my_reminder')">
            <i class="ti-alarm-clock"></i>
        </button>
        @endif
        @endif

        <!--Download PDF-->
        <a data-toggle="tooltip" title="{{ cleanLang(__('lang.download')) }}" id="invoiceDownloadButton"
            href="{{ url('/invoices/'.$bill->bill_invoiceid.'/pdf') }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark" download>
            <i class="mdi mdi-download"></i>
        </a>

    </div>
</div>