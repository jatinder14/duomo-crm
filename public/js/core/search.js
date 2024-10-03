"use strict";

$(document).ready(function () {



    /** --------------------------------------------------------------------------------------------------
     *  topnav search clicked
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '#top-search-form', function () {

        var start_content = $("#search-start-content").html();

        //clear search form and add start screen
        $("#searchModalBody").html(start_content);

        //reset search form
        $("#global-search-field").val('');


    });


    /** --------------------------------------------------------------------------------------------------
     *  search form filled
     * -------------------------------------------------------------------------------------------------*/
    let search_timeout;
    $(document).on('keyup', '#global-search-field', function (e) {

        //default start content
        var start_content = $("#search-start-content").html();

        //current value of search form
        var value = $(this).val();

        //no value, show start screen and return
        if (value == '') {
            $("#searchModalBody").html(start_content);
            return;
        }

        // check if the input field is focused and the Enter key is pressed - execute search now
        if ($(this).is(':focus') && e.keyCode === 13) {
            clearTimeout(search_timeout);
            if (value != '') {
                nxAjaxUxRequest($(this));
            }
        } else {
            // if other keys are pressed execute dynamic search after a delay
            clearTimeout(search_timeout);
            search_timeout = setTimeout(() => {
                if ($('#global-search-field').is(':focus')) {
                    if ($(this).val() != '') {
                        NX.dynamicSearch($(this), e);
                    }
                }
            }, 1500);
        }
    });


    /** --------------------------------------------------------------------------------------------------
     *  search category button clicked
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.search-category-button', function () {

         $(".search-category-button").removeClass('active');
         $(this).addClass('active');

    });

});


/**--------------------------------------------------------------------------------------
 * [SEARCH]
 * @description: show start window
 * -------------------------------------------------------------------------------------*/
function nxSearchStart() {

    //default start content
    var start_content = $("#search-start-content").html();

    //show start content
    if ($('#searchModalBody').find('#search-modal-message-start').length == 0) {
        $("#searchModalBody").html(start_content);
    }
}