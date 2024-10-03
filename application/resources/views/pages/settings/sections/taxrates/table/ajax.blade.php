@foreach($taxrates as $taxrate)
<!--each row-->
<tr id="taxrate_{{ $taxrate->taxrate_id }}">
    <td class="taxrates_col_name">
        {{ $taxrate->taxrate_name }}

        @if($taxrate->taxrate_type == 'system')
        <span class="sl-icon-star text-warning p-l-5" data-toggle="tooltip"
            title="{{ cleanLang(__('lang.system_default')) }}"></span>
        @endif
    </td>
    <td class="taxrates_col_value">
        {{ $taxrate->taxrate_value }}%
    </td>
    <td class="taxrates_col_created_by">
        <img src="{{ getUsersAvatar($taxrate->avatar_directory, $taxrate->avatar_filename, $taxrate->taxrate_creatorid) }}"
            alt="user" class="img-circle avatar-xsmall">
        {{ checkUsersName($taxrate->first_name, $taxrate->taxrate_creatorid)  }}
    </td>
    <td class="taxrates_col_value">
        @if($taxrate->taxrate_status == 'enabled')
        <span class="label label-outline-info">@lang('lang.enabled')</span>
        @else
        <span class="label label-outline-default">@lang('lang.disabled')</span>
        @endif
    </td>
    <td class="taxrates_col_action actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ url('/settings/taxrates/'.$taxrate->taxrate_id.'/edit') }}"
                data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.tax_rates')) }}"
                data-action-url="{{ url('/settings/taxrates/'.$taxrate->taxrate_id) }}" data-action-method="PUT"
                data-action-ajax-class="js-ajax-ux-request" data-action-ajax-loading-target="taxrates-td-container">
                <i class="sl-icon-note"></i>
            </button>
            @if($taxrate->taxrate_type == 'user')
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ url('/') }}/settings/taxrates/{{ $taxrate->taxrate_id }}">
                <i class="sl-icon-trash"></i>
            </button>
            @else
            <!--optionally show disabled button?-->
            <span class="btn btn-outline-default btn-circle btn-sm disabled {{ runtimePlaceholdeActionsButtons() }}"
                data-toggle="tooltip" title="{{ cleanLang(__('lang.actions_not_available')) }}"><i
                    class="sl-icon-trash"></i></span>
            @endif
        </span>
        <!--action button-->
    </td>
</tr>
@endforeach
<!--each row-->