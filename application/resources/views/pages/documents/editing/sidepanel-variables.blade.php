<!-- right-sidebar -->
<div class="right-sidebar documents-side-panel-variables" id="documents-side-panel-variables">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <!--add class'due'to title panel -->
                <i class="ti-write display-inline-block m-t--5"></i>
                <div class="display-inline-block">
                    @lang('lang.variables')
                </div>
                <span>
                    <i class="ti-close js-close-side-panels" data-target="documents-side-panel-variables"
                        id="documents-side-panel-variables-close-icon"></i>
                </span>
            </div>
            <!--title-->
            <!--body-->
            <div class="r-panel-body documents-side-panel-variables-body  p-b-80"
                id="documents-side-panel-variables-body">

                <div class="alert alert-info">
                    @lang('lang.variables_instruction')
                </div>

                <ul class="x-ducument-variables-list">

                    <li>{company_name}</li>
                    <li>{client_company_name}</li>
                    <li>{client_first_name}</li>
                    <li>{client_last_name}</li>
                    <li>{client_phone}</li>
                    <li>{client_street}</li>
                    <li>{client_city}</li>
                    <li>{client_state}</li>
                    <li>{client_zip}</li>
                    <li>{client_country}</li>
                    <li>{client_website}</li>


                    <!--proposal variables-->
                    @if($document->doc_type == 'proposal')
                    <li>{proposal_id}</li>
                    <li>{title}</li>
                    <li>{proposal_date}</li>
                    <li>{expiry_date}</li>
                    <li>{prepared_by_name}</li>
                    <li>{pricing_table}</li>
                    <li>{pricing_total}</li>
                    @endif

                    <!--contract variables-->
                    @if($document->doc_type == 'contract')
                    <li>{contract_id}</li>
                    <li>{contract_title}</li>
                    <li>{contract_date}</li>
                    <li>{contract_end_date}</li>
                    <li>{contract_value}</li>
                    <li>{prepared_by_name}</li>
                    <li>{pricing_table}</li>
                    <li>{pricing_table_total}</li>
                    @endif

                    <li>{todays_date}</li>
                </ul>

            </div>
            <!--body-->
        </div>
    </form>
</div>
<!--sidebar-->