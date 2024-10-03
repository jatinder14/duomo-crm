<div class="signup-form">

    <div class="x-heading m-t-50">
        <h4>@lang('lang.login_to_your_account')</h4>
    </div>
    <div class="x-sub-heading">
        @lang('lang.please_enter_account_domain')?
    </div>
    <form class="form-horizontal form-material x-form p-t-30" id="loginForm" novalidate="novalidate" _lpchecked="1">
        <div class="input-group m-t-0 m-b-50">
            <input type="text" class="form-control" placeholder="@lang('lang.account_name')" name="domain_name" aria-describedby="basic-addon2">
            <span class="input-group-addon x-sub-domain" id="basic-addon2">.{{ runtimeSystemDomain() }}</span>
        </div>
        <div class="form-group text-center m-t-10 p-b-10">
            <div class="col-xs-12">
                <button class="btn btn-info btn-lg btn-block position-relative ajax-request" id="loginSubmitButton"
                    data-button-loading-annimation="yes"
                    data-url="{{ url('account/login') }}" 
                    data-type="form" 
                    data-form-id="loginForm" 
                    data-ajax-type="post"
                    data-button-loading-annimation="yes"
                    data-progress-bar="hidden"
                    type="submit">@lang('lang.continue')</button>
            </div>
        </div>
    </form>

</div>