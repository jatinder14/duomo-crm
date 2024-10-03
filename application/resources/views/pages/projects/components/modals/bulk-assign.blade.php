<div class="form-group row m-b-50">
    <label class="col-12 text-left control-label col-form-label required">@lang('lang.users')
        <span class="align-middle text-info font-16" data-toggle="tooltip" title="@lang('lang.bulk_assign_info')"
            data-placement="top"><i class="ti-info-alt"></i></span></label>
    <div class="col-12">
        <select name="assigned" id="assigned"
            class="form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
            multiple="multiple" tabindex="-1" aria-hidden="true">
            <!--users list-->
            @foreach(config('system.team_members') as $user)
            <option value="{{ $user->id }}">
                {{ $user->full_name }}</option>
            @endforeach
            <!--/#users list-->
        </select>
    </div>
</div>

<!--skip_notifications-->
<div class="modal-selector m-b-0 m-t-30">
    <div class="form-group form-group-checkbox row">
        <div class="col-12 ">
            <input type="checkbox" id="skip_notifications" name="skip_notifications"
                class="filled-in chk-col-light-blue">
            <label class="p-l-30" for="skip_notifications">@lang('lang.skip_user_notification') <span
                    class="align-middle text-info font-16" data-toggle="tooltip"
                    title="@lang('lang.skip_user_notification_info')" data-placement="top"><i
                        class="ti-info-alt"></i></span></label>
        </div>
    </div>
</div>