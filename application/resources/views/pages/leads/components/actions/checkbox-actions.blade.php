<div class="col-12 align-self-center hidden checkbox-actions box-shadow-minimum" id="leads-checkbox-actions-container">
    <!--button-->
    @if(config('visibility.action_buttons_edit'))
    <div class="x-buttons">
        @if(config('visibility.action_buttons_delete'))
        <button type="button" class="btn btn-sm btn-default waves-effect waves-dark confirm-action-danger"
            data-type="form" data-ajax-type="POST" data-form-id="leads-list-table"
            data-url="{{ url('/leads/delete?type=bulk') }}"
            data-confirm-title="{{ cleanLang(__('lang.delete_selected_items')) }}"
            data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" id="checkbox-actions-delete-leads">
            <i class="sl-icon-trash"></i> {{ cleanLang(__('lang.delete')) }}
        </button>
        @endif

        <!--change category-->
        <button type="button"
            class="btn btn-sm btn-default waves-effect waves-dark actions-modal-button js-ajax-ux-request"
            data-toggle="modal" data-target="#actionsModal"
            data-modal-title="{{ cleanLang(__('lang.change_category')) }}"
            data-url="{{ urlResource('/leads/change-category') }}"
            data-action-url="{{ urlResource('/leads/change-category?type=bulk') }}" data-action-method="POST"
            data-action-type="form" data-action-form-id="main-body" data-loading-target="actionsModalBody"
            data-skip-checkboxes-reset="TRUE" id="checkbox-actions-change-category-leads">
            <i class="sl-icon-share-alt"></i> {{ cleanLang(__('lang.change_category')) }}
        </button>

        <!--change status-->
        <button type="button"
            class="btn btn-sm btn-default waves-effect waves-dark actions-modal-button js-ajax-ux-request"
            data-toggle="modal" data-target="#actionsModal" data-modal-title="@lang('lang.change_status')"
            data-url="{{ urlResource('/leads/bulk-change-status') }}"
            data-action-url="{{ urlResource('/leads/bulk-change-status') }}" data-action-method="POST"
            data-action-type="form" data-action-form-id="main-body" data-loading-target="actionsModalBody"
            data-skip-checkboxes-reset="TRUE" id="checkbox-actions-change-category-leads">
            <i class="ti-bookmark"></i> @lang('lang.change_status')
        </button>

        @if(auth()->user()->role->role_assign_leads == 'yes')
        <!--assign users-->
        <button type="button"
            class="btn btn-sm btn-default waves-effect waves-dark actions-modal-button js-ajax-ux-request"
            data-toggle="modal" data-target="#actionsModal" data-modal-title="@lang('lang.assign_users')"
            data-url="{{ urlResource('/leads/change-assigned') }}"
            data-action-url="{{ urlResource('/leads/change-assigned?type=bulk') }}" data-action-method="POST"
            data-action-type="form" data-action-form-id="main-body" data-loading-target="actionsModalBody"
            data-skip-checkboxes-reset="TRUE" id="checkbox-actions-change-category-leads">
            <i class="sl-icon-people"></i> @lang('lang.assign_users')
        </button>
        @endif
    </div>
    @else
    <div class="x-notice">
        {{ cleanLang(__('lang.no_actions_available')) }}
    </div>
    @endif
</div>