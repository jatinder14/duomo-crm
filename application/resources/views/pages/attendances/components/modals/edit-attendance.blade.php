<div class="row">
    <div class="col-lg-12">
        @if (auth()->user()->role->role_projects_scope == 'global')
            <div class="form-group row">
                <label
                    class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.team_member')) }}*</label>
                <div class="col-sm-12 col-lg-9">
                    <select name="user_id" id="user_id"
                        class="users_assigned_toggle users_assigned_client_toggle form-control form-control-sm js-select2-basic-search-modal select2-hidden-accessible"
                        data-assigned-dropdown="assigned" data-client-assigned-dropdown="assigned-client"
                        data-ajax--url="{{ url('/') }}/feed/users?ref=general">
                        <option value="{{ $attendance->user_id ?? '' }}">
                            {{ $attendance->user->full_name ?? '' }}
                        </option>
                    </select>
                </div>
            </div>
        @else
            <input type="hidden" name="user_id" id="user_id" value="{{ $attendance->user_id ?? '' }}">
        @endif
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.date')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="date" class="form-control form-control-sm" placeholder="@lang('lang.date')"
                    name="date" id="date" value="{{ $attendance->date ?? '' }}">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.in_time')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="time" class="form-control form-control-sm" placeholder="@lang('lang.in_time')"
                    name="in_time" id="in_time" value="{{ $attendance->in_time ?? '' }}">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.out_time')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="time" class="form-control form-control-sm" placeholder="@lang('lang.out_time')"
                    name="out_time" id="out_time" value="{{ $attendance->out_time ?? '' }}">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.reason')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <textarea class="form-control form-control-sm" placeholder="@lang('lang.reason')" name="reason" id="reason">{{ $attendance->reason ?? '' }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.status')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="attendance_status"
                    name="attendance_status">
                    @foreach ($statuses as $status)
                        <option value="{{ $status->attendancestatus_id }}"
                            {{ runtimePreselected($attendance->attendance_status ?? '', $status->attendancestatus_id) }}>
                            {{ $status->attendancestatus_title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
