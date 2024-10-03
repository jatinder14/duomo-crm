@if(isset($page['page']) && $page['page'] == 'files')
<div class="col-12 align-self-center hidden checkbox-actions " id="files-checkbox-actions-container">
    <div class="x-buttons">

        <!--how many actions do we have-->
        @php $actions = 0; @endphp

        <!--download button-->
        @if(config('visibility.action_buttons_download'))
        <button type="button" class="btn btn-sm btn-default waves-effect waves-dark ajax-request"
            id="files-bulk-download-button" data-type="form" data-ajax-type="POST" data-form-id="files-table"
            data-button-loading-annimation="yes" data-skip-checkboxes-reset="true"
            data-url="{{ urlResource('/files/bulkdownload') }}" id="checkbox-actions-delete-files">
            <i class="ti-download"></i> {{ cleanLang(__('lang.download')) }}
        </button>
        @php $actions ++; @endphp
        @endif

        <!--move button-->
        @if(config('visibility.action_buttons_move'))
        <button type="button"
            class="btn btn-sm btn-default edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
            id="checkbox-actions-move-files" data-toggle="modal" data-target="#commonModal"
            data-url="{{ urlResource('files/move') }}" data-ajax-type="POST" data-loading-target="commonModalBody"
            data-modal-title="@lang('lang.move_files')" data-action-type="form" data-action-form-id="files-table"
            data-action-url="{{ urlResource('files/move') }}" data-action-method="PUT" data-modal-size="modal-sm"
            data-button-loading-annimation="yes" data-action-ajax-loading-target="commonModalBody"><i
                class="ti-share-alt"></i> @lang('lang.move')
        </button>
        @php $actions ++; @endphp
        @endif

        <!--copy button-->
        @if(auth()->user()->is_team)
        <button type="button"
            class="btn btn-sm btn-default edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
            id="checkbox-actions-move-files" data-toggle="modal" data-target="#commonModal"
            data-url="{{ urlResource('files/copy') }}" data-ajax-type="GET" data-loading-target="commonModalBody"
            data-modal-title="@lang('lang.copy_files')" data-action-type="form" data-action-form-id="main-body"
            data-action-url="{{ urlResource('files/copy') }}" data-action-method="POST" data-modal-size="modal-sm"
            data-action-ajax-class="ajax-request" data-button-loading-annimation="yes"
            data-action-ajax-loading-target="commonModalBody"><i class="mdi mdi-content-copy"></i> @lang('lang.copy')
        </button>
        @php $actions ++; @endphp
        @endif

        <!--delete button-->
        @if(config('visibility.action_buttons_bulk_delete'))
        <button type="button" class="btn btn-sm btn-default waves-effect waves-dark confirm-action-danger"
            data-type="form" data-ajax-type="POST" data-action-form-id="files-table" data-form-id="files-table"
            data-url="{{ url('/files/delete') }}" data-confirm-title="{{ cleanLang(__('lang.delete_selected_items')) }}"
            data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" id="checkbox-actions-delete-files">
            <i class="sl-icon-trash"></i> {{ cleanLang(__('lang.delete')) }}
        </button>
        @php $actions ++; @endphp
        @endif

        @if($actions == 0)
        <div class="x-notice">
            {{ cleanLang(__('lang.no_actions_available')) }}
        </div>
        @endif
    </div>

</div>
@endif