@if(auth()->check() && auth()->user()->is_team )
@if(auth()->check() && auth()->user()->role->role_estimates >= 2)
<!--show editing icon (automation)-->
<a href="javascript:void(0)" id="estimate-automation-icon"
    class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form {{ runtimeVisibility('estimate-automation-icon', $bill->estimate_automation_status) }}"
    data-toggle="modal" data-target="#commonModal"
    data-url="{{ urlResource('/estimates/'.$bill->bill_estimateid.'/edit-automation') }}"
    data-loading-target="commonModalBody" data-modal-title="@lang('lang.estimate_automation')"
    data-action-url="{{ urlResource('/estimates/'.$bill->bill_estimateid.'/edit-automation') }}"
    data-action-method="POST" data-action-ajax-loading-target="commonModalBody">
    <i class="sl-icon-energy text-warning cursor-pointer" data-toggle="tooltip"
        title="{{ cleanLang(__('lang.estimate_automation')) }}"></i>
</a>
@else
<!--show plain icon (automation)-->
<i class="sl-icon-energy text-warning cursor-pointer {{ runtimeVisibility('estimate-automation-icon', $bill->estimate_automation_status) }}"
    data-toggle="tooltip" id="estimate-automation-icon" title="{{ cleanLang(__('lang.estimate_automation')) }}"></i>
@endif
@endif