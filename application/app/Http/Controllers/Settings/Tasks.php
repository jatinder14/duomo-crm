<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for task settings
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Settings;
use App\Http\Controllers\Controller;
use App\Http\Responses\Settings\Tasks\CreatePriorityResponse;
use App\Http\Responses\Settings\Tasks\CreateStatusResponse;
use App\Http\Responses\Settings\Tasks\DestroyStatusResponse;
use App\Http\Responses\Settings\Tasks\EditStatusResponse;
use App\Http\Responses\Settings\Tasks\IndexResponse;
use App\Http\Responses\Settings\Tasks\moveResponse;
use App\Http\Responses\Settings\Tasks\MoveUpdateResponse;
use App\Http\Responses\Settings\Tasks\PrioritiesResponse;
use App\Http\Responses\Settings\Tasks\StatusesResponse;
use App\Http\Responses\Settings\Tasks\StorePriorityResponse;
use App\Http\Responses\Settings\Tasks\StoreStatusResponse;
use App\Http\Responses\Settings\Tasks\UpdateResponse;
use App\Http\Responses\Settings\Tasks\UpdateStatusResponse;
use App\Http\Responses\Settings\Tasks\EditPriorityResponse;
use App\Http\Responses\Settings\Tasks\UpdatePriorityResponse;

use App\Http\Responses\Settings\Tasks\DestroyPriorityResponse;
use App\Http\Responses\Settings\Tasks\movePriorityResponse;
use App\Http\Responses\Settings\Tasks\MovePriorityUpdateResponse;

use App\Repositories\SettingsRepository;
use App\Repositories\TaskPriorityRepository;
use App\Repositories\TaskStatusRepository;
use Illuminate\Http\Request;
use Validator;

class Tasks extends Controller {

    //vars
    protected $settingsrepo;
    protected $statusrepo;
    protected $priorityrepo;

    public function __construct(SettingsRepository $settingsrepo, TaskStatusRepository $statusrepo, TaskPriorityRepository $priorityrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //settings general
        $this->middleware('settingsMiddlewareIndex');

        $this->settingsrepo = $settingsrepo;
        $this->statusrepo = $statusrepo;
        $this->priorityrepo = $priorityrepo;

    }

    /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        //crumbs, page data & stats
        $page = $this->pageSettings();

        $settings = \App\Models\Settings::find(1);

