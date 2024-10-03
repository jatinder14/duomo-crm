"use strict";

$(document).ready(function () {




    /**--------------------------------------------------------------------------------------
     * load intial feed (team)
     * -------------------------------------------------------------------------------------*/
    nxAjaxUxRequest($("#messages_team_link"));


    /**--------------------------------------------------------------------------------------
     * text editor
     * -------------------------------------------------------------------------------------*/
    nxTinyMCEPlain(200, '#messaging_text_editor', 'transparent_body');


    /** --------------------------------------------------------------------------------------------------
     *  menu (user) link has been clicked
     * -------------------------------------------------------------------------------------------------*/
    $(".messages-menu-link").on('click', function () {

        //reset the feed
        $("#chat-messages-container").html('');

        //make link active
        $(".messages-menu-link").removeClass('active');
        $(this).addClass('active');

        //update button and tracking target
        var message_target = $(this).attr('data-message-target');
        $(".tracking_message_target").val(message_target);

        //remove counter
        var counter_id = $(this).attr('data-counter-id');
        $("#" + counter_id).html('');
        $("#" + counter_id).hide();

        //add data to autload element
        $("#feed_container_message_target").val(message_target);

    });

    //perfect scroll - left menu
    if ($("#messages-left-menu").length) {
        const messagees_left_menu_scroll = new PerfectScrollbar('#messages-left-menu', {
            wheelSpeed: 0,
            wheelPropagation: false,
            minScrollbarLength: null,
            swipeEasing: true
        });
    }


    /** --------------------------------------------------------------------------------------------------
     *  menu (user) post message button has been clicked
     * -------------------------------------------------------------------------------------------------*/
    $("#messaging_submit_button").on('click', function () {

        //reset the editot
        tinymce.activeEditor.setContent('');

    });



    /**--------------------------------------------------------------------------------------
     * poll the server for new messages every 7 seconds
     * -------------------------------------------------------------------------------------*/
    if ($("#messages_polling_trigger").length) {
        function nxMessagesPolling() {
            //only do this if we have a set timestamp
            if ($("#timestamp_submit_button").val() > 0) {
                nxAjaxUxRequest($("#messages_polling_trigger"));
            }
            //loop again
            setTimeout(nxMessagesPolling, 7000);
        };
        nxMessagesPolling();
    }


    /**--------------------------------------------------------------------------------------
     * hover on message
     * -------------------------------------------------------------------------------------*/
    $(document).on({
        mouseenter: function () {
            $(this).find('.messages_delete_button').show();
        },
        mouseleave: function () {
            $(this).find('.messages_delete_button').hide();
        }
    }, ".message-content-box");


    /** --------------------------------------------------------------------------------------------------
     *  delete a message
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.messages_delete_button', function () {

        var message_id = $(this).attr('data-message-id');

        //remove the missage
        $("#" + message_id).remove();

        //send request
        nxAjaxUxRequest($(this));
    });



    /** --------------------------------------------------------------------------------------------------
     *  upload file
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.messaging_file_upload_button', function () {

        //reset fle upload
        try {
            fileupload_messages[0].dropzone.removeAllFiles();
        } catch (err) {

        }
        $("#messages_right_text_wrapper").hide();
        $("#messages_file_upload_wrapper").show();

    });


    /** --------------------------------------------------------------------------------------------------
     *  upload file
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '#messages_file_upload_close_button', function () {

        $("#messages_file_upload_wrapper").hide();
        $("#messages_right_text_wrapper").show();

    });


    /** --------------------------------------------------------------------------------------------------
     *  upload file
     * -------------------------------------------------------------------------------------------------*/
    var fileupload_messages = $("#fileupload_messages").dropzone({
        url: "/fileupload?thumb_size=lg",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function () {
            this.on("error", function (file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function (file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });


    $("#feed_container").scroll(function () {

        //target height (top of chat box)
        var target_height = $(this).prop('scrollHeight') - $(this).height();

        //threshold target
        var threshold_target_height = target_height - 20; //just a little before the top

        //current scroll position
        var position = $(this).scrollTop();

        //we have hit the top
        if(position == target_height){

            //make new request
            if($(this).attr('data-autoload') == 'yes'){

                //prevent multiple triggering
                $(this).attr('data-autoload', 'no');

                console.log('chat: making new request');

                //trigger request for more chats
                nxAjaxUxRequest($(this));

            }
        }
    });
});