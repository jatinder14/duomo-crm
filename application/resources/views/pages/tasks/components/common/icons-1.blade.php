<!--[icons] task dependency - lock icon (cannot be started)-->
@if($task->count_dependency_cannot_start > 0)
<span class="x-icon text-warning p-l-3  display-inline-block font-14 vm " data-toggle="tooltip"  data-placement="top"
    title="@lang('lang.task_dependency_info_cannot_be_started')"><i class="mdi mdi-lock"></i></span>
@endif

<!--[icons] task dependency - lock icon (cannot be completed)-->
@if($task->count_dependency_cannot_complete > 0)
<span class="x-icon text-danger p-l-3  display-inline-block font-14 vm " data-toggle="tooltip"  data-placement="top"
    title="@lang('lang.task_dependency_info_cannot_be_completed')"><i class="mdi mdi-lock"></i></span>
@endif

<!--[icons] task dependency - fullfilled-->
@if($task->count_dependency_cannot_start_fullfilled > 0 || $task->count_dependency_cannot_complete_fulfilled > 0)
<span class="x-icon text-success p-l-3  display-inline-block font-14 vm " data-toggle="tooltip"  data-placement="top"
    title="@lang('lang.dependecies_have_been_fulfilled')"><i class="mdi mdi-lock"></i></span>
@endif



<!--[icons] recurring-->
@if(auth()->user()->is_team && $task->task_recurring == 'yes')
<span class="sl-icon-refresh text-danger display-inline-block p-l-3 vm " data-toggle="tooltip"  data-placement="top" title="@lang('lang.recurring_task')"></span>
@endif