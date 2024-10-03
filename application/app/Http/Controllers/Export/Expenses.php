<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for exporting
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Export;

use App\Exports\ExpensesExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class Expenses extends Controller {

    /**
     * The expense repository instance.
     */
    protected $expenserepo;

    public function __construct() {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('expensesMiddlewareIndex')->only([
            'index',
        ]);

    }

    /**
     * Export clients to XLSX file
     * @return download
     */
    public function index(ExpensesExport $export) {

        //temp directory
        $directory = Str::random(30);

        //file name
        $filename = __('lang.expenses') . '.xlsx';

        //create directory
        Storage::makeDirectory("/temp/$directory");

        //export the file
        try {

            //create directory
            Excel::store($export, "temp/$directory/$filename", 'public');

            //return ajax redirect
            $jsondata['delayed_redirect_url'] = url("/storage/temp/$directory/$filename");

            //ajax response
            return response()->json($jsondata);

        } catch (Exception $e) {

            //error
            $error_message = $e->getMessage();
            Log::error("exporting expenses failed", ['process' => '[permissions]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'error_message' => $error_message]);
            //default error
            abort(409, $error_message);

        }
    }

}