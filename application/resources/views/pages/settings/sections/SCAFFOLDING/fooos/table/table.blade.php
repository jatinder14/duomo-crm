<div class="table-responsive">
    @if (@count($fooos ?? []) > 0)
    <table id="demo-fooo-addrow" class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10">
        <thead>
            <tr>
                <th class="fooos_col_action"><a href="javascript:void(0)">@lang('lang.fooo')</a></th>
            </tr>
        </thead>
        <tbody id="fooo-td-container">
            <!--ajax content here-->
            @include('pages.settings.sections.fooos.table.ajax')
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

    @if(config('system.settings_type') == 'standalone')
    <!--[standalone] - settings documentation help-->
    <div>
        <a href="https://growcrm.io/documentation" target="_blank" class="btn btn-sm btn-info help-documentation"><i
                class="ti-info-alt"></i>
            {{ cleanLang(__('lang.help_documentation')) }}</a>
    </div>
    @endif

    @endif
    @if (@count($fooos ?? []) == 0)
    <!--nothing found-->
    @include('notifications.no-results-found')
    <!--nothing found-->
    @endif
</div>