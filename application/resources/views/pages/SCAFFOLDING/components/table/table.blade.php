<div class="card count-{{ @count($fooos ?? []) }}" id="fooo-table-wrapper">
    <div class="card-body">
        <div class="table-responsive">
            @if (@count($fooos ?? []) > 0)
            <table id="fooo-fooo-addrow" class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10">
                <thead>
                    <tr>
                        @if(config('visibility.fooos_col_checkboxes'))
                        <th class="list-checkbox-wrapper">
                            <!--list checkbox-->
                            <span class="list-checkboxes display-inline-block w-px-20">
                                <input type="checkbox" id="listcheckbox-fooos" name="listcheckbox-fooos"
                                    class="listcheckbox-all filled-in chk-col-light-blue"
                                    data-actions-container-class="fooos-checkbox-actions-container"
                                    data-children-checkbox-class="listcheckbox-fooos">
                                <label for="listcheckbox-fooos"></label>
                            </span>
                        </th>
                        @endif

                        <!--actions-->
                        <th class="col_fooos_actions"><a href="javascript:void(0)">@lang('lang.actions')</a></th>
                    </tr>
                </thead>
                <tbody id="fooo-td-container">
                    <!--ajax content here-->
                    @include('pages.fooos.components.table.ajax')
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
            @endif @if (@count($fooos ?? []) == 0)
            <!--nothing found-->
            @include('notifications.no-results-found')
            <!--nothing found-->
            @endif
        </div>
    </div>
</div>