        //reponse payload
        $payload = [
            'page' => $page,
            'settings' => $settings,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update() {

        //update
        if (!$this->settingsrepo->updateTasks()) {
            abort(409);
        }

        //update additional settings
        \App\Models\Settings2::where('settings2_id', 1)
            ->update([
                'settings2_tasks_manage_dependencies' => request('settings2_tasks_manage_dependencies'),
            ]);

        //reponse payload
        $payload = [];

        //generate a response
        return new UpdateResponse($payload);
    }

    /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function statuses() {

        //crumbs, page data & stats
        $page = $this->pageSettings('statuses');

        $statuses = $this->statusrepo->search();

        //reponse payload
        $payload = [
            'page' => $page,
            'statuses' => $statuses,
        ];

        //show the view
        return new StatusesResponse($payload);
    }

    /**
     * Show the form for editing the specified resource.
     * @url baseusr/items/1/edit
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function editStatus($id) {

        //page settings
        $page = $this->pageSettings('edit');

        //client tasksources
        $statuses = $this->statusrepo->search($id);

        //not found
        if (!$status = $statuses->first()) {
            abort(409, __('lang.error_loading_item'));
        }

        //reponse payload
        $payload = [
            'page' => $page,
            'status' => $status,
        ];

        //response
        return new EditStatusResponse($payload);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus($id) {

        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'taskstatus_title' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (\App\Models\TaskStatus::where('taskstatus_title', $value)
                        ->where('taskstatus_id', '!=', request()->route('id'))
                        ->exists()) {
                        return $fail(__('lang.status_already_exists'));
                    }
                }],
        ], $messages);

        //errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }

        //update the resource
        if (!$this->statusrepo->update($id)) {
            abort(409);
        }

        //get the category object (friendly for rendering in blade template)
        $statuses = $this->statusrepo->search($id);

        //reponse payload
        $payload = [
            'statuses' => $statuses,
        ];

        //process reponse
        return new UpdateStatusResponse($payload);

    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function createStatus() {

        //page settings
        $page = $this->pageSettings();
        $page['default_color'] = 'checked';

        //reponse payload
        $payload = [
            'page' => $page,
        ];

        //show the form
        return new CreateStatusResponse($payload);
    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\Response
     */
    public function storeStatus() {

        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'taskstatus_title' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (\App\Models\TaskStatus::where('taskstatus_title', $value)
                        ->exists()) {
                        return $fail(__('lang.status_already_exists'));
                    }
                }],
        ], $messages);

        //errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }

        //get the last row (order by position - desc)
        if ($last = \App\Models\TaskStatus::orderBy('taskstatus_position', 'desc')->first()) {
            $position = $last->taskstatus_position + 1;
        } else {
            //default position
            $position = 2;
        }

        //create the source
        if (!$taskstatus_id = $this->statusrepo->create($position)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //get the source object (friendly for rendering in blade template)
        $statuses = $this->statusrepo->search($taskstatus_id);

        //reponse payload
        $payload = [
            'statuses' => $statuses,
        ];

        //process reponse
        return new StoreStatusResponse($payload);

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function move($id) {

        //page settings
        $page = $this->pageSettings();

        //client tasksources
        $statuses = \App\Models\TaskStatus::get();

        //reponse payload
        $payload = [
            'page' => $page,
            'statuses' => $statuses,
        ];

        //response
        return new moveResponse($payload);
    }

    /**
     * Move tasks from one category to another
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function updateMove($id) {

        //page settings
        $page = $this->pageSettings();

        //move the tasks
        \App\Models\Task::where('task_status', $id)->update(['task_status' => request('tasks_status')]);

        //client tasksources
        $statuses = $this->statusrepo->search();

        //reponse payload
        $payload = [
            'page' => $page,
            'statuses' => $statuses,
        ];

        //response
        return new MoveUpdateResponse($payload);
    }

    /**
     * Update a stages position
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function updateStagePositions() {

        //reposition each task status
        $i = 1;
        foreach (request('sort-stages') as $key => $id) {
            if (is_numeric($id)) {
                \App\Models\TaskStatus::where('taskstatus_id', $id)->update(['taskstatus_position' => $i]);
            }
            $i++;
        }

        //retun simple success json
        return response()->json('success', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function destroyStatus($id) {

        //get record
        if (!\App\Models\TaskStatus::find($id)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //get it in useful format
        $statuses = $this->statusrepo->search($id);
        $status = $statuses->first();

        //validation: default
        if ($status->taskstatus_system_default == 'yes') {
            abort(409, __('lang.you_cannot_delete_system_default_item'));
        }

        //validation: default
        if ($status->count_tasks != 0) {
            abort(409, __('lang.task_status_not_empty'));
        }

        //delete the category
        $status->delete();

        //reponse payload
        $payload = [
            'status_id' => $id,
        ];

        //process reponse
        return new DestroyStatusResponse($payload);
    }

    /**
     * show task priorities
     *
     * @return \Illuminate\Http\Response
     */
    public function priorities() {

        //crumbs, page data & stats
        $page = $this->pageSettings('priorities');

        $priorities = $this->priorityrepo->search();

        //reponse payload
        $payload = [
            'page' => $page,
            'priorities' => $priorities,
        ];

        //show the view
        return new PrioritiesResponse($payload);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function createPriority() {

        //page settings
        $page = $this->pageSettings();
        $page['default_color'] = 'checked';

        //reponse payload
        $payload = [
            'page' => $page,
        ];

        //show the form
        return new CreatePriorityResponse($payload);
    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\Response
     */
    public function storePriority() {

        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'taskpriority_title' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (\App\Models\TaskPriority::where('taskpriority_title', $value)
                        ->exists()) {
                        return $fail(__('lang.priority_already_exists'));
                    }
                }],
        ], $messages);

        //errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }

        //get the last row (order by position - desc)
        if ($last = \App\Models\TaskPriority::orderBy('taskpriority_position', 'desc')->first()) {
            $position = $last->taskpriority_position + 1;
        } else {
            //default position
            $position = 2;
        }

        //create the source
        if (!$taskpriority_id = $this->priorityrepo->create($position)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //get the source object (friendly for rendering in blade template)
        $priorities = $this->priorityrepo->search($taskpriority_id);

        //reponse payload
        $payload = [
            'priorities' => $priorities,
        ];

        //process reponse
        return new StorePriorityResponse($payload);

    }

        /**
     * Show the form for editing the specified resource.
     * @url baseusr/items/1/edit
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function editPriority($id) {

        //page settings
        $page = $this->pageSettings('edit');

        //client tasksources
        $priorities = $this->priorityrepo->search($id);

        //not found
        if (!$priority = $priorities->first()) {
            abort(409, __('lang.error_loading_item'));
        }

        //reponse payload
        $payload = [
            'page' => $page,
            'priority' => $priority,
        ];

        //response
        return new EditPriorityResponse($payload);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function updatePriority($id) {

        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'taskpriority_title' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (\App\Models\TaskPriority::where('taskpriority_title', $value)
                        ->where('taskpriority_id', '!=', request()->route('id'))
                        ->exists()) {
                        return $fail(__('lang.priority_already_exists'));
                    }
                }],
        ], $messages);

        //errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }

        //update the resource
        if (!$this->priorityrepo->update($id)) {
            abort(409);
        }

        //get the category object (friendly for rendering in blade template)
        $priorities = $this->priorityrepo->search($id);

        //reponse payload
        $payload = [
            'priorities' => $priorities,
        ];

        //process reponse
        return new UpdatePriorityResponse($payload);

    }


        /**
     * Remove the specified resource from storage.
     *
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function destroyPriority($id) {

        //get record
        if (!\App\Models\TaskPriority::find($id)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //get it in useful format
        $priorities = $this->priorityrepo->search($id);
        $priority = $priorities->first();

        //validation: default
        if ($priority->taskpriority_system_default == 'yes') {
            abort(409, __('lang.you_cannot_delete_system_default_item'));
        }

        //validation: default
        if ($priority->count_tasks != 0) {
            abort(409, __('lang.task_priority_not_empty'));
        }

        //delete the category
        $priority->delete();

        //reponse payload
        $payload = [
            'priority_id' => $id,
        ];

        //process reponse
        return new DestroyPriorityResponse($payload);
    }


        /**
     * Update a stages position
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function updatePriorityPositions() {

        //reposition each task priority
        $i = 1;
        foreach (request('sort-priorities') as $key => $id) {
            if (is_numeric($id)) {
                \App\Models\TaskPriority::where('taskpriority_id', $id)->update(['taskpriority_position' => $i]);
            }
            $i++;
        }

        //retun simple success json
        return response()->json('success', 200);
    }

        /**
     * Show the form for editing the specified resource.
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function movePriority($id) {

        //page settings
        $page = $this->pageSettings();

        //client tasksources
        $priorities = \App\Models\TaskPriority::get();

        //reponse payload
        $payload = [
            'page' => $page,
            'priorities' => $priorities,
        ];

        //response
        return new movePriorityResponse($payload);
    }

    /**
     * Move tasks from one category to another
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function updatePriorityMove($id) {

        //page settings
        $page = $this->pageSettings();

        //move the tasks
        \App\Models\Task::where('task_priority', $id)->update(['task_priority' => request('tasks_priority')]);

        //client tasksources
        $priorities = $this->priorityrepo->search();

        //reponse payload
        $payload = [
            'page' => $page,
            'priorities' => $priorities,
        ];

        //response
        return new MovePriorityUpdateResponse($payload);
    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        $page = [
            'crumbs' => [
                __('lang.settings'),
                __('lang.tasks'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page' => 'settings',
            'meta_title' => __('lang.settings'),
            'heading' => __('lang.settings'),
            'settingsmenu_general' => 'active',
        ];

        config([
            //visibility - add project buttton
            'visibility.list_page_actions_add_button' => true,
        ]);

        //create new resource
        if ($section == 'statuses') {
            $page['crumbs'] = [
                __('lang.settings'),
                __('lang.tasks'),
                __('lang.statuses'),
            ];
            $page += [
                'add_modal_title' => __('lang.add_new_task_status'),
                'add_modal_create_url' => url('settings/tasks/statuses/create'),
                'add_modal_action_url' => url('settings/tasks/statuses/create'),
                'add_modal_action_ajax_class' => '',
                'add_modal_action_ajax_loading_target' => 'commonModalBody',
                'add_modal_action_method' => 'POST',
            ];
        }

        //create new resource
        if ($section == 'priorities') {
            $page['crumbs'] = [
                __('lang.settings'),
                __('lang.tasks'),
                __('lang.priorities'),
            ];
            $page += [
                'add_modal_title' => __('lang.add_new_task_status'),
                'add_modal_create_url' => url('settings/tasks/priorities/create'),
                'add_modal_action_url' => url('settings/tasks/priorities/create'),
                'add_modal_action_ajax_class' => '',
                'add_modal_action_ajax_loading_target' => 'commonModalBody',
                'add_modal_action_method' => 'POST',
            ];
        }

        return $page;
    }

}
