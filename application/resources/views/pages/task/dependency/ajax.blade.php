@foreach($dependecies_all as $dependency)
<!--each dependency-->
<div id="task_dependency_task_{{ $dependency->tasksdependency_id }}"
    class="task-dependency-task {{ runtimeTaskDependencyColors($dependency->tasksdependency_type,  $dependency->tasksdependency_status)}}">
    <span><a href="{{ url('/tasks/v/'.$dependency->task_id) }}" target="_blank">{{ $dependency->task_title }}</a></span>

    <!--delete dependency-->
    @if(config('permission.manage_dependency'))
    <span class="task-dependency-delete-button ajax-request" id="task-dependency-delete-button" data-parent="task_dependency_task_{{ $dependency->tasksdependency_id }}"
        data-url="{{ urlResource('/tasks/'.$task->task_id.'/delete-dependency?dependency_id='.$dependency->tasksdependency_id) }}" data-ajax-type="DELETE"
        data-progress-bar="hidden">
        <i class="sl-icon-close"></i>
    </span>
    @endif

    <!--dependency fullfilled-->
    @if($dependency->tasksdependency_status == 'fulfilled')
    <span class="task-dependency-fulfilled-icon">
        <i class="mdi mdi-checkbox-marked-circle"></i>
    </span>
    @endif

</div>
@endforeach

<!--info panel-->
@if(count($dependecies_all ?? []) > 0)
<div class="p-l-1">
    <span class="bg-danger task-dependency-tooltip" data-toggle="tooltip" data-placement="top"
        title="@lang('lang.dependency_prevents_task_from_completing')">
    </span>
    <span class="bg-warning task-dependency-tooltip" data-toggle="tooltip" data-placement="top"
        title="@lang('lang.dependency_prevents_task_from_starting')">
    </span>
    <span class="bg-success task-dependency-tooltip" data-toggle="tooltip" data-placement="top"
        title="@lang('lang.dependency_has_been_fulfilled')">
    </span>
</div>
@endif