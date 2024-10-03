<div class="chat-right-aside messages_file_upload_wrapper hidden" id="messages_file_upload_wrapper">

    <!--upload wrapper-->
    <div class="dropzone dz-clickable" id="fileupload_messages">
        <div class="dz-default dz-message">
            <i class="icon-Upload-toCloud"></i>
            <span>@lang('lang.drag_drop_file')</span>
        </div>
    </div>

    <!--submit button-->
    <div class="messages_file_upload_submit_button">

        <!--[meta] - default values will be updated dynamically-->
        <input type="hidden" name="message_source" value="{{ auth()->user()->unique_id }}">
        <input type="hidden" class="tracking_message_target" name="message_target" value="team">
        <input type="hidden" class="tracking_timestamp" name="timestamp" id="timestamp_submit_button" value="">

        <!--upload button-->
        <button type="button" class="btn btn-info edit-add-modal-button js-ajax-ux-request" data-type="form"
            data-form-id="messages_file_upload_wrapper" data-ajax-type="post" data-loading-target="fileupload_messages"
            data-url="{{ url('messages/fileupload') }}">@lang('lang.send_files')
        </button>
    </div>

    <!--close button-->
    <button type="button" class="close" id="messages_file_upload_close_button">
        <i class="ti-close"></i>
    </button>

</div>