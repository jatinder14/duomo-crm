"use strict";

$(document).ready(function () {

    /** --------------------------------------------------------------------------------------------------
     *  [export] - tables to excel files
     *  @source https://github.com/hhurz/tableExport.jquery.plugin
     *  @version 1.27.0 (March 2023)
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.js-export-table-excel', function (e) {

        var table_id = $(this).attr('data-table');

        var table = $("#" + table_id);

        // validate the table exists
        if (table.length == 0) {

            //error message
            NX.notification({
                type: 'error',
                message: NXLANG.generic_error
            });

            return;
        }


        //loading
        $(this).addClass('button-loading-annimation');

        // Export the table to Excel using tableExport.jquery.plugin
        table.tableExport({
            type: 'xlsx',
            escape: 'false',
            fileName: 'Export',
            onCellData: function (cell, rowIndex, colIndex, data) {

                // remove the currency symbol from all cell with the class 'data-type-money'
                if (NX.settings_system_currency_symbol != '') {
                    if ($(cell).hasClass('data-type-money')) {
                        return data.replace(NX.settings_system_currency_symbol, '');
                    }
                }
                return data;
            }
        });

        //loading
        $(this).removeClass('button-loading-annimation');
    });


    /** --------------------------------------------------------------------------------------------------
     *  [export] - tables to csv files
     *  @source https://github.com/hhurz/tableExport.jquery.plugin
     *  @version 1.27.0 (March 2023)
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.js-export-table-csv', function (e) {

        var table_id = $(this).attr('data-table');

        var table = $("#" + table_id);

        // validate the table exists
        if (table.length == 0) {

            //error message
            NX.notification({
                type: 'error',
                message: NXLANG.generic_error
            });

            return;
        }


        //loading
        $(this).addClass('button-loading-annimation');

        // Export the table to Excel using tableExport.jquery.plugin
        table.tableExport({
            type: 'csv',
            escape: 'false',
            fileName: 'Export',
            onCellData: function (cell, rowIndex, colIndex, data) {

                // remove the currency symbol from all cell with the class 'data-type-money'
                if (NX.settings_system_currency_symbol != '') {
                    if ($(cell).hasClass('data-type-money')) {
                        return data.replace(NX.settings_system_currency_symbol, '');
                    }
                }
                return data;
            }
        });

        //loading
        $(this).removeClass('button-loading-annimation');
    });


    /** --------------------------------------------------------------------------------------------------
     *  [print] - print a table
     * @source https://github.com/jasonday/printThis
     * @version v1.15.0
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.js-print-table', function (e) {

        var table_id = $(this).attr('data-table');

        var table = $("#" + table_id);

        // validate the table exists
        if (table.length == 0) {

            //error message
            NX.notification({
                type: 'error',
                message: NXLANG.generic_error
            });

            return;
        }

        //print the table
        table.printThis({
            beforePrint: function () {
                table.addClass("printing-table-css");
                $("body").addClass("printing-css");
            },
            afterPrint: function () {
                table.removeClass("printing-table-css");
                $("body").removeClass("printing-css");
            },
            debug:false,
        });
    });
});