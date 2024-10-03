<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for ticket settings
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Settings;
use App\Http\Controllers\Controller;
use App\Http\Responses\Settings\Tickets\CreateStatusResponse;
use App\Http\Responses\Settings\Tickets\DestroyStatusResponse;
use App\Http\Responses\Settings\Tickets\EditStatusResponse;
use App\Http\Responses\Settings\Tickets\IndexResponse;
use App\Http\Responses\Settings\Tickets\moveResponse;
use App\Http\Responses\Settings\Tickets\MoveUpdateResponse;
use App\Http\Responses\Settings\Tickets\SettingsResponse;
use App\Http\Responses\Settings\Tickets\StatusesResponse;
use App\Http\Responses\Settings\Tickets\StoreStatusResponse;
use App\Http\Responses\Settings\Tickets\UpdateResponse;
use App\Http\Responses\Settings\Tickets\UpdateSettingsResponse;
use App\Http\Responses\Settings\Tickets\UpdateStatusResponse;
use App\Repositories\SettingsRepository;
use App\Repositories\TicketStatusRepository;
use DB;
use Illuminate\Http\Request;
use Validator;

class Tickets extends Controller {

    /**
     * The repository instances.
     */
    protected $settingsrepo;
    protected $statusrepo;

    public function __construct(SettingsRepository $settingsrepo, TicketStatusRepository $statusrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //settings general
        $this->middleware('settingsMiddlewareIndex');

        $this->settingsrepo = $settingsrepo;
        $this->statusrepo = $statusrepo;

    }

    /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        //crumbs, page data & stats
        $page = $this->pageSettings();

        //save system settings into config array
        $settings = \App\Models\Settings::leftJoin('settings2', 'settings2.settings2_id', '=', 'settings.settings_id')
            ->Where('settings_id', 1)
            ->first();

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

        //custom error messages
        $messages = [];

        //update
        if (!$this->settingsrepo->updateTickets()) {
            abort(409);
        }

        //reset existing account owner
        \App\Models\Settings2::where('settings2_id', 1)
            ->update([
                'settings2_tickets_replying_interface' => request('settings2_tickets_replying_interface'),
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

        //client ticketsources
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
            'ticketstatus_title' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (\App\Models\TicketStatus::where('ticketstatus_title', $value)
                        ->where('ticketstatus_id', '!=', request()->route('id'))
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
            'ticketstatus_title' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (\App\Models\TicketStatus::where('ticketstatus_title', $value)
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
        if ($last = \App\Models\TicketStatus::orderBy('ticketstatus_position', 'desc')->first()) {
            $position = $last->ticketstatus_position + 1;
        } else {
            //default position
            $position = 2;
        }

        //create the source
        if (!$ticketstatus_id = $this->statusrepo->create($position)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //get the source object (friendly for rendering in blade template)
        $statuses = $this->statusrepo->search($ticketstatus_id);

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

        //client ticketsources
        $statuses = \App\Models\TicketStatus::get();

        //reponse payload
        $payload = [
            'page' => $page,
            'statuses' => $statuses,
        ];

        //response
        return new moveResponse($payload);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function statusSettings($id) {

        //page settings
        $page = $this->pageSettings();

        //get the status
        if (!$status = \App\Models\TicketStatus::Where('ticketstatus_id', $id)->first()) {
            abort(404);
        }

        //reponse payload
        $payload = [
            'page' => $page,
            'status' => $status,
        ];

        //response
        return new SettingsResponse($payload);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function statusSettingsUpdate($id) {

        //page settings
        $page = $this->pageSettings();

        //get the status
        if (!$status = \App\Models\TicketStatus::Where('ticketstatus_id', $id)->first()) {
            abort(404);
        }

        //reset all other statuses
        if (request('ticketstatus_use_for_client_replied') == 'on') {
            DB::table('tickets_status')->update([
                'ticketstatus_use_for_client_replied' => 'no',
            ]);
        }

        //reset all other statuses
        if (request('ticketstatus_use_for_team_replied') == 'on') {
            DB::table('tickets_status')->update([
                'ticketstatus_use_for_team_replied' => 'no',
            ]);
        }

        //update the status
        $status = \App\Models\TicketStatus::Where('ticketstatus_id', $id)->first();
        $status->ticketstatus_use_for_client_replied = (request('ticketstatus_use_for_client_replied') == 'on') ? 'yes' : 'no';
        $status->ticketstatus_use_for_team_replied = (request('ticketstatus_use_for_team_replied') == 'on') ? 'yes' : 'no';
        $status->save();

        //get all statuses
        $statuses = $this->statusrepo->search();

        //reponse payload
        $payload = [
            'page' => $page,
            'statuses' => $statuses,
        ];

        //response
        return new UpdateSettingsResponse($payload);
    }

    /**
     * Move tickets from one category to another
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function updateMove($id) {

        //page settings
        $page = $this->pageSettings();

        //move the tickets
        \App\Models\Ticket::where('ticket_status', $id)->update(['ticket_status' => request('tickets_status')]);

        //client ticketsources
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

        //reposition each ticket status
        $i = 1;
        foreach (request('sort-stages') as $key => $id) {
            if (is_numeric($id)) {
                \App\Models\TicketStatus::where('ticketstatus_id', $id)->update(['ticketstatus_position' => $i]);
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
        if (!\App\Models\TicketStatus::find($id)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //get it in useful format
        $statuses = $this->statusrepo->search($id);
        $status = $statuses->first();

        //validation: default
        if ($status->ticketstatus_system_default == 'yes') {
            abort(409, __('lang.you_cannot_delete_system_default_item'));
        }

        //validation: default
        if ($status->count_tickets != 0) {
            abort(409, __('lang.ticket_status_not_empty'));
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
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        $page = [
            'crumbs' => [
                __('lang.settings'),
                __('lang.tickets'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page' => 'settings',
            'meta_title' => __('lang.settings'),
            'heading' => __('lang.settings'),
            'settingsmenu_general' => 'active',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => __('lang.add_new_ticket_status'),
            'add_modal_create_url' => url('settings/tickets/statuses/create'),
            'add_modal_action_url' => url('settings/tickets/statuses/create'),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        config([
            //visibility - add project buttton
            'visibility.list_page_actions_add_button' => true,
        ]);

        //create new resource
        if ($section == 'statuses') {
            $page['crumbs'] = [
                __('lang.settings'),
                __('lang.tickets'),
                __('lang.statuses'),
            ];
        }

        return $page;
    }

}
