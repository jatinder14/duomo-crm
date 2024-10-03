<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for time sheets
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\Holidays\CreateResponse;
use App\Http\Responses\Holidays\DestroyResponse;
use App\Http\Responses\Holidays\EditResponse;
use App\Http\Responses\Holidays\IndexResponse;
use App\Http\Responses\Holidays\StoreResponse;
use App\Http\Responses\Holidays\UpdateResponse;
use App\Repositories\HolidayRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class Holidays extends Controller {

    /**
     * The holiday repository instance.
     */
    protected $holidayrepo;

    public function __construct(HolidayRepository $holidayrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('holidaysMiddlewareIndex')->only([
            'index',
            'update',
            'store',
        ]);
        $this->middleware('holidaysMiddlewareEdit')->only([
            'update',
        ]);
        $this->middleware('holidaysMiddlewareDestroy')->only([
            'destroy',
        ]);

        $this->holidayrepo = $holidayrepo;
    }

    /**
     * Display a listing of holidays
     * @return \Illuminate\Http\Response
     */
    public function index() {

        // //only present holidays
        // request()->merge([
        //     'filter_holiday_status' => 'present',
        // ]);

        //get holidays
        $holidays = $this->holidayrepo->search();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('holidays'),
            'holidays' => $holidays,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create() {

        //response
        return new CreateResponse();
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function store() {
        //validate
        $validator = Validator::make(request()->all(), [
            'holiday_date' => [
                'required',
                'date',
            ],
            'holiday_description' => [
                'nullable',
                'string',
            ],
        ]);

        //validation errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }
            abort(409, $messages);
        }

        $holiday_date = request('holiday_date');
        $holiday_description = request('holiday_description');

        //store
        $holiday = new \App\Models\Holiday();
        $holiday->holiday_date = $holiday_date;
        $holiday->holiday_description = $holiday_description;
        $holiday->save();
        
        $holidays = $this->holidayrepo->search();
        $count = $holidays->total();

        //get refreshed holiday
        $holidays = $this->holidayrepo->search($holiday->holiday_id);

        //reponse payload
        $payload = [
            'holidays' => $holidays,
            'count' => $count,
        ];

        //generate a response
        return new StoreResponse($payload);
    }

    public function show() {
        abort('404');
    }

    /**
     * Show the form for editing the specified holiday
     * @param object CategoryRepository instance of the repository
     * @param int $id holiday id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        if (!$holiday = \App\Models\Holiday::Where('holiday_id', $id)->first()) {
            abort(404);
        }

        //reponse payload
        $payload = [
            'holiday' => $holiday,
        ];

        //return the reposnse
        return new EditResponse($payload);
    }

    /**
     * Update the specified holidayin storage.
     * @param object holidaystoreUpdate instance of the repository
     * @param object UnitRepository instance of the repository
     * @param int $id holiday id
     * @return \Illuminate\Http\Response
     */
    public function update($id) {
        // get the holiday
        if (!$holiday = \App\Models\Holiday::where('holiday_id', $id)->first()) {
            abort(404);
        }

        //validate
        $validator = Validator::make(request()->all(), [
            'holiday_date' => [
                'required',
                'date',
            ],
            'holiday_description' => [
                'nullable',
                'string',
            ],
        ]);

        //validation errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }
            abort(409, $messages);
        }
        
        $holiday_date = request('holiday_date');
        $holiday_description = request('holiday_description');

        //update
        $holiday->holiday_date = $holiday_date;
        $holiday->holiday_description = $holiday_description;
        $holiday->save();

        //get updates
        $holidays = $this->holidayrepo->search($id);

        //reponse payload
        $payload = [
            'holidays' => $holidays,
        ];

        //generate a response
        return new UpdateResponse($payload);
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy() {

        //delete each record in the array
        $allrows = array();
        foreach (request('ids') as $id => $value) {
            //only checked items
            if ($value == 'on') {
                //get the holiday
                $holiday = \App\Models\Holiday::Where('holiday_id', $id)->first();
                //delete client
                $holiday->delete();
                //add to array
                $allrows[] = $id;
            }
        }
        //reponse payload
        $payload = [
            'allrows' => $allrows,
        ];

        //generate a response
        return new DestroyResponse($payload);
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
            'crumbs' => [
                __('lang.holidays'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'holidays',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_holidays' => 'active',
            'submenu_holidays' => 'active',
            'sidepanel_id' => 'sidepanel-filter-holidays',
            'dynamic_search_url' => url('holidays/search?action=search&holidayresource_id=' . request('holidayresource_id') . '&holidayresource_type=' . request('holidayresource_type')),
            'add_button_classes' => '',
            'add_button_classes' => 'add-edit-item-button',
            'load_more_button_route' => 'holidays',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => __('lang.add_holidays'),
            'add_modal_create_url' => url('holidays/create'),
            'add_modal_action_url' => url('holidays'),
            'add_modal_action_ajax_class' => '',
            'add_modal_size' => 'modal-sm',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //projects list page
        if ($section == 'holidays') {
            $page += [
                'meta_title' => __('lang.holidays'),
                'heading' => __('lang.holidays'),

            ];
            if (request('source') == 'ext') {
                $page += [
                    'list_page_actions_size' => 'col-lg-12',
                ];
            }
            return $page;
        }

        //return
        return $page;
    }
}
