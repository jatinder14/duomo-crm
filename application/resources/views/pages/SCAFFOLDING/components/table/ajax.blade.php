@foreach($fooos as $fooo)
<!--each row-->
<tr id="fooo_{{ $fooo->fooo_id }}">

    @if(config('visibility.fooos_col_checkboxes'))
    <td class="col_fooos_checkbox checkfooo" id="fooos_col_checkbox_{{ $fooo->fooo_id }}">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-fooos-{{ $fooo->fooo_id }}" name="ids[{{ $fooo->fooo_id }}]"
                class="listcheckbox listcheckbox-fooos filled-in chk-col-light-blue fooos-checkbox"
                data-actions-container-class="fooos-checkbox-actions-container" data-fooo-id="{{ $fooo->fooo_id }}">
            <label for="listcheckbox-fooos-{{ $fooo->fooo_id }}"></label>
        </span>
    </td>
    @endif
    
    <!--actions-->
    <td class="col_fooos_actions actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--delete-->
            <button type="button" title="@lang('lang.delete')"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="@lang('lang.delete_item')" data-confirm-text="@lang('lang.are_you_sure')"
                data-ajax-type="DELETE" data-url="{{ url('/fooos/'.$fooo->fooo_id) }}">
                <i class="sl-icon-trash"></i>
            </button>
            <!--edit-->
            <button type="button" title="@lang('lang.edit')"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/fooos/'.$fooo->fooo_id.'/edit') }}" data-loading-target="commonModalBody"
                data-modal-title="@lang('lang.edit_item')" data-action-url="{{ urlResource('/fooos/'.$fooo->fooo_id) }}"
                data-action-method="PUT" data-action-ajax-class="js-ajax-ux-request"
                data-action-ajax-loading-target="fooos-td-container">
                <i class="sl-icon-note"></i>
            </button>
            <!--view-->
            <a href="{{ url('/fooo/'.$fooo->fooo_id) }}" title="@lang('lang.view')"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm">
                <i class="ti-new-window"></i>
            </a>
        </span>
        <!--action button-->
        <!--more button (hidden)-->
        <span class="list-table-action dropdown hidden font-size-inherit">
            <button type="button" id="listTableAction" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                class="btn btn-outline-default-light btn-circle btn-sm">
                <i class="ti-more"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="listTableAction">
                <a class="dropdown-item" href="javascript:void(0)">
                    <i class="ti-new-window"></i> @lang('lang.view_details')</a>
            </div>
        </span>
        <!--more button-->
    </td>
</tr>
@endforeach
<!--each row-->