@foreach($tasks as $task)
<!--each row-->
<tr id="task_{{ $task->product_task_id }}">


    <!--product_task_title-->
    <td class="col_product_task_title">
        <a href="javascript:void(0);"
            class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form" data-toggle="modal"
            data-target="#commonModal" data-url="{{ urlResource('/items/tasks/'.$task->product_task_id.'/edit') }}"
            data-loading-target="commonModalBody" data-modal-title="@lang('lang.edit_item')"
            data-action-url="{{ urlResource('/items/tasks/'.$task->product_task_id) }}" data-action-method="PUT"
            data-action-ajax-class="js-ajax-ux-request" data-action-ajax-loading-target="tasks-td-container">
            {{ str_limit($task->product_task_title ?? '---', 60) }}
        </a>
    </td>

    <!--actions-->
    <td class="col_tasks_actions actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--delete-->
            <button type="button" title="@lang('lang.delete')"
                class="btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="@lang('lang.delete_item')" data-confirm-text="@lang('lang.are_you_sure')"
                data-ajax-type="DELETE" data-url="{{ url('/items/tasks/'.$task->product_task_id) }}">
                <i class="sl-icon-trash"></i>
            </button>
            <!--edit-->
            <button type="button" title="@lang('lang.edit')"
                class="btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/items/tasks/'.$task->product_task_id.'/edit') }}"
                data-loading-target="commonModalBody" data-modal-title="@lang('lang.edit_task')"
                data-action-url="{{ urlResource('/items/tasks/'.$task->product_task_id) }}" data-action-method="PUT"
                data-action-ajax-class="js-ajax-ux-request" data-action-ajax-loading-target="tasks-td-container">
                <i class="sl-icon-note"></i>
            </button>
        </span>
    </td>
</tr>
@endforeach
<!--each row-->