@foreach($canned_responses as $canned)
<!--each row-->
<tr id="canned_{{ $canned->canned_id }}">

    <!--canned_title-->
    <td class="col_canned_title">
        {{ str_limit($canned->canned_title ?? '---', 200) }}
    </td>

    <!--canned_created-->
    <td class="col_canned_created">
        {{ runtimeDate($canned->canned_created) }}
    </td>


    <!--canned_visibility-->
    <td class="col_canned_visibility">
        @if($canned->canned_visibility == 'public')
        <span class="label label-outline-info">@lang('lang.public')</span>
        @else
        <span class="label label-outline-warning">@lang('lang.private')</span>
        @endif
    </td>

    <!--actions-->
    <td class="col_canned_actions actions_column">
        <!--action button-->

        <span class="list-table-action dropdown font-size-inherit">
            <!--delete-->
            <button type="button" title="@lang('lang.delete')"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="@lang('lang.delete_item')" data-confirm-text="@lang('lang.are_you_sure')"
                data-ajax-type="DELETE" data-url="{{ url('/canned/'.$canned->canned_id) }}">
                <i class="sl-icon-trash"></i>
            </button>
            <!--edit-->
            <button type="button" title="@lang('lang.edit')"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/canned/'.$canned->canned_id.'/edit') }}"
                data-loading-target="commonModalBody" data-modal-title="@lang('lang.edit_item')"
                data-modal-size="modal-xl" data-action-url="{{ urlResource('/canned/'.$canned->canned_id) }}"
                data-action-method="PUT" data-action-ajax-class="js-ajax-ux-request"
                data-action-ajax-loading-target="canned-td-container">
                <i class="sl-icon-note"></i>
            </button>
        </span>

        <!--action button-->
    </td>
</tr>
@endforeach
<!--each row-->