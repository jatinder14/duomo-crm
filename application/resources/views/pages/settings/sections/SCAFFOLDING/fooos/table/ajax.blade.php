@foreach($fooos as $fooo)
<!--each row-->
<tr id="fooo_{{ $fooo->fooo_id }}">

    <td class="fooos_col_action actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ url('/settings/fooos/'.$fooo->leadfooos_id.'/edit') }}" data-loading-target="commonModalBody"
                data-modal-title="{{ cleanLang(__('lang.edit_lead_source')) }}"
                data-action-url="{{ url('/settings/fooos/'.$fooo->leadfooos_id) }}" data-action-method="PUT"
                data-action-ajax-class="js-ajax-ux-request" data-action-ajax-loading-target="fooos-td-container">
                <i class="sl-icon-note"></i>
            </button>
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="@lang('lang.delete_item')" data-confirm-text="@lang('lang.are_you_sure')"
                data-ajax-type="DELETE" data-url="{{ url('/') }}/fooos/{{ $fooo->leadfooos_id }}">
                <i class="sl-icon-trash"></i>
            </button>
        </span>
        <!--action button-->
    </td>
</tr>
@endforeach
<!--each row-->