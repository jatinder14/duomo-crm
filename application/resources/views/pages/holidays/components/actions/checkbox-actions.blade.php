<div class="col-12 align-self-center hidden checkbox-actions box-shadow-minimum" id="holidays-checkbox-actions-container">
    <!--button-->
    @if (config('visibility.action_buttons_delete'))
        <div class="x-buttons">
            <button type="button" class="btn btn-sm btn-default waves-effect waves-dark confirm-action-danger"
                data-type="form" data-ajax-type="POST" data-form-id="holidays-list-table"
                data-url="{{ url('/holidays/delete?type=bulk') }}"
                data-confirm-title="{{ cleanLang(__('lang.delete_selected_items')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" id="checkbox-actions-delete-holidays">
                <i class="sl-icon-trash"></i> {{ cleanLang(__('lang.delete')) }}
            </button>
        </div>
    @else
        <div class="x-notice">
            {{ cleanLang(__('lang.no_actions_available')) }}
        </div>
    @endif
</div>
