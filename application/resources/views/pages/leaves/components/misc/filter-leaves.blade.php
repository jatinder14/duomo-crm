<!-- right-sidebar -->
<div class="right-sidebar" id="sidepanel-filter-leaves">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i class="icon-Filter-2"></i>{{ cleanLang(__('lang.filter_leaves')) }}
                <span>
                    <i class="ti-close js-close-side-panels" data-target="sidepanel-filter-leaves"></i>
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
                                    <select name="filter_leave_creatorid" id="filter_leave_creatorid"
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
                        {{ cleanLang(__('lang.date_from')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="date" class="form-control form-control-sm"
                                    placeholder="@lang('lang.date_from')" name="filter_leave_date_from"
                                    id="filter_leave_date_from">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.date_to')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="date" class="form-control form-control-sm"
                                    placeholder="@lang('lang.date_to')" name="filter_leave_date_to"
                                    id="filter_leave_date_to">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.category')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select class="select2-basic form-control form-control-sm" id="filter_leave_categoryid"
                                    name="filter_leave_categoryid">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->leavecategory_id }}">
                                            {{ $category->leavecategory_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.reason')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <textarea class="form-control form-control-sm" placeholder="@lang('lang.reason')" name="filter_leave_reason"
                                    id="filter_leave_reason"></textarea>
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
                                <select class="select2-basic form-control form-control-sm" id="filter_leave_status"
                                    name="filter_leave_status">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->leavestatus_id }}">
                                            {{ $status->leavestatus_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.comment')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <textarea class="form-control form-control-sm" placeholder="@lang('lang.comment')" name="filter_leave_comment"
                                    id="filter_leave_comment"></textarea>
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
                        data-url="{{ urlResource('/leaves/search?') }}" data-type="form"
                        data-ajax-type="GET">{{ cleanLang(__('lang.apply_filter')) }}</button>
                </div>
            </div>
            <!--body-->
        </div>
    </form>
</div>
<!--sidebar-->
