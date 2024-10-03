<!--customer type selector-->
<div class="modal-selector">
    <!--client-->
    <div class="customer-selector-container" id="customer-type-client-container">
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">@lang('lang.client')</label>
            <div class="col-sm-12 col-lg-9">
                <!--select2 basic search-->
                <select name="doc_client_id" id="doc_client_id"
                    class="form-control form-control-sm js-select2-basic-search-modal select2-hidden-accessible"
                    data-ajax--url="{{ url('/') }}/feed/company_names"></select>
                <!--select2 basic search-->
                </select>
            </div>
        </div>
    </div>

    <!--lead-->
    <div class="customer-selector-container hidden" id="customer-type-lead-container">
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">@lang('lang.lead')</label>
            <div class="col-sm-12 col-lg-9">
                <!--select2 basic search-->
                <select name="doc_lead_id" id="doc_lead_id"
                    class="form-control form-control-sm js-select2-basic-search-modal select2-hidden-accessible"
                    data-ajax--url="{{ url('/') }}/feed/leadnames?ref=general"></select>
                <!--select2 basic search-->
                </select>
            </div>
        </div>
    </div>

    <!--option buttons-->
    <div class="modal-selector-links">
        <a href="javascript:void(0)" class="customer-type-selector" data-type="lead"
            data-target-container="customer-type-lead-container">@lang('lang.lead')</a> |
        <a href="javascript:void(0)" class="customer-type-selector active" data-type="client"
            data-target-container="customer-type-client-container">@lang('lang.client')</a>
    </div>

    <!--client type indicator-->
    <input type="hidden" name="customer-selection-type" id="customer-selection-type" value="client">
</div>

<!--proposal_title-->
<div class="form-group row">
    <label
        class="col-sm-12 col-lg-3 text-left control-label col-form-label required">@lang('lang.proposal_title')</label>
    <div class="col-sm-12 col-lg-9">
        <input type="text" class="form-control form-control-sm" id="doc_title" name="doc_title"
            value="{{ $proposal->doc_title ?? '' }}">
    </div>
</div>

<!--proposal_date-->
<div class="form-group row">
    <label
        class="col-sm-12 col-lg-3 text-left control-label col-form-label required">@lang('lang.proposal_date')</label>
    <div class="col-sm-12 col-lg-9">
        <input type="text" class="form-control form-control-sm pickadate" autocomplete="off" name="doc_date_start"
            value="{{ runtimeDatepickerDate($proposal->doc_date_start ?? '') }}">
        <input class="mysql-date" type="hidden" name="doc_date_start" id="doc_date_start"
            value="{{ $proposal->doc_date_start ?? '' }}">
    </div>
</div>


<!--valid_until-->
<div class="form-group row">
    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">@lang('lang.valid_until')</label>
    <div class="col-sm-12 col-lg-9">
        <input type="text" class="form-control form-control-sm pickadate" autocomplete="off" name="doc_date_end"
            value="{{ runtimeDatepickerDate($proposal->doc_date_end ?? '') }}">
        <input class="mysql-date" type="hidden" name="doc_date_end" id="doc_date_end"
            value="{{ $proposal->doc_date_end ?? '' }}">
    </div>
</div>

<!--category-->
<div class="form-group row">
    <label
        class="col-sm-12 col-lg-3 text-left control-label col-form-label  required">{{ cleanLang(__('lang.category')) }}</label>
    <div class="col-sm-12 col-lg-9">
        <select class="select2-basic form-control form-control-sm" id="doc_categoryid" name="doc_categoryid">
            @foreach($categories as $category)
            <option value="{{ $category->category_id }}"
                {{ runtimePreselected($proposal->doc_categoryid ?? '', $category->category_id) }}>{{
                        runtimeLang($category->category_name) }}</option>
            @endforeach
        </select>
    </div>
</div>