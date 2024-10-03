@if(config('visibility.signatures_show_top_line'))
<div class="line m-t-50"></div>
@endif

<div class="doc-signed-panel">
    <div class="row">

        <!--provider signature-->
        <div class="col-6 text-left">
            <div class="p-l-0">
                <ul>
                    <li>
                        <h5>@lang('lang.service_provider')</h5>
                    </li>

                    <!--signed-->
                    @if(config('visibility.doc_provider_signed'))
                    <li class="p-t-10">{{ $document->doc_provider_signed_first_name }}
                        {{ $document->doc_provider_signed_last_name }}</li>
                    <li>
                        <img src="{{ url('storage/files/'.$document->doc_provider_signed_signature_directory .'/'.$document->doc_provider_signed_signature_filename) }}"
                            alt="@lang('lang.signature')" />
                    </li>

                    <!--signature date-->
                    <li class="p-t-15 m-b-10">@lang('lang.date') :
                        {{ runtimeDate($document->doc_provider_signed_date) }}
                        <!--info on why the providers signature can nolonger be delete-->
                        @if(config('visibility.doc_provider_delete_signature_disabled'))
                        <span class="align-middle text-info font-16" data-toggle="tooltip"
                            title="@lang('lang.contract_signature_cannot_be_delete')" data-placement="top"><i
                                class="ti-info-alt"></i></span>
                        @endif
                    </li>
                    @endif

                    <!--unsigned - viewing mode-->
                    @if(config('visibility.doc_provider_unsigned'))
                    <li>
                        <h3 class="muted p-t-10">@lang('lang.unsigned')</h3>
                    </li>
                    @endif


                    <!--add signature-->
                    @if(config('visibility.doc_provider_add_signature'))
                    <li>
                        <button type="button"
                            class="btn btn-secondary btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                            data-toggle="modal" data-target="#commonModal" data-progress-bar="hidden"
                            data-url="{{ url('contracts/'.$document->doc_unique_id.'/sign/team') }}"
                            data-loading-target="commonModalBody" data-modal-title="@lang('lang.sign_contract')"
                            data-action-form-id="" data-modal-size="modal-lg"
                            data-action-url="{{ url('contracts/'.$document->doc_unique_id.'/sign/team') }}"
                            data-action-method="POST" data-action-ajax-class="js-ajax-ux-request">
                            @lang('lang.sign_contract')
                        </button>
                    </li>
                    @endif

                    <!--delete signature-->
                    @if(config('visibility.doc_provider_delete_signature'))
                    <li>
                        <button type="button" class="btn btn-outline-danger btn-sm confirm-action-danger"
                            data-confirm-title="@lang('lang.delete_signature')"
                            data-confirm-text="@lang('lang.are_you_sure')" data-ajax-type="DELETE"
                            data-url="{{ url('contracts/'.$document->doc_unique_id.'/sign/delete-signature') }}">
                            @lang('lang.delete_signature')
                        </button>
                    </li>
                    @endif

                </ul>
            </div>
        </div>
        <!--client signature-->
        <div class="col-6 text-right">
            <div class="p-r-0">
                <ul>
                    <li>
                        <h5>@lang('lang.client')</h5>
                    </li>

                    <!--signed-->
                    @if(config('visibility.doc_client_signed'))
                    <li>{{ $document->doc_signed_first_name }}
                        {{ $document->doc_signed_last_name }}</li>
                    <li>
                        <img src="{{ url('storage/files/'.$document->doc_signed_signature_directory .'/'.$document->doc_signed_signature_filename) }}"
                            alt="@lang('lang.signature')" />
                    </li>

                    <!--signature date-->
                    <li class="p-t-15 m-b-10">@lang('lang.date') :
                        {{ runtimeDate($document->doc_signed_date) }}
                    </li>
                    @endif

                    <!--unsigned - viewing mode-->
                    @if(config('visibility.doc_client_unsigned'))
                    <li>
                        <h3 class="muted p-t-10">@lang('lang.unsigned')</h3>
                    </li>
                    @endif

                    <!--add signature-->
                    @if(config('visibility.doc_client_add_signature'))
                    <li>
                        <button type="button"
                            class="btn btn-secondary btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                            data-toggle="modal" data-target="#commonModal" data-progress-bar="hidden"
                            data-url="{{ url('contracts/'.$document->doc_unique_id.'/sign/client') }}"
                            data-loading-target="commonModalBody" data-modal-title="@lang('lang.sign_contract')"
                            data-action-form-id="" data-modal-size="modal-lg"
                            data-action-url="{{ url('contracts/'.$document->doc_unique_id.'/sign/client') }}"
                            data-action-method="POST" data-action-ajax-class="js-ajax-ux-request">
                            @lang('lang.sign_contract')
                        </button>
                    </li>
                    @endif

                </ul>
            </div>
        </div>
    </div>
</div>

@if(config('visibility.signatures_show_bottom_line'))
<div class="line m-t-20"></div>
@endif