"use strict";

$(document).ready(function () {

    /** --------------------------------------------------------------------------------------------------
     *  hover on cover image and show remove button
     * -------------------------------------------------------------------------------------------------*/
    $(document).on({
        mouseenter: function () {
            $('.cover-image-remove-button').show();
        },
        mouseleave: function () {
            $('.cover-image-remove-button').hide();
        }
    }, ".card-cover-image-wrapper");

    /** --------------------------------------------------------------------------------------------------
     *  add cover image button
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.js-add-cover-image', function () {

        var image_url = $(this).attr('data-image-url');
        var this_remove_button = $(this).attr('data-remove-cover-button');
        var this_add_button = $(this).attr('data-add-cover-button');
        var remove_cover_url = $(this).attr('data-image-url');
        var kanban_card_id = $(this).attr('data-id');


        //set background image
        $('#card-cover-image-container').css('background-image', 'url("' + image_url + '")');

        //set the cover on the kanban also
        $('#kanban-card-cover-image-' + kanban_card_id).css('background-image', 'url("' + image_url + '")');
        $('#kanban-card-cover-image-' + kanban_card_id).show();

        //update remove image button url
        $(".remove-cover-image-button").attr('data-url', remove_cover_url);


        //elements
        $("#card-cover-image-wrapper").show();


        //buttons
        $(".cover_image_buttons_remove").hide();
        $(".cover_image_buttons_add").show();
        $("#" + this_remove_button).show();
        $("#" + this_add_button).hide();

        //reposition modal close button
        $("#card-modal-close").addClass('card-has-cover-image');

        //request to backend
        nxAjaxUxRequest($(this));
    });


    /** --------------------------------------------------------------------------------------------------
     *  remove cover image button
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.js-remove-cover-image', function (e) {

        e.stopPropagation();

        var kanban_card_id = $(this).attr('data-id');

        //elements
        $("#card-cover-image-wrapper").hide();

        //reposition modal close button
        $("#card-modal-close").removeClass('card-has-cover-image');

        //hide on kanban card
        $('#kanban-card-cover-image-' + kanban_card_id).hide();


        //buttons
        $(".cover_image_buttons_remove").hide();
        $(".cover_image_buttons_add").show();

        //request to backend
        nxAjaxUxRequest($(this));
    });


});


/**--------------------------------------------------------------------------------------
 * [CARD COVERS] 
 * @description: card cover image has been removed in backend
 * -------------------------------------------------------------------------------------*/
function NXCardRemoveCover() {

    //elements
    $("#card-cover-image-wrapper").hide();

    //reposition modal close button
    $("#card-modal-close").removeClass('card-has-cover-image');

    //buttons
    $(".cover_image_buttons_remove").hide();
    $(".cover_image_buttons_add").show();
}

/**--------------------------------------------------------------------------------------
 * [RESET TAGS] 
 * @description: reset tags when file attachment is completed
 * -------------------------------------------------------------------------------------*/
function NXResetAttachmentsTags() {

    var $dropdown = $("#tags");
    $dropdown.val('');
    $dropdown.trigger("change");

}