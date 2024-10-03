<div class="row">
    <div class="col-lg-12">
        @if (auth()->user()->role->role_projects_scope == 'global')
            <div class="form-group row">
                <label
                    class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.team_member')) }}*</label>
                <div class="col-sm-12 col-lg-9">
                    <select name="leave_creatorid" id="leave_creatorid"
                        class="users_assigned_toggle users_assigned_client_toggle form-control form-control-sm js-select2-basic-search-modal select2-hidden-accessible"
                        data-assigned-dropdown="assigned" data-client-assigned-dropdown="assigned-client"
                        data-ajax--url="{{ url('/') }}/feed/users?ref=general"></select>
                </div>
            </div>
        @else
            <input type="hidden" name="leave_creatorid" id="leave_creatorid" value="{{ auth()->id() }}">
        @endif

        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.date_from')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="date" class="form-control form-control-sm" placeholder="@lang('lang.date_from')"
                    name="leave_date_from" id="leave_date_from">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.date_to')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="date" class="form-control form-control-sm" placeholder="@lang('lang.date_to')"
                    name="leave_date_to" id="leave_date_to">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.category')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="leave_categoryid"
                    name="leave_categoryid">
                    @foreach ($categories as $category)
                        <option value="{{ $category->leavecategory_id }}">{{ $category->leavecategory_title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.reason')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <textarea class="form-control form-control-sm" placeholder="@lang('lang.reason')" name="leave_reason" id="leave_reason"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.status')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="leave_status" name="leave_status">
                    @foreach ($statuses as $status)
                        <option value="{{ $status->leavestatus_id }}">{{ $status->leavestatus_title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.comment')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <textarea class="form-control form-control-sm" placeholder="@lang('lang.comment')" name="leave_comment" id="leave_comment"></textarea>
            </div>
        </div>
    </div>
</div>
