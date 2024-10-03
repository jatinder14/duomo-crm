<?php

/**----------------------------------------------------------------------------------------------------------------
 * [GROWCRM - CUSTOM ROUTES]
 * Place your custom routes or overides in this file. This file is not updated with Grow CRM updates
 * ---------------------------------------------------------------------------------------------------------------*/

//ATTENDANCES
Route::group(['prefix' => 'attendances'], function () {
    Route::any("/", "Attendances@index");
    Route::any("/search", "Attendances@index");
    Route::post("/delete", "Attendances@destroy")->middleware(['demoModeCheck']);
});
Route::resource('attendances', 'Attendances');

//HOLIDAYS
Route::group(['prefix' => 'holidays'], function () {
    Route::any("/", "Holidays@index");
    Route::any("/search", "Holidays@index");
    Route::post("/delete", "Holidays@destroy")->middleware(['demoModeCheck']);
});
Route::resource('holidays', 'Holidays');

//LEAVES
Route::group(['prefix' => 'leaves'], function () {
    Route::any("/", "Leaves@index");
    Route::any("/search", "Leaves@index");
    Route::post("/delete", "Leaves@destroy")->middleware(['demoModeCheck']);
    Route::put("/{leave}", "Leaves@update");
    Route::delete("/{leave}", "Leaves@destroy");
});
Route::resource('leaves', 'Leaves');

//IMPORT ATTENDANCES
Route::resource('import/attendances', 'Import\Attendances');

//EXPORT ATTENDANCES
Route::post('export/attendances', 'Export\Attendances@index');

//EXPORT LEAVES
Route::post('export/leaves', 'Export\Leaves@index');

//EXPORT HOLIDAYS
Route::post('export/holidays', 'Export\Holidays@index');
