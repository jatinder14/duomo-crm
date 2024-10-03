<div class="table-responsive" id="file-folders-table">
    @if (@count($folders ?? []) > 0)
    <table id="demo-folder-addrow" class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10">
        <thead>
            <tr>
                <th class="col_filefolder_name">@lang('lang.name')</th>
                <th class="col_filefolder_created">@lang('lang.date')</th>
                <th class="col_filefolder_creatorid">@lang('lang.created_by')</th>
                <th class="col_action"><a href="javascript:void(0)">@lang('lang.actions')</a></th>
            </tr>
        </thead>
        <tbody id="folders-td-container">
            <!--ajax content here-->
            @include('pages.settings.sections.files.table.ajax')
            <!--ajax content here-->
        </tbody>
    </table>
    @endif
    @if (@count($folders ?? []) == 0)
    <!--nothing found-->
    @include('notifications.no-results-found')
    <!--nothing found-->
    @endif
</div>