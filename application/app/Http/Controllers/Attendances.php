<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for time sheets
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\Attendances\CreateResponse;
use App\Http\Responses\Attendances\DestroyResponse;
use App\Http\Responses\Attendances\EditResponse;
use App\Http\Responses\Attendances\IndexResponse;
use App\Http\Responses\Attendances\StoreResponse;
use App\Http\Responses\Attendances\UpdateResponse;
use App\Repositories\AttendanceRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class Attendances extends Controller {

    /**
     * The attendance repository instance.
     */
    protected $attendancerepo;

    public function __construct(AttendanceRepository $attendancerepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('attendancesMiddlewareIndex')->only([
            'index',
            'update',
            'store',
        ]);
        $this->middleware('attendancesMiddlewareEdit')->only([
            'update',
        ]);
        $this->middleware('attendancesMiddlewareDestroy')->only([
            'destroy',
        ]);

        $this->attendancerepo = $attendancerepo;
    }

    /**
     * Display a listing of attendances
     * @return \Illuminate\Http\Response
     */
    public function index() {

        // //only present attendances
        // request()->merge([
        //     'filter_attendance_status' => 'present',
        // ]);

        //get attendances
        $attendances = $this->attendancerepo->search();
        $statuses = \App\Models\AttendanceStatus::orderBy('attendancestatus_position', 'asc')->get();
        //reponse payload
        $payload = [
            'page' => $this->pageSettings('attendances'),
            'attendances' => $attendances,
            'statuses' => $statuses,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $statuses = \App\Models\AttendanceStatus::orderBy('attendancestatus_position', 'asc')->get();
        //response
        $payload = [
            'statuses' => $statuses,
        ];
        return new CreateResponse($payload);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function store() {
        //validate
        $validator = Validator::make(request()->all(), [
            'user_id' => [
                'required',
                Rule::exists('users', 'id'),
            ],
            'date' => [
                'required',
                'date',
            ],
            'in_time' => [
                'required',
            ],
            'out_time' => [
                'required',
            ],
            'reason' => [
                'nullable',
                'string',
            ],
            'attendance_status' => [
                'required',
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

        $user_id = request('user_id');
        $date = request('date');
        $in_time = request('in_time');
        $out_time = request('out_time');
        $reason = request('reason');
        $attendance_status = request('attendance_status');

        //validate
        if ($in_time && $out_time && $in_time > $out_time) {
            abort(409, __('lang.out_time_must_be_greater_than_or_equal_to_in_time'));
        }

        //store
        $attendance = new \App\Models\Attendance();
        $attendance->user_id = $user_id;
        $attendance->date = $date;
        $attendance->in_time = $in_time;
        $attendance->out_time = $out_time;
        $attendance->reason = $reason;
        $attendance->attendance_status = $attendance_status;
        $attendance->save();
        
        $attendances = $this->attendancerepo->search();
        $count = $attendances->total();

        //get refreshed attendance
        $attendances = $this->attendancerepo->search($attendance->attendance_id);

        //reponse payload
        $payload = [
            'attendances' => $attendances,
            'count' => $count,
        ];

        //generate a response
        return new StoreResponse($payload);
    }

    public function show() {
        abort('404');
    }

    /**
     * Show the form for editing the specified attendance
     * @param object CategoryRepository instance of the repository
     * @param int $id attendance id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        if (!$attendance = \App\Models\Attendance::Where('attendance_id', $id)->first()) {
            abort(404);
        }

        $statuses = \App\Models\AttendanceStatus::orderBy('attendancestatus_position', 'asc')->get();
        //reponse payload
        $payload = [
            'attendance' => $attendance,
            'statuses' => $statuses,
        ];

        //return the reposnse
        return new EditResponse($payload);
    }

    /**
     * Update the specified attendancein storage.
     * @param object attendancestoreUpdate instance of the repository
     * @param object UnitRepository instance of the repository
     * @param int $id attendance id
     * @return \Illuminate\Http\Response
     */
    public function update($id) {
        // get the attendance
        if (!$attendance = \App\Models\Attendance::where('attendance_id', $id)->first()) {
            abort(404);
        }

        //validate
        $validator = Validator::make(request()->all(), [
            'user_id' => [
                'required',
                Rule::exists('users', 'id'),
            ],
            'date' => [
                'required',
                'date',
            ],
            'in_time' => [
                'required',
            ],
            'out_time' => [
                'required',
            ],
            'reason' => [
                'nullable',
                'string',
            ],
            'attendance_status' => [
                'required',
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
        
        $user_id = request('user_id');
        $date = request('date');
        $in_time = request('in_time');
        $out_time = request('out_time');
        $reason = request('reason');
        $attendance_status = request('attendance_status');

        //validate
        if ($in_time && $out_time && $in_time > $out_time) {
            abort(409, __('lang.out_time_must_be_greater_than_or_equal_to_in_time'));
        }

        //update
        $attendance->user_id = $user_id;
        $attendance->date = $date;
        $attendance->in_time = $in_time;
        $attendance->out_time = $out_time;
        $attendance->reason = $reason;
        $attendance->attendance_status = $attendance_status;
        $attendance->save();

        //get updates
        $attendances = $this->attendancerepo->search($id);

        //reponse payload
        $payload = [
            'attendances' => $attendances,
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
                //get the attendance
                $attendance = \App\Models\Attendance::Where('attendance_id', $id)->first();
                //delete client
                $attendance->delete();
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
                __('lang.attendances'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'attendances',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_attendances' => 'active',
            'submenu_attendances' => 'active',
            'sidepanel_id' => 'sidepanel-filter-attendances',
            'dynamic_search_url' => url('attendances/search?action=search&attendanceresource_id=' . request('attendanceresource_id') . '&attendanceresource_type=' . request('attendanceresource_type')),
            'add_button_classes' => '',
            'add_button_classes' => 'add-edit-item-button',
            'load_more_button_route' => 'attendances',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => __('lang.add_attendance'),
            'add_modal_create_url' => url('attendances/create'),
            'add_modal_action_url' => url('attendances'),
            'add_modal_action_ajax_class' => '',
            'add_modal_size' => 'modal-sm',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //projects list page
        if ($section == 'attendances') {
            $page += [
                'meta_title' => __('lang.attendances'),
                'heading' => __('lang.attendances'),

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
