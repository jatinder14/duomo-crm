<!--add dependency button-->
@if(config('permission.manage_dependency'))
<div class="x-element x-action x-element-info m-b-15" id="card-dependency-create-button"><i
        class="sl-icon-shuffle m-t--4 p-r-6"></i>
    <span class="x-highlight"> @lang('lang.add_a_dependency')</span>
</div>
@endif

<!--add adependency-->
<div class="task-dependency-container hidden" id="task-dependency-create-container">
    <!--blocking_task-->
    <div class="form-group row">
        <label class="col-sm-12 text-left control-label col-form-label">@lang('lang.blocking_task') <span
                class="align-middle text-info font-16" data-toggle="tooltip" title="@lang('lang.task_blocking_info_2')"
                data-placement="top"><i class="ti-info-alt"></i></span></label>
        <div class="col-sm-12">
            <select class="select2-basic form-control form-control-sm" id="tasksdependency_blockerid"
                name="tasksdependency_blockerid">
                @foreach($project_tasks as $project_task)
                @if($project_task->task_id != $task->task_id)
                <option value="{{ $project_task->task_id }}">{{ $project_task->task_title }}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <!--blocking_task-->
    <div class="form-group row">
        <label class="col-sm-12 text-left control-label col-form-label">@lang('lang.dependency_type') <span
                class="align-middle text-info font-16" data-toggle="tooltip" title="@lang('lang.task_blocking_info_1')"
                data-placement="top"><i class="ti-info-alt"></i></span></label>
        <div class="col-sm-12">
            <select class="select2-basic form-control form-control-sm" id="tasksdependency_type"
                name="tasksdependency_type">
                <option value="cannot_complete">@lang('lang.this_task') - @lang('lang.dependency_type_cannot_complete')</option>
                <option value="cannot_start">@lang('lang.this_task') - @lang('lang.dependency_type_cannot_start')</option>
            </select>
        </div>
    </div>

    <div class="buttons-block  p-b-0 p-t-0 text-right">
        <!--close button (task/lead cards only-->
        <button type="button" class="btn btn-rounded-x btn-default btn-xs ajax-request"
            id="card-task-dependency-close-button">@lang('lang.close')</button>
        <!--delete button-->
        <!--save button-->
        <button type="button" class="btn btn-rounded-x btn-info btn-xs js-ajax-ux-request" data-url="{{ urlResource('tasks/'.$task->task_id.'/add-dependency') }} "
            data-type="form" data-form-id="task-dependency-create-container" data-loading-class="loading-before"
            data-loading-target="task-dependency-create-container" data-ajax-type="post">@lang('lang.save')</button>
    </div>
</div>



<!--task dependencies list-->
<div class="task-dependency-list-container" id="task-dependency-list-container">
    @include('pages.task.dependency.ajax')
</div>