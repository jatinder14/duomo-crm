<div class="doc-to-by-container">

    <div class="row">

        <!--COMPANY DETAILS-->
        <div class="col-6">
            <div class="doc-to-by">
                <!--title-->
                <div class="">
                    <h3>@lang('lang.service_provider')</h3>
                </div>
                <!--organisation & address-->
                <div class="x-title resizetext">
                    <h4 class="font-weight-500">{{ config('system.settings_company_name') }}</h4>
                    @if(config('system.settings_company_address_line_1'))
                    <div>{{ config('system.settings_company_address_line_1') }}</div>
                    @endif
                    @if(config('system.settings_company_city'))
                    <div>{{ config('system.settings_company_city') }}</div>
                    @endif
                    @if(config('system.settings_company_state'))
                    <div>{{ config('system.settings_company_state') }}</div>
                    @endif
                    @if(config('system.settings_company_zipcode'))
                    <div>{{ config('system.settings_company_zipcode') }}</div>
                    @endif
                    @if(config('system.settings_company_country'))
                    <div>{{ config('system.settings_company_country') }}</div>
                    @endif
                    @if(config('system.settings_company_customfield_1') != '')
                    <div>{{ config('system.settings_company_customfield_1') }}</div>
                    @endif
                    @if(config('system.settings_company_customfield_2') != '')
                    <div>{{ config('system.settings_company_customfield_2') }}</div>
                    @endif
                    @if(config('system.settings_company_customfield_3') != '')
                    <div>{{ config('system.settings_company_customfield_3') }}</div>
                    @endif
                    @if(config('system.settings_company_customfield_4') != '')
                    <div>{{ config('system.settings_company_customfield_4') }}</div>
                    @endif
                </div>
            </div>
        </div>


        <!--CLIENT RESOURCE-->
        @if($document->docresource_type == 'client')
        <div class="col-6">
            <div class="doc-to-by text-right">
                <!--title-->
                <div class="">
                    <h3>@lang('lang.client')</h3>
                </div>
                <!--organisation & address-->
                <div class="x-title resizetext">
                    <h4 class="font-weight-500">{{ $document->client_company_name }}</h4>
                    @if($document->client_billing_street)
                    <div>{{ $document->client_billing_street }}</div>
                    @endif
                    @if($document->client_billing_city)
                    <div>{{ $document->client_billing_city }}</div>
                    @endif
                    @if($document->client_billing_state)
                    <div>{{ $document->client_billing_state }}</div>
                    @endif
                    @if($document->client_billing_zip)
                    <div>{{ $document->client_billing_zip }}</div>
                    @endif
                    @if($document->client_billing_country)
                    <div>{{ $document->client_billing_country }}</div>
                    @endif
                    <!--custom fields-->
                    @foreach($customfields as $field)
                    @if($field->customfields_show_invoice == 'yes' && $field->customfields_status == 'enabled')
                    @php $key = $field->customfields_name; @endphp
                    @php $customfield = $document[$key] ?? ''; @endphp
                    @if($customfield != '')
                    <div class="text-muted m-t-3">{{ $field->customfields_title }}: {{ runtimeCustomFieldsFormat($customfield, $field->customfields_datatype) }}</div>
                    @endif
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif



        <!--LEAD RESOURCE-->
        @if($document->docresource_type == 'lead')
        <div class="col-sm-12 col-lg-6">
            <div class="doc-to-by text-right">
                <!--title-->
                <div class="">
                    <h3>@lang('lang.client')</h3>
                </div>
                <!--organisation & address-->
                <div class="x-title resizetext">
                    @if($document->lead_company_name)
                    <h4 class="font-weight-500">{{ $document->lead_company_name }}</h4> 
                    @else 
                    <h4 class="font-weight-500">{{ $document->lead_firstname }} {{ $document->lead_lastname }}
                    </h4>
                    @endif
                    @if($document->lead_street)
                    <div>{{ $document->lead_street }}</div>
                    @endif
                    @if($document->lead_city)
                    <div>{{ $document->lead_city }}</div>
                    @endif
                    @if($document->lead_state)
                    <div>{{ $document->lead_state }}</div>
                    @endif
                    @if($document->lead_zip)
                    <div>{{ $document->lead_zip }}</div>
                    @endif
                    @if($document->lead_country)
                    <div>{{ $document->lead_country }}</div>
                    @endif
                </div>
            </div>
        </div>
        @endif





    </div>
</div>