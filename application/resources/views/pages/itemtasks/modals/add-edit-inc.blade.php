<!--product_task_title-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label required">Title</label>
    <div class="col-sm-12">
        <input type="text" class="form-control form-control-sm" id="product_task_title" name="product_task_title"
            value="{{ $task->product_task_title ?? '' }}">
    </div>
</div>

<!--product_task_description-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">@lang('lang.description')</label>
    <div class="col-sm-12">
        <textarea class="form-control form-control-sm tinymce-textarea" rows="5" name="product_task_description"
            id="product_task_description">{{ $task->product_task_description ?? '' }}</textarea>
    </div>
</div>

<!--assigned users-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">@lang('lang.automation_assign_project')</label>
    <div class="col-sm-12 ">
        <select name="automation_assigned_users" id="automation_assigned_users"
            class="form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
            multiple="multiple" tabindex="-1" aria-hidden="true">
            @foreach(config('system.team_members') as $user)
            <option value="{{ $user->id }}" {{ runtimePreselectedInArray($user->id ?? '', $assigned ?? []) }}>{{
                                $user->full_name }}</option>
            @endforeach
        </select>
    </div>
</div>


<div class="modal-selector m-t-50">
    <h5 class="m-t-10">@lang('lang.dependencies')</h5>

    <!--dependencies - prevent from completing-->
    <div class="form-group row m-t-20">
        <label
            class="col-sm-12 text-left control-label col-form-label required">@lang('lang.dependency_prevents_task_from_completing')</label>
        <div class="col-sm-12">
            <select name="dependencies_cannot_complete" id="dependencies_cannot_complete"
                class="form-control  form-control-sm select2-basic select2-multiple select2-hidden-accessible"
                multiple="multiple" tabindex="-1" aria-hidden="true">
                @foreach($tasks as $product_task)
                <option value="{{ $product_task->product_task_id }}"
                    {{ runtimePreselectedInArray($product_task->product_task_id ?? '', $cannot_complete_dependencies ?? []) }}>
                    {{ $product_task->product_task_title }}
                </option>
                @endforeach
            </select>
        </div>
    </div>


    <!--dependencies - prevent from starting-->
    <div class="form-group row m-t-20">
        <label
            class="col-sm-12 text-left control-label col-form-label required">@lang('lang.dependency_prevents_task_from_starting')</label>
        <div class="col-sm-12">
            <select name="dependencies_cannot_start" id="dependencies_cannot_start"
                class="form-control  form-control-sm select2-basic select2-multiple select2-hidden-accessible"
                multiple="multiple" tabindex="-1" aria-hidden="true">
                @foreach($tasks as $product_task)
                <option value="{{ $product_task->product_task_id }}"
                    {{ runtimePreselectedInArray($product_task->product_task_id ?? '', $cannot_start_dependencies ?? []) }}>
                    {{ $product_task->product_task_title }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
</div>