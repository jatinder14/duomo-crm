<!-- right-sidebar -->
<div class="right-sidebar" id="sidepanel-filter-tickets">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i class="icon-Filter-2"></i>{{ cleanLang(__('lang.filter_tickets')) }}
                <span>
                    <i class="ti-close js-close-side-panels" data-target="sidepanel-filter-tickets"></i>
                </span>
            </div>
            <!--title-->
            <!--body-->
            <div class="r-panel-body">

                <!--company name-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.client_name')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="filter_ticket_clientid" id="filter_ticket_clientid"
                                    class="clients_and_projects_toggle form-control form-control-sm js-select2-basic-search select2-hidden-accessible"
                                    data-projects-dropdown="filter_ticket_projectid"
                                    data-feed-request-type="filter_tickets"
                                    data-ajax--url="{{ url('/') }}/feed/company_names"></select>
                            </div>
                        </div>
                    </div>
                </div>

                <!--project-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.project')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select
                                    class="select2-basic form-control form-control-sm dynamic_filter_ticket_projectid"
                                    id="filter_ticket_projectid" name="filter_ticket_projectid" disabled>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!--category-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.category')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="filter_ticket_categoryid" id="filter_ticket_categoryid"
                                    class="form-control form-control-sm select2-basic select2-multiple select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}">
                                        {{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <!--date-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.date')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="filter_ticket_created_start"
                                    class="form-control form-control-sm pickadate" autocomplete="off"
                                    placeholder="Start">
                                <input class="mysql-date" type="hidden" name="filter_ticket_created_start"
                                    id="filter_ticket_created_start" value="">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="filter_ticket_created_end"
                                    class="form-control form-control-sm pickadate" autocomplete="off" placeholder="End">
                                <input class="mysql-date" type="hidden" name="filter_ticket_created_end"
                                    id="filter_ticket_created_end" value="">
                            </div>
                        </div>
                    </div>
                </div>


                <!--priority-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.priority')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select class="select2-basic form-control form-control-sm" id="filter_ticket_priority"
                                    name="filter_ticket_priority" multiple="multiple" tabindex="-1" aria-hidden="true">
                                    <option value=""></option>
                                    @foreach(config('settings.ticket_priority') as $key => $value)
                                    <option value="{{ $key }}">{{ runtimeLang($key) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!--status-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.status')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select class="select2-basic form-control form-control-sm" id="filter_ticket_status"
                                    name="filter_ticket_status" multiple="multiple" tabindex="-1" aria-hidden="true">
                                    <option value=""></option>
                                    @foreach($statuses as $status)
                                    <option value="{{ $status->ticketstatus_id }}"
                                        {{ runtimePreselected($ticket->ticket_status ?? '', $status->ticketstatus_id) }}>{{
                                        runtimeLang($status->ticketstatus_title) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <!--custom fields-->
                @include('misc.customfields-filters')

                <!--remember filters-->
                <div class="modal-selector m-t-20">

                    <div class="form-group form-group-checkbox row">
                        <div class="col-12 p-t-5">
                            <input type="checkbox" id="show_archive_tickets" name="show_archive_tickets" class="filled-in chk-col-light-blue" >
                            <label class="p-l-30" for="show_archive_tickets">@lang('lang.show_archive_tickets')</label>
                        </div>
                    </div>


                    
                    <div class="form-group form-group-checkbox row">
                        <div class="col-12 p-t-5">
                            <input type="checkbox" id="filter_remember" name="filter_remember" class="filled-in chk-col-light-blue" 
                            {{ runtimePrechecked(auth()->user()->remember_filters_tickets_status ?? '') }}>
                            <label class="p-l-30" for="filter_remember">@lang('lang.remember_filter') <span
                                    class="align-middle text-info font-16" data-toggle="tooltip"
                                    title="@lang('lang.remember_filter_info')" data-placement="top"><i
                                        class="ti-info-alt"></i></span></label>
                        </div>
                    </div>
                </div>

                <!--indicate this was a filter-->
                <input type="hidden" name="search_type" value="filter">


                <!--buttons-->
                <div class="buttons-block">
                    <button type="button" name="foo1"
                        class="btn btn-rounded-x btn-secondary js-reset-filter-side-panel">{{ cleanLang(__('lang.reset')) }}</button>
                    <input type="hidden" name="action" value="search">
                    <input type="hidden" name="source" value="{{ $page['source_for_filter_panels'] ?? '' }}">
                    <button type="button" class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button"
                        data-url="{{ urlResource('/tickets/search?') }}" data-type="form"
                        data-ajax-type="GET">{{ cleanLang(__('lang.apply_filter')) }}</button>
                </div>


            </div>
            <!--body-->
        </div>
    </form>
</div>
<!--sidebar-->