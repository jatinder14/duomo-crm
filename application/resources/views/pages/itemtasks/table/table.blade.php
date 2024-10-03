        <div class="table-responsive">
            @if (@count($tasks) > 0)
            <table id="task-task-addrow" class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10">
                <thead>
                    <tr>
                        <!--product_task_title-->
                        <th class="col_product_task_title"><a href="javascript:void(0)">@lang('lang.title')</a></th>

                        <!--actions-->
                        <th class="col_tasks_actions w-px-100"><a href="javascript:void(0)">@lang('lang.actions')</a></th>
                    </tr>
                </thead>
                <tbody id="task-td-container">
                    <!--ajax content here-->
                    @include('pages.itemtasks.table.ajax')
                    <!--ajax content here-->
                </tbody>
            </table>
            @endif @if (@count($tasks ?? []) == 0)
            <!--nothing found-->
            @include('notifications.no-results-found')
            <!--nothing found-->
            @endif
        </div>