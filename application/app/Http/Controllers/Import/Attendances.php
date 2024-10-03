<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Http\Responses\Import\Attendances\CreateResponse;
use App\Http\Responses\Import\Common\StoreResponse;
use App\Imports\AttendancesImport;
use App\Repositories\ImportExportRepository;
use App\Repositories\SystemRepository;
use Illuminate\Validation\Rule;
use Validator;

class Attendances extends Controller {

    /**
     * The event repository instance.
     */
    protected $eventrepo;

    public function __construct() {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //Permissions on methods
        $this->middleware('importAttendancesMiddlewareCreate')->only([
            'create',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create(SystemRepository $systemrepo) {

        //check server requirements for excel module
        $server_check = $systemrepo->serverRequirementsExcel();

        //reponse payload
        $payload = [
            'type' => 'attendances',
            'requirements' => $server_check['requirements'],
            'server_status' => $server_check['status'],
            'page' => $this->pageSettings('create'),
        ];

        //show the form
        return new CreateResponse($payload);
    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\Response
     */
    public function store(ImportExportRepository $importexportrepo) {

        //unique transaction ref
        $import_ref = str_unique();

        //uploaded file path
        $file_path = BASE_DIR . "/storage/temp/" . request('importing-file-uniqueid') . "/" . request('importing-file-name');

        //initial validation
        if (!$importexportrepo->validateImport()) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //unique import ID (transaction tracking)
        request()->merge([
            'import_ref' => $import_ref,
        ]);

        //import attendances
        $import = new AttendancesImport();
        $import->import($file_path);

        //log erors
        $importexportrepo->logImportError($import->failures(), $import_ref);

        //additional processing
        if ($import->getRowCount() > 0) {
            $this->processCollection();
        }

        //reponse payload
        $payload = [
            'type' => 'attendances',
            'error_count' => count($import->failures()),
            'error_ref' => $import_ref,
            'count_passed' => $import->getRowCount(),
        ];

        //process reponse
        return new StoreResponse($payload);

    }

    /**
     * additional processing for the imported collection
     */
    private function processCollection() {

        //get the attendances for this import
        $attendances = \App\Models\Attendance::Where('attendance_importid', request('import_ref'))->get();

        //additional processing
        foreach ($attendances as $attendance) {

            /** ----------------------------------------------
             * send email [attendance]
             * ----------------------------------------------*/
            

        }
    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        //common settings
        $page = [

        ];

        //return
        return $page;
    }
}