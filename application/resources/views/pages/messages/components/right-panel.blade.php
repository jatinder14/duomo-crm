<div class="chat-right-aside" id="messages_right_text_wrapper">

    <!--feed container-->
    <div class="chat-rbox" id="feed_container" data-autoload="no" data-url="" data-form-id="feed_container_data"
        data-ajax-type="post" data-loading-target="chat-messages-container-spacer">
        <!--(dynamic) defaults will be auto reset-->
        <div id="feed_container_data">
            <input type="hidden" name="message_source" value="{{ auth()->user()->unique_id }}">
            <input type="hidden" name="message_target" id="feed_container_message_target" value="team">
            <input type="hidden" name="message_load" value="autoload">
        </div>

        <div class="p-t-10 p-b-10" id="chat-messages-container-spacer"></div>
        <!--chat feed-->
        <ul class="chat-list p-20" id="chat-messages-container">
            <!--dynamic content-->
        </ul>
    </div>

    <div class="card-post" id="messaging_submit_container">

        <div class="messaging_text_wrapper">
            <!--text box-->
            <textarea id="messaging_text_editor" class="form-control b-0" name="message_text"></textarea>
        </div>

        <!--submit button-->
        <button type="button" id="messaging_submit_button"
            class="btn btn-danger btn-icon-circle messaging_submit_button ajax-request"
            data-loading-target="chat-messages-container" data-progress-bar="hidden"
            data-url="{{ url('/messages/post/text') }}" data-type="form" data-ajax-type="post"
            data-form-id="messaging_submit_container" data-loading-target="comments-container">
            <i class="sl-icon-paper-plane"></i>
        </button>

        <!--upload files button-->
        <button type="button" id="messaging_file_upload_button"
            class="btn btn-info btn-icon-circle messaging_file_upload_button">
            <i class="sl-icon-paper-clip"></i>
        </button>

        <!--identifier (dynamic)-->
        <div id="message_meta_contaier">
            <input type="hidden" name="message_source" value="{{ auth()->user()->unique_id }}">
            <!--default value will be updated dynaicall-->
            <input type="hidden" class="tracking_message_target" name="message_target" value="team">
            <input type="hidden" class="tracking_timestamp" name="timestamp" id="timestamp_submit_button" value="">

            <!--polling-->
            <span data-type="form" id="messages_polling_trigger" data-form-id="message_meta_contaier"
                data-ajax-type="post" data-progress-bar="hidden" data-url="{{ url('/messages/feed') }}">
            </span>
        </div>
    </div>
</div>