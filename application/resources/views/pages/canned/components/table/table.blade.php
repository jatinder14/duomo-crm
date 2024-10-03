<div class="card count-{{ @count($canned_responses ?? []) }}" id="canned-table-wrapper">
    <div class="card-body">
        <div class="table-responsive">
            @if (@count($canned_responses ?? []) > 0)
            <table id="canned-addrow" class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10">
                <thead>
                    <tr>
                        <!--canned_title-->
                        <th class="col_canned_title"><a class="js-ajax-ux-request js-list-sorting"
                                id="sort_canned_title" href="javascript:void(0)"
                                data-url="{{ urlResource('/canned?action=sort&orderby=canned_title&sortorder=asc') }}">@lang('lang.title')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                        <!--canned_created-->
                        <th class="col_canned_created w-px-150"><a class="js-ajax-ux-request js-list-sorting"
                                id="sort_canned_created" href="javascript:void(0)"
                                data-url="{{ urlResource('/canned?action=sort&orderby=canned_created&sortorder=asc') }}">@lang('lang.date_created')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                        <!--canned_visibility-->
                        <th class="col_canned_visibility w-px-150"><a class="js-ajax-ux-request js-list-sorting"
                                id="sort_canned_visibility" href="javascript:void(0)"
                                data-url="{{ urlResource('/canned?action=sort&orderby=canned_visibility&sortorder=asc') }}">@lang('lang.visibility')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                        <!--actions-->
                        <th class="col_canned_actions w-px-150"><a href="javascript:void(0)">@lang('lang.actions')</a>
                        </th>
                    </tr>
                </thead>
                <tbody id="canned-td-container">
                    <!--ajax content here-->
                    @include('pages.canned.components.table.ajax')
                    <!--ajax content here-->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="20">
                            <!--load more button-->
                            @include('misc.load-more-button')
                            <!--load more button-->
                        </td>
                    </tr>
                </tfoot>
            </table>
            @endif @if (@count($canned_responses ?? []) == 0)
            <!--nothing found-->
            @include('notifications.no-results-found')
            <!--nothing found-->
            @endif
        </div>
    </div>
</div>