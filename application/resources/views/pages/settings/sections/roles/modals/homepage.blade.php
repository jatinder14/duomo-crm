<!--item-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">@lang('lang.home_page')</label>
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm select2-preselected" id="role_homepage"
            name="role_homepage" data-preselected="{{ $role->role_homepage ?? ''}}">
            <option></option>
            <option value="dashboard">@lang('lang.dashboard')</option>
            <option value="clients">@lang('lang.clients')</option>
            <option value="projects">@lang('lang.projects')</option>
            <option value="tasks">@lang('lang.tasks')</option>
            <option value="leads">@lang('lang.leads')</option>
            <option value="proposals">@lang('lang.proposals')</option>
            <option value="invoices">@lang('lang.invoices')</option>
            <option value="payments">@lang('lang.payments')</option>
            <option value="estimates">@lang('lang.estimates')</option>
            <option value="products">@lang('lang.products')</option>
            <option value="expenses">@lang('lang.expenses')</option>
            <option value="subscriptions">@lang('lang.subscriptions')</option>
            <option value="tickets">@lang('lang.tickets')</option>
            <option value="knowledgebase">@lang('lang.knowledgebase')</option>
        </select>
    </div>
</div>