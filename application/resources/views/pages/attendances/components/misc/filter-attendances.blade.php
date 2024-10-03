<!-- right-sidebar -->
<div class="right-sidebar" id="sidepanel-filter-attendances">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i class="icon-Filter-2"></i>{{ cleanLang(__('lang.filter_attendances')) }}
                <span>
                    <i class="ti-close js-close-side-panels" data-target="sidepanel-filter-attendances"></i>
                </span>
            </div>
            <!--body-->
            <div class="r-panel-body">
                @if (auth()->user()->role->role_projects_scope == 'global')
                    <!-- team member -->
                    <div class="filter-block">
                        <div class="title">
                            {{ cleanLang(__('lang.team_members')) }}
                        </div>
                        <div class="fields">
                            <div class="row">
                                <div class="col-md-12">
                                    <select name="filter_user_id" id="filter_user_id"
                                        class="form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
                                        multiple="multiple" tabindex="-1" aria-hidden="true">
                                        @foreach (config('system.team_members') as $user)
                                            <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!--date-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.date')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="filter_date" class="form-control form-control-sm pickadate"
                                    autocomplete="off" placeholder="{{ cleanLang(__('lang.date')) }}">
                                <input class="mysql-date" type="hidden" name="filter_date" id="filter_date"value="">
                            </div>
                        </div>
                    </div>
                </div>

                <!--in time-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.in_time')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="time" name="filter_in_time" id="filter_in_time"
                                    class="form-control form-control-sm" autocomplete="off"
                                    placeholder="{{ cleanLang(__('lang.in_time')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!--out time-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.out_time')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="time" name="filter_out_time" id="filter_out_time"
                                    class="form-control form-control-sm" autocomplete="off"
                                    placeholder="{{ cleanLang(__('lang.out_time')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!--reason-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.reason')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <textarea name="filter_reason" id="filter_reason" class="form-control form-control-sm" autocomplete="off"
                                    placeholder="{{ cleanLang(__('lang.reason')) }}"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.status')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select class="select2-basic form-control form-control-sm" id="filter_attendance_status"
                                    name="filter_attendance_status">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->attendancestatus_id }}">
                                            {{ $status->attendancestatus_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!--buttons-->
                <div class="buttons-block">
                    <button type="button" name="foo1"
                        class="btn btn-rounded-x btn-secondary js-reset-filter-side-panel">{{ cleanLang(__('lang.reset')) }}</button>
                    <input type="hidden" name="action" value="search">
                    <input type="hidden" name="source" value="{{ $page['source_for_filter_panels'] ?? '' }}">
                    <button type="button" class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button"
                        data-url="{{ urlResource('/attendances/search?') }}" data-type="form"
                        data-ajax-type="GET">{{ cleanLang(__('lang.apply_filter')) }}</button>
                </div>
            </div>
            <!--body-->
        </div>
    </form>
</div>
<!--sidebar-->
