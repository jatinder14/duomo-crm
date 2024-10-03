<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for time sheets
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\Leaves\CreateResponse;
use App\Http\Responses\Leaves\DestroyResponse;
use App\Http\Responses\Leaves\EditResponse;
use App\Http\Responses\Leaves\IndexResponse;
use App\Http\Responses\Leaves\StoreResponse;
use App\Http\Responses\Leaves\UpdateResponse;
use App\Repositories\LeaveRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class Leaves extends Controller {

    /**
     * The leave repository instance.
     */
    protected $leaverepo;

    public function __construct(LeaveRepository $leaverepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('leavesMiddlewareIndex')->only([
            'index',
            'update',
            'store',
        ]);
        $this->middleware('leavesMiddlewareEdit')->only([
            'update',
        ]);
        $this->middleware('leavesMiddlewareDestroy')->only([
            'destroy',
        ]);

        $this->leaverepo = $leaverepo;
    }

    /**
     * Display a listing of leaves
     * @return \Illuminate\Http\Response
     */
    public function index() {

        // //only present leaves
        // request()->merge([
        //     'filter_leave_status' => 'present',
        // ]);

        //get leaves
        $leaves = $this->leaverepo->search();
        $categories = \App\Models\LeaveCategory::orderBy('leavecategory_position', 'asc')->get();
        $statuses = \App\Models\LeaveStatus::orderBy('leavestatus_position', 'asc')->get();
        //reponse payload
        $payload = [
            'page' => $this->pageSettings('leaves'),
            'leaves' => $leaves,
            'categories' => $categories,
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

        $categories = \App\Models\LeaveCategory::orderBy('leavecategory_position', 'asc')->get();
        $statuses = \App\Models\LeaveStatus::orderBy('leavestatus_position', 'asc')->get();
        $payload = [
            'categories' => $categories,
            'statuses' => $statuses,
        ];
        //response
        return new CreateResponse($payload);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function store() {
        //validate
        $validator = Validator::make(request()->all(), [
            'leave_creatorid' => [
                'required',
                Rule::exists('users', 'id'),
            ],
            'leave_date_from' => [
                'required',
                'date',
            ],
            'leave_date_to' => [
                'required',
                'date',
            ],
            'leave_categoryid' => [
                'required',
            ],
            'leave_reason' => [
                'required',
                'string',
            ],
            'leave_status' => [
                'required',
            ],
            'leave_comment' => [
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

        $leave_creatorid = request('leave_creatorid');
        $leave_date_from = request('leave_date_from');
        $leave_date_to = request('leave_date_to');
        $leave_categoryid = request('leave_categoryid');
        $leave_reason = request('leave_reason');
        $leave_comment = request('leave_comment');
        $leave_status = request('leave_status');

        //validate
        if ($leave_date_from && $leave_date_to && $leave_date_from > $leave_date_to) {
            abort(409, __('lang.date_to_must_be_greater_than_or_equal_to_date_from'));
        }

        //store
        $leave = new \App\Models\Leave();
        $leave->leave_creatorid = $leave_creatorid;
        $leave->leave_date_from = $leave_date_from;
        $leave->leave_date_to = $leave_date_to;
        $leave->leave_categoryid = $leave_categoryid;
        $leave->leave_reason = $leave_reason;
        $leave->leave_comment = $leave_comment;
        $leave->leave_status = $leave_status;
        $leave->save();
        
        $leaves = $this->leaverepo->search();
        $count = $leaves->total();

        //get refreshed leave
        $leaves = $this->leaverepo->search($leave->leave_id);

        //reponse payload
        $payload = [
            'leaves' => $leaves,
            'count' => $count,
        ];

        //generate a response
        return new StoreResponse($payload);
    }

    public function show() {
        abort('404');
    }

    /**
     * Show the form for editing the specified leave
     * @param object CategoryRepository instance of the repository
     * @param int $id leave id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        if (!$leave = \App\Models\Leave::Where('leave_id', $id)->first()) {
            abort(404);
        }
        $categories = \App\Models\LeaveCategory::orderBy('leavecategory_position', 'asc')->get();
        $statuses = \App\Models\LeaveStatus::orderBy('leavestatus_position', 'asc')->get();
        //reponse payload
        $payload = [
            'leave' => $leave,
            'categories' => $categories,
            'statuses' => $statuses,
        ];

        //return the reposnse
        return new EditResponse($payload);
    }

    /**
     * Update the specified leavein storage.
     * @param object leavestoreUpdate instance of the repository
     * @param object UnitRepository instance of the repository
     * @param int $id leave id
     * @return \Illuminate\Http\Response
     */
    public function update($id) {
        // get the leave
        if (!$leave = \App\Models\Leave::where('leave_id', $id)->first()) {
            abort(404);
        }

        //validate
        $validator = Validator::make(request()->all(), [
            'leave_creatorid' => [
                'required',
                Rule::exists('users', 'id'),
            ],
            'leave_date_from' => [
                'required',
                'date',
            ],
            'leave_date_to' => [
                'required',
                'date',
            ],
            'leave_categoryid' => [
                'required',
            ],
            'leave_reason' => [
                'required',
                'string',
            ],
            'leave_status' => [
                'required',
            ],
            'leave_comment' => [
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
        
        $leave_creatorid = request('leave_creatorid');
        $leave_date_from = request('leave_date_from');
        $leave_date_to = request('leave_date_to');
        $leave_categoryid = request('leave_categoryid');
        $leave_reason = request('leave_reason');
        $leave_comment = request('leave_comment');
        $leave_status = request('leave_status');

        //validate
        if ($leave_date_from && $leave_date_to && $leave_date_from > $leave_date_to) {
            abort(409, __('lang.date_to_must_be_greater_than_or_equal_to_date_from'));
        }

        //update
        $leave->leave_creatorid = $leave_creatorid;
        $leave->leave_date_from = $leave_date_from;
        $leave->leave_date_to = $leave_date_to;
        $leave->leave_categoryid = $leave_categoryid;
        $leave->leave_reason = $leave_reason;
        $leave->leave_comment = $leave_comment;
        $leave->leave_status = $leave_status;
        $leave->save();

        //get updates
        $leaves = $this->leaverepo->search($id);

        //reponse payload
        $payload = [
            'leaves' => $leaves,
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
                //get the leave
                $leave = \App\Models\Leave::Where('leave_id', $id)->first();
                //delete client
                $leave->delete();
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
                __('lang.leaves'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'leaves',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_leaves' => 'active',
            'submenu_leaves' => 'active',
            'sidepanel_id' => 'sidepanel-filter-leaves',
            'dynamic_search_url' => url('leaves/search?action=search&leaveresource_id=' . request('leaveresource_id') . '&leaveresource_type=' . request('leaveresource_type')),
            'add_button_classes' => '',
            'add_button_classes' => 'add-edit-item-button',
            'load_more_button_route' => 'leaves',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => __('lang.add_leaves'),
            'add_modal_create_url' => url('leaves/create'),
            'add_modal_action_url' => url('leaves'),
            'add_modal_action_ajax_class' => '',
            'add_modal_size' => 'modal-sm',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //projects list page
        if ($section == 'leaves') {
            $page += [
                'meta_title' => __('lang.leaves'),
                'heading' => __('lang.leaves'),

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
