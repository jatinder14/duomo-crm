<select name="assigned" id="assigned"
    class="form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
    multiple="multiple" tabindex="-1" aria-hidden="true">
    <!--array of currently assigned-->
    @if(isset($assigned))
    @foreach($assigned as $user)
    @php $users[] = $user->id; @endphp
    @endforeach
    @endif
    <!--users list-->
    @foreach(config('system.team_members') as $user)
    <option value="{{ $user->id }}" {{ runtimePreselectedInArray($user->id ?? '', $users ?? []) }}>{{
    $user->full_name }}</option>
    @endforeach
    <!--/#users list-->
</select>

<div class="alert alert-info m-t-40">
    <h5 class="text-info"><i class="sl-icon-info"></i> @lang('lang.info')</h5>@lang('lang.leads_assigned_info')
</div>

<!--form buttons-->
<div class="text-right p-t-30">
    <button type="submit" id="submitButton" class="btn btn-danger waves-effect text-left ajax-request" 
        data-url="{{ url('settings/webforms/'.$webform->webform_id.'/assigned') }}"
        data-loading-target="actionsModalBody" 
        data-ajax-type="POST" 
        data-type="form" 
        data-form-id="actionsModalBody" 
        data-on-start-submit-button="disable">Submit~</button>
</div>