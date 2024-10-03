<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for estimates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Estimates\EstimateClone;
use App\Http\Requests\Estimates\EstimateSave;
use App\Http\Requests\Estimates\EstimateStoreUpdate;
use App\Http\Requests\Estimates\EstimateUpdateAutomation;
use App\Http\Responses\Common\ChangeCategoryResponse;
use App\Http\Responses\Estimates\AcceptResponse;
use App\Http\Responses\Estimates\AttachFilesResponse;
use App\Http\Responses\Estimates\AttachProjectResponse;
use App\Http\Responses\Estimates\ChangeCategoryUpdateResponse;
use App\Http\Responses\Estimates\ChangeStatusResponse;
use App\Http\Responses\Estimates\ConvertToEstimate;
use App\Http\Responses\Estimates\CreateCloneResponse;
use App\Http\Responses\Estimates\CreateResponse;
use App\Http\Responses\Estimates\DeclineResponse;
use App\Http\Responses\Estimates\DestroyResponse;
use App\Http\Responses\Estimates\DocumentEditingResponse;
use App\Http\Responses\Estimates\EditAutomationResponse;
use App\Http\Responses\Estimates\EditResponse;
use App\Http\Responses\Estimates\IndexResponse;
use App\Http\Responses\Estimates\PDFResponse;
use App\Http\Responses\Estimates\PublishResponse;
use App\Http\Responses\Estimates\PublishRevisedResponse;
use App\Http\Responses\Estimates\ResendResponse;
use App\Http\Responses\Estimates\SaveResponse;
use App\Http\Responses\Estimates\ShowPublicResponse;
use App\Http\Responses\Estimates\ShowResponse;
use App\Http\Responses\Estimates\StoreCloneResponse;
use App\Http\Responses\Estimates\StoreResponse;
use App\Http\Responses\Estimates\UpdateAutomationResponse;
use App\Http\Responses\Estimates\UpdateResponse;
use App\Http\Responses\Estimates\UpdateTaxtypeResponse;
use App\Http\Responses\Estimates\PublishScheduledResponse;
use App\Repositories\CategoryRepository;
use App\Repositories\ClientRepository;
use App\Repositories\CloneEstimateRepository;
use App\Repositories\DestroyRepository;
use App\Repositories\EmailerRepository;
use App\Repositories\EstimateAutomationRepository;
use App\Repositories\EstimateGeneratorRepository;
use App\Repositories\EstimateRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventTrackingRepository;
use App\Repositories\FileRepository;
use App\Repositories\LineitemRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\PublishEstimateRepository;
use App\Repositories\TagRepository;
use App\Repositories\TaxRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Image;
use Intervention\Image\Exception\NotReadableException;
use Validator;

class Estimates extends Controller {

    /**
     * The estimate repository instance.
     */
    protected $estimaterepo;

    /**
     * The tags repository instance.
     */
    protected $tagrepo;

    /**
     * The user repository instance.
     */
    protected $userrepo;

    /**
     * The tax repository instance.
     */
    protected $taxrepo;

    /**
     * The line item repository instance.
     */
    protected $lineitemrepo;

    /**
     * The unit repository instance.
     */
    protected $unitrepo;

    /**
     * The event tracking repository instance.
     */
    protected $trackingrepo;

    /**
     * The event repository instance.
     */
    protected $eventrepo;

    /**
     * The emailer repository
     */
    protected $emailerrepo;

    /**
     * The estimate generator repository
     */
    protected $estimategenerator;

    public function __construct(
        EstimateRepository $estimaterepo,
        TagRepository $tagrepo,
        UserRepository $userrepo,
        TaxRepository $taxrepo,
        LineitemRepository $lineitemrepo,
        EventRepository $eventrepo,
        EventTrackingRepository $trackingrepo,
        EmailerRepository $emailerrepo,
        EstimateGeneratorRepository $estimategenerator
    ) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth')->except(
            'showPublic',
            'acceptEstimate',
            'declineEstimate'
        );

        $this->middleware('estimatesMiddlewareIndex')->only([
            'index',
            'update',
            'store',
            'changeCategoryUpdate',
            'attachProjectUpdate',
            'changeStatusUpdate',
            'EditAutomationResponse',
            'updateAutomation',
        ]);

        $this->middleware('estimatesMiddlewareCreate')->only([
            'create',
            'store',
        ]);

        $this->middleware('estimatesMiddlewareEdit')->only([
            'edit',
            'update',
            'emailClient',
            'dettachProject',
            'attachProject',
            'attachProjectUpdate',
            'convertToInvoice',
            'changeStatusUpdate',
            'changeStatus',
            'saveInvoice',
            'changeStatusUpdate',
            'convertToInvoice',
            'convertToInvoiceAction',
            'createClone',
            'storeClone',
            'editAutomation',
            'updateTaxType',
        ]);

        $this->middleware('estimatesMiddlewareShow')->only([
            'show',
            'downloadPDF',
        ]);

        $this->middleware('estimatesMiddlewareShowPublic')->only([
            'showPublic',
            'acceptEstimate',
            'declineEstimate',
        ]);

        $this->middleware('estimatesMiddlewareDestroy')->only(['destroy']);

        //only needed for the [action] methods
        $this->middleware('estimatesMiddlewareBulkEdit')->only(['changeCategoryUpdate']);

        //repos
        $this->estimaterepo = $estimaterepo;
        $this->tagrepo = $tagrepo;
        $this->userrepo = $userrepo;
        $this->lineitemrepo = $lineitemrepo;
        $this->taxrepo = $taxrepo;
        $this->eventrepo = $eventrepo;
        $this->trackingrepo = $trackingrepo;
        $this->emailerrepo = $emailerrepo;
        $this->estimategenerator = $estimategenerator;

        //global settings for use in urls
        config([
            'bill.url_end_point' => 'estimates',
        ]);

    }

    /**
     * Display a listing of estimates
     * @param object ProjectRepository instance of the repository
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectRepository $projectrepo, CategoryRepository $categoryrepo) {

        $projects = [];

        //get estimate
        $estimates = $this->estimaterepo->search();

        //get all categories (type: estimate) - for filter panel
        $categories = $categoryrepo->get('estimate');

        //get all tags (type: lead) - for filter panel
        $tags = $this->tagrepo->getByType('estimate');

        //get clients project list
        if (config('visibility.filter_panel_clients_projects')) {
            if (is_numeric(request('estimateresource_id'))) {
                $projects = $projectrepo->search('', ['project_clientid' => request('estimateresource_id')]);
            }
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('estimates'),
            'estimates' => $estimates,
            'projects' => $projects,
            'stats' => $this->statsWidget(),
            'categories' => $categories,
            'tags' => $tags,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new estimate.
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function create(CategoryRepository $categoryrepo) {

        //estimate categories
        $categories = $categoryrepo->get('estimate');

        //get tags
        $tags = $this->tagrepo->getByType('estimate');

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('create'),
            'categories' => $categories,
            'tags' => $tags,
        ];

        //show the form
        return new CreateResponse($payload);
    }

    /**
     * Store a newly created estimate  in storage.
     * @param object EstimateStoreUpdate
     * @return \Illuminate\Http\Response
     */
    public function store(EstimateStoreUpdate $request, ClientRepository $clientrepo) {

        //are we creating a new client
        if (request('client-selection-type') == 'new') {

            //create client
            if (!$client_id = $clientrepo->create([
                'send_email' => 'yes',
                'return' => 'id',
            ])) {
                abort(409);
            }

            //add client id to request
            request()->merge([
                'bill_clientid' => $client_id,
            ]);
        }

        //create the estimate
        if (!$bill_estimateid = $this->estimaterepo->create()) {
            abort(409);
        }

        //get new estimate
        $estimate = \App\Models\Estimate::Where('bill_estimateid', $bill_estimateid)->first();

        //apply automation
        $this->applyDefaultAutomation($estimate);

        //add tags
        $this->tagrepo->add('estimate', $bill_estimateid);

        //reponse payload
        $payload = [
            'id' => $bill_estimateid,
        ];

        //process reponse
        return new StoreResponse($payload);

    }

    /**
     * some notes
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function applyDefaultAutomation($estimate) {

        //validation
        if (request('automation') != 'on') {
            return;
        }

        //automation setttings
        $settings = \App\Models\Settings2::find(1);

        //apply defaults
        $estimate->estimate_automation_status = $settings->settings2_estimates_automation_default_status;
        $estimate->estimate_automation_create_project = $settings->settings2_estimates_automation_create_project;
        $estimate->estimate_automation_project_status = $settings->settings2_estimates_automation_project_status;
        $estimate->estimate_automation_project_title = $settings->settings2_estimates_automation_project_title;
        $estimate->estimate_automation_project_email_client = $settings->settings2_estimates_automation_project_email_client;
        $estimate->estimate_automation_create_invoice = $settings->settings2_estimates_automation_create_invoice;
        $estimate->estimate_automation_invoice_email_client = $settings->settings2_estimates_automation_invoice_email_client;
        $estimate->estimate_automation_invoice_due_date = $settings->settings2_estimates_automation_invoice_due_date;
        $estimate->estimate_automation_create_tasks = $settings->settings2_estimates_automation_create_tasks;
        $estimate->estimate_automation_copy_attachments = $settings->settings2_estimates_automation_copy_attachments;

        $estimate->save();

        //[automation] assigned users
        $assigned_users = \App\Models\AutomationAssigned::Where('automationassigned_resource_type', 'estimate')
            ->Where('automationassigned_resource_id', 0)
            ->get();

        $assigned = [];
        foreach ($assigned_users as $user_id) {
            $assigned = new \App\Models\AutomationAssigned();
            $assigned->automationassigned_resource_type = 'estimate';
            $assigned->automationassigned_resource_id = $estimate->bill_estimateid;
            $assigned->automationassigned_userid = $user_id;
            $assigned->save();
        }

    }

    /**
     * Display the specified estimate.
     * @param int $id estimate  id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        //get invoice object payload
        if (!$payload = $this->estimategenerator->generate($id)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //append to payload
        $payload['page'] = $this->pageSettings('estimate', $payload['bill']);

        //mark events as read
        \App\Models\EventTracking::where('parent_id', $id)
            ->where('parent_type', 'estimate')
            ->where('eventtracking_userid', auth()->id())
            ->update(['eventtracking_status' => 'read']);

        //if client - marked as opened
        if (auth()->user()->is_client) {
            \App\Models\Estimate::where('bill_estimateid', $id)
                ->update(['bill_viewed_by_client' => 'yes']);
        }

        //custom fields
        $payload['customfields'] = \App\Models\CustomField::Where('customfields_type', 'clients')->get();

        //get estimate files
        $payload['files'] = \App\Models\File::Where('fileresource_type', 'estimate')->Where('fileresource_id', $id)->orderBy('file_filename', 'ASC')->get();

        //pdf estimate
        if (request()->segment(3) == 'pdf') {
            return new PDFResponse($payload);
        }

        //document editing (proposal/contract)
        if (request('estimate_mode') == 'document') {
            return new DocumentEditingResponse($payload);
        }

        //process reponse
        return new ShowResponse($payload);
    }

    /**
     * Display the specified estimate.
     * @param int $id estimate  id
     * @return \Illuminate\Http\Response
     */
    public function showPublic($id) {

        //get estimate
        $estimate = \App\Models\Estimate::Where('bill_uniqueid', $id)->first();

        //set numeric id
        $id = $estimate->bill_estimateid;

        //for logged in clients - redirect to view inside dashboard
        if (auth()->check()) {
            if (request('render') != 'print' && request('action') != 'preview') {
                //skip for downloads
                if (request()->segment(4) != 'pdf') {
                    return redirect("/estimates/$id");
                }
            }
        }

        //get invoice object payload
        if (!$payload = $this->estimategenerator->generate($id)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //append to payload
        $payload['page'] = $this->pageSettings('estimate', $payload['bill']);

        //mark events as read
        if (auth()->check() && auth()->user()->is_client) {
            \App\Models\EventTracking::where('parent_id', $id)
                ->where('parent_type', 'estimate')
                ->where('eventtracking_userid', auth()->id())
                ->update(['eventtracking_status' => 'read']);
        }

        //if client - marked as opened
        if (auth()->check() && auth()->user()->is_client) {
            \App\Models\Estimate::where('bill_estimateid', $id)
                ->update(['bill_viewed_by_client' => 'yes']);
        }

        //custom fields
        $payload['customfields'] = \App\Models\CustomField::Where('customfields_type', 'clients')->get();

        //get estimate files
        $payload['files'] = \App\Models\File::Where('fileresource_type', 'estimate')->Where('fileresource_id', $id)->orderBy('file_filename', 'ASC')->get();

        //pdf estimate
        if (request()->segment(4) == 'pdf') {
            return new PDFResponse($payload);
        }

        //process reponse
        return new ShowPublicResponse($payload);
    }

    /**
     * save estimate changes, when an ioice is being edited
     * @param object EstimateSave
     * @return \Illuminate\Http\Response
     */
    public function saveEstimate(EstimateSave $request, $id) {

        //get the estimate
        $estimates = $this->estimaterepo->search($id);
        $estimate = $estimates->first();

        //save each line item in the database
        $this->estimaterepo->saveLineItems($id);

        //update taxes - (summary taxes)
        if ($estimate->bill_tax_type == 'summary') {
            $this->updateEstimateTax($id);
        }

        //update other estimate attributes
        $this->estimaterepo->updateEstimate($id);

        //reponse payload
        $payload = [
            'estimate' => $estimate,
        ];

        //saving from proposal or contract page
        if (request('estimate_mode') == 'document') {
            return response()->json(array(
                'notification' => [
                    'type' => 'success',
                    'value' => __('lang.request_has_been_completed'),
                ],
                'skip_dom_reset' => true,
            ));
        }

        //response
        return new SaveResponse($payload);

    }

    /**
     * update the tax for an estimate
     * (1) delete existing estimate taxes
     * (2) for summary taxes - save new taxes
     * @param int $bill_estimateid
     * @return \Illuminate\Http\Response
     */
    private function updateEstimateTax($bill_estimateid = '') {

        //delete current estimate taxes
        \App\Models\Tax::Where('taxresource_type', 'estimate')
            ->where('taxresource_id', $bill_estimateid)
            ->delete();

        //save taxes [summary taxes]
        if (is_array(request('bill_logic_taxes'))) {
            foreach (request('bill_logic_taxes') as $tax) {
                //get data elements
                $list = explode('|', $tax);
                $data = [
                    'tax_taxrateid' => $list[2],
                    'tax_name' => $list[1],
                    'tax_rate' => $list[0],
                    'taxresource_type' => 'estimate',
                    'taxresource_id' => $bill_estimateid,
                ];
                $this->taxrepo->create($data);
            }
        }

    }

    /**
     * publish an estimate
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function publishEstimate(PublishEstimateRepository $publishrepo, $id) {

        //generate the invoice
        $result = $publishrepo->publishEstimate($id);

        //error processing
        if (!$result['status']) {
            abort(409, $result['message']);
        }

        //reponse payload
        $payload = [
            'estimate' => $result['estimate'],
        ];

        //response
        return new PublishResponse($payload);
    }

        /**
     * schedule an estimate for publising later
     * @param int $id estimate id
     * @return \Illuminate\Http\Response
     */
    public function publishScheduledEstimate($id) {

        //does the estimate exist
        if (!$estimate = \App\Models\Estimate::Where('bill_estimateid', $id)->first()) {
            abort(404);
        }

        //custom error messages
        $messages = [
            'publishing_option_date.required' => __('lang.schedule_date') . '-' . __('lang.is_required'),
        ];

        //validate
        $validator = Validator::make(request()->all(), [
            'publishing_option_date' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (strtotime($value) < strtotime(now()->toDateString())) {
                        return $fail(__('lang.schedule_date_cannot_be_past'));
                    }
                },
            ],
        ], $messages);

        //errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }
            //redirect and show error (to make show the publish dropdown works again)
            request()->session()->flash('error-notification', __('lang.error') . ': ' . $messages);
            $jsondata['redirect_url'] = url("/estimates/$id");
            return response()->json($jsondata);
        }

        //secdule the estimate
        $estimate->bill_publishing_type = 'scheduled';
        $estimate->bill_publishing_scheduled_date = request('publishing_option_date');
        $estimate->bill_publishing_scheduled_status = 'pending';
        $estimate->bill_publishing_scheduled_log = '';
        $estimate->save();

        //reponse payload
        $payload = [
            'id' => $id,
        ];

        //response
        return new PublishScheduledResponse($payload);
    }

    /**
     * publish a revised estimate
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function publishRevisedEstimate($id) {

        //generate the invoice
        if (!$payload = $this->estimategenerator->generate($id)) {
            abort(409, __('lang.error_loading_item'));
        }

        //estimate
        $estimate = $payload['bill'];

        //validate current status
        if ($estimate->bill_status != 'declined') {
            abort(409, __('lang.action_only_available_on_declined_estimates'));
        }

        //check if estimate is not already expired
        $bill_expiry_date = \Carbon\Carbon::parse($estimate->bill_expiry_date);
        if ($bill_expiry_date->diffInDays(today(), false) > 0) {
            abort(409, __('lang.estimate_has_expired_update_date'));
        }

        /** ----------------------------------------------
         * record event [comment]
         * ----------------------------------------------*/
        $data = [
            'event_creatorid' => auth()->id(),
            'event_item' => 'estimate',
            'event_item_id' => $estimate->bill_estimateid,
            'event_item_lang' => 'event_revised_estimate',
            'event_item_content' => __('lang.estimate') . ' - ' . $estimate->formatted_bill_estimateid,
            'event_item_content2' => '',
            'event_parent_type' => 'estimate',
            'event_parent_id' => $estimate->bill_estimateid,
            'event_parent_title' => $estimate->project_title,
            'event_clientid' => $estimate->bill_clientid,
            'event_show_item' => 'yes',
            'event_show_in_timeline' => 'yes',
            'eventresource_type' => (is_numeric($estimate->bill_projectid)) ? 'project' : 'client',
            'eventresource_id' => (is_numeric($estimate->bill_projectid)) ? $estimate->bill_projectid : $estimate->bill_clientid,
            'event_notification_category' => 'notifications_billing_activity',

        ];
        //record event
        if ($event_id = $this->eventrepo->create($data)) {
            //get users (main client)
            $users = $this->userrepo->getClientUsers($estimate->bill_clientid, 'owner', 'ids');
            //record notification
            $emailusers = $this->trackingrepo->recordEvent($data, $users, $event_id);
        }

        /** ----------------------------------------------
         * send email [queued]
         * ----------------------------------------------*/
        if (isset($emailusers) && is_array($emailusers)) {
            //send to users
            if ($users = \App\Models\User::WhereIn('id', $emailusers)->get()) {
                foreach ($users as $user) {
                    $mail = new \App\Mail\PublishRevisedEstimate($user, [], $estimate);
                    $mail->build();
                }
            }
        }

        //update estimate status
        \App\Models\Estimate::where('bill_estimateid', $estimate->bill_estimateid)
            ->update(['bill_status' => 'revised']);

        //response
        return new PublishRevisedResponse();
    }

    /**
     * resend an estimate via email
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function resendEstimate($id) {

        //generate the estimate
        if (!$payload = $this->estimategenerator->generate($id)) {
            abort(409, __('lang.error_loading_item'));
        }

        //estimate
        $estimate = $payload['bill'];

        //validate current status
        if ($estimate->bill_status == 'draft') {
            abort(409, __('lang.estimate_still_draft'));
        }

        /** ----------------------------------------------
         * send email [queued]
         * ----------------------------------------------*/
        $users = $this->userrepo->getClientUsers($estimate->bill_clientid, 'owner', 'collection');
        foreach ($users as $user) {
            $mail = new \App\Mail\PublishEstimate($user, [], $estimate);
            $mail->build();
        }

        //response
        return new ResendResponse();
    }

    /**
     * customer accepting estimate
     * @param int $id estimate id
     * @return \Illuminate\Http\Response
     */
    public function acceptEstimate(EstimateAutomationRepository $automationrepo, $id) {

        //get estimate
        $estimate = \App\Models\Estimate::Where('bill_uniqueid', $id)->first();

        //set numeric id
        $id = $estimate->bill_estimateid;

        //generate the estimate
        if (!$payload = $this->estimategenerator->generate($id)) {
            abort(409, __('lang.error_loading_item'));
        }

        //estimate
        $estimate = $payload['bill'];

        //validate current status
        if (!in_array($estimate->bill_status, ['new', 'revised'])) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //update estimate status
        \App\Models\Estimate::where('bill_estimateid', $estimate->bill_estimateid)
            ->update(['bill_status' => 'accepted']);

        //set accepted by main user
        $user = \App\Models\User::Where('clientid', $estimate->bill_clientid)->Where('account_owner', 'yes')->first();

        /** ----------------------------------------------
         * record event [comment]
         * see database table to details of each key
         * ----------------------------------------------*/
        $data = [
            'event_creatorid' => (auth()->check()) ? auth()->id() : $user->id,
            'event_item' => 'estimate',
            'event_item_id' => $estimate->bill_estimateid,
            'event_item_lang' => 'event_accepted_estimate',
            'event_item_content' => __('lang.estimate') . ' - ' . $estimate->formatted_bill_estimateid,
            'event_item_content2' => '',
            'event_clientid' => $estimate->bill_clientid,
            'event_parent_type' => 'estimate',
            'event_parent_id' => $estimate->bill_estimateid,
            'event_parent_title' => $estimate->project_title,
            'event_clientid' => $estimate->bill_clientid,
            'event_show_item' => 'yes',
            'event_show_in_timeline' => 'yes',
            'eventresource_type' => (is_numeric($estimate->bill_projectid)) ? 'project' : 'client',
            'eventresource_id' => (is_numeric($estimate->bill_projectid)) ? $estimate->bill_projectid : $estimate->bill_clientid,
            'event_notification_category' => 'notifications_billing_activity',
        ];
        //record event
        if ($event_id = $this->eventrepo->create($data)) {
            //get estimate team users, with billing app notifications enabled
            $users = $this->userrepo->mailingListTeamEstimates('app');
            //record notification
            $this->trackingrepo->recordEvent($data, $users, $event_id);
        }

        /** --------------------------------------------------------
         * send email [queued]
         * - estimate users, with biling email preference enabled
         * --------------------------------------------------------*/
        $users = $this->userrepo->mailingListTeamEstimates('email');
        foreach ($users as $user) {
            $mail = new \App\Mail\AcceptEstimate($user, [], $estimate);
            $mail->build();
        }

        /** --------------------------------------------------------
         * [automation] - estimate accepted
         * --------------------------------------------------------*/
        $automationrepo->process($estimate);

        //response
        return new AcceptResponse();
    }

    /**
     * customer declining an estimate
     * @param int $id estimate id
     * @return \Illuminate\Http\Response
     */
    public function declineEstimate($id) {

        //get estimate
        $estimate = \App\Models\Estimate::Where('bill_uniqueid', $id)->first();

        //set numeric id
        $id = $estimate->bill_estimateid;

        //generate the estimate
        if (!$payload = $this->estimategenerator->generate($id)) {
            abort(409, __('lang.error_loading_item'));
        }

        //estimate
        $estimate = $payload['bill'];

        //validate current status
        if (!in_array($estimate->bill_status, ['new', 'revised'])) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //update estimate status
        \App\Models\Estimate::where('bill_estimateid', $estimate->bill_estimateid)
            ->update(['bill_status' => 'declined']);

        //set accepted by main user
        $user = \App\Models\User::Where('clientid', $estimate->bill_clientid)->Where('account_owner', 'yes')->first();

        /** ----------------------------------------------
         * record event [comment]
         * see database table to details of each key
         * ----------------------------------------------*/
        $data = [
            'event_creatorid' => (auth()->check()) ? auth()->id() : $user->id,
            'event_item' => 'estimate',
            'event_item_id' => $estimate->bill_estimateid,
            'event_item_lang' => 'event_declined_estimate',
            'event_item_content' => __('lang.estimate') . ' - ' . $estimate->formatted_bill_estimateid,
            'event_item_content2' => '',
            'event_clientid' => $estimate->bill_clientid,
            'event_parent_type' => 'estimate',
            'event_parent_id' => $estimate->bill_estimateid,
            'event_parent_title' => $estimate->project_title,
            'event_clientid' => $estimate->bill_clientid,
            'event_show_item' => 'yes',
            'event_show_in_timeline' => 'yes',
            'eventresource_type' => (is_numeric($estimate->bill_projectid)) ? 'project' : 'client',
            'eventresource_id' => (is_numeric($estimate->bill_projectid)) ? $estimate->bill_projectid : $estimate->bill_clientid,
            'event_notification_category' => 'notifications_billing_activity',
        ];
        //record event
        if ($event_id = $this->eventrepo->create($data)) {
            //get estimate team users, with billing app notifications enabled
            $users = $this->userrepo->mailingListTeamEstimates('app');
            //record notification
            $this->trackingrepo->recordEvent($data, $users, $event_id);
        }

        /** --------------------------------------------------------
         * send email [queued]
         * - estimate users, with biling email preference enabled
         * --------------------------------------------------------*/
        $users = $this->userrepo->mailingListTeamEstimates('email');
        foreach ($users as $user) {
            $mail = new \App\Mail\DeclineEstimate($user, [], $estimate);
            $mail->build();
        }

        //response
        return new DeclineResponse();
    }

    /**
     * Show the form for editing the specified estimate.
     * @param object CategoryRepository instance of the repository
     * @param int $id estimate  id
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryRepository $categoryrepo, $id) {

        //get the project
        $estimate = $this->estimaterepo->search($id);

        //client categories
        $categories = $categoryrepo->get('estimate');

        //get tags
        $tags_resource = $this->tagrepo->getByResource('estimate', $id);
        $tags_user = $this->tagrepo->getByType('estimate');
        $tags = $tags_resource->merge($tags_user);
        $tags = $tags->unique('tag_title');

        //not found
        if (!$estimate = $estimate->first()) {
            abort(409, __('lang.estimate_not_found'));
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
            'estimate' => $estimate,
            'categories' => $categories,
            'tags' => $tags,
        ];

        //response
        return new EditResponse($payload);
    }

    /**
     * Update the specified estimate in storage.
     * @param int $id estimate  id
     * @return \Illuminate\Http\Response
     */
    public function update($id) {
        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'bill_date' => 'required|date',
            'bill_expiry_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value != '' && request('bill_date') != '' && (strtotime($value) < strtotime(request('bill_date')))) {
                        return $fail(__('lang.expiry_date_must_be_after_estimate_date'));
                    }
                }],
            'bill_categoryid' => [
                'required',
                Rule::exists('categories', 'category_id'),
            ],
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

        //update
        if (!$this->estimaterepo->update($id)) {
            abort(409);
        }

        //delete & update tags
        $this->tagrepo->delete('estimate', $id);
        $this->tagrepo->add('estimate', $id);

        //get project
        $estimates = $this->estimaterepo->search($id);

        //reponse payload
        $payload = [
            'estimates' => $estimates,
            'stats' => $this->statsWidget(),
        ];

        //generate a response
        return new UpdateResponse($payload);

    }

    /**
     * Remove the specified estimate from storage.
     * @param object DestroyRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRepository $destroyrepo) {

        //delete each record in the array
        $allrows = array();
        foreach (request('ids') as $id => $value) {
            //only checked items
            if ($value == 'on') {
                //destroy estimate
                $destroyrepo->destroyEstimate($id);
                //add to array
                $allrows[] = $id;
            }
        }
        //reponse payload
        $payload = [
            'allrows' => $allrows,
            'stats' => $this->statsWidget(),
        ];

        //generate a response
        return new DestroyResponse($payload);
    }

    /**
     * Show the form for changing estimate category
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function changeCategory(CategoryRepository $categoryrepo) {

        //get all estimate categories
        $categories = $categoryrepo->get('estimate');

        //reponse payload
        $payload = [
            'categories' => $categories,
        ];

        //show the form
        return new ChangeCategoryResponse($payload);
    }

    /**
     * update the estimate category
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function changeCategoryUpdate(CategoryRepository $categoryrepo) {

        //validate the category exists
        if (!\App\Models\Category::Where('category_id', request('category'))
            ->Where('category_type', 'estimate')
            ->first()) {
            abort(409, __('lang.item_not_found'));
        }

        //update each estimate
        $allrows = array();
        foreach (request('ids') as $bill_estimateid => $value) {
            if ($value == 'on') {
                $estimate = \App\Models\Estimate::Where('bill_estimateid', $bill_estimateid)->first();
                //update the category
                $estimate->bill_categoryid = request('category');
                $estimate->save();
                //get the estimate in rendering friendly format
                $estimates = $this->estimaterepo->search($bill_estimateid);
                //add to array
                $allrows[] = $estimates;
            }
        }

        //reponse payload
        $payload = [
            'allrows' => $allrows,
        ];

        //show the form
        return new ChangeCategoryUpdateResponse($payload);
    }

    /**
     * Show the form for changing estimate category
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function convertToInvoice($id) {

        //reponse payload
        $payload = [
            'estimate_id' => $id,
        ];

        //show the form
        return new ConvertToEstimate($payload);
    }

    /**
     * convert the estimate into an invoice
     * @return \Illuminate\Http\Response
     */
    public function convertToInvoiceAction(DestroyRepository $destroyrepo, $id) {

        //convert to invoice
        $invoice = $this->estimaterepo->convertEstimateToInvoice($id);

        //update invoice
        $invoice->bill_date = request('bill_date');
        $invoice->bill_due_date = request('bill_date');
        $invoice->bill_creatorid = auth()->id();
        $invoice->bill_uniqueid = str_unique();
        $invoice->save();

        //delete original estimate
        if (request('delete_original_estimate') == 'on') {
            $destroyrepo->destroyEstimate($id);
        }

        //redirect to new invoice
        $jsondata = [];
        $jsondata['redirect_url'] = url("/invoices/" . $invoice->bill_invoiceid);
        return response()->json($jsondata);
    }

    /**
     * Show the form for changing an estimate status
     * @return \Illuminate\Http\Response
     */
    public function changeStatus() {

        //get the estimate
        $estimate = \App\Models\Estimate::Where('bill_estimateid', request()->route('estimate'))->first();

        //reponse payload
        $payload = [
            'estimate' => $estimate,
        ];

        //show the form
        return new ChangeStatusResponse($payload);
    }

    /**
     * change estimate status
     * @return \Illuminate\Http\Response
     */
    public function changeStatusUpdate(EstimateAutomationRepository $automationrepo) {

        //validate the estimate exists
        $estimate = \App\Models\Estimate::Where('bill_estimateid', request()->route('estimate'))->first();

        //current status
        $initial_status = $estimate->bill_status;

        //if revsed - mark as unread
        if (request('bill_status') == 'revised' || request('bill_status') == 'new') {
            \App\Models\Estimate::where('bill_estimateid', request()->route('estimate'))
                ->update(['bill_viewed_by_client' => 'no']);
        }

        //update the estimate
        $estimate->bill_status = request('bill_status');
        $estimate->save();

        //get refreshed estimate
        $estimates = $this->estimaterepo->search(request()->route('estimate'));
        $estimate = $estimates->first();

        //manually run the automation (for accepted estimates)
        if ($initial_status != $estimate->bill_status && $estimate->bill_status == 'accepted') {
            $automationrepo->process($estimate);
        }

        //reponse payload
        $payload = [
            'estimates' => $estimates,
            'bill_estimateid' => request()->route('estimate'),
            'stats' => $this->statsWidget(),
        ];

        //show the form
        return new UpdateResponse($payload);
    }

    /**
     * Show the form for attaching a project to an estimate
     * @return \Illuminate\Http\Response
     */
    public function attachProject() {

        //get client id
        $client_id = request('client_id');

        //reponse payload
        $payload = [
            'projects_feed_url' => url("/feed/projects?ref=clients_projects&client_id=$client_id"),
        ];

        //show the form
        return new AttachProjectResponse($payload);
    }

    /**
     * attach a project to an estimate
     * @return \Illuminate\Http\Response
     */
    public function attachProjectUpdate() {

        //validate the estimate exists
        $estimate = \App\Models\estimate::Where('bill_estimateid', request()->route('estimate'))->first();

        //validate the project exists
        if (!$project = \App\Models\Project::Where('project_id', request('attach_project_id'))->first()) {
            abort(409, __('lang.item_not_found'));
        }

        //update the estimate
        $estimate->bill_projectid = request('attach_project_id');
        $estimate->bill_clientid = $project->project_clientid;
        $estimate->save();

        //get refreshed estimate
        $estimates = $this->estimaterepo->search(request()->route('estimate'));
        $estimate = $estimates->first();

        //refresh estimate
        $this->estimaterepo->refreshestimate($estimate);

        //reponse payload
        $payload = [
            'estimates' => $estimates,
        ];

        //show the form
        return new UpdateResponse($payload);
    }

    /**
     * dettach estimate from a project
     * @return \Illuminate\Http\Response
     */
    public function dettachProject() {

        //validate the estimate exists
        $estimate = \App\Models\estimate::Where('bill_estimateid', request()->route('estimate'))->first();

        //update the estimate
        $estimate->bill_projectid = null;
        $estimate->save();

        //get refreshed estimate
        $estimates = $this->estimaterepo->search(request()->route('estimate'));

        //reponse payload
        $payload = [
            'estimates' => $estimates,
        ];

        //show the form
        return new UpdateResponse($payload);
    }

    /**
     * show the form for cloning an estimate
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function createClone(CategoryRepository $categoryrepo, $id) {

        //get the estimate
        $estimate = \App\Models\estimate::Where('bill_estimateid', $id)->first();

        //get tags
        $tags = $this->tagrepo->getByType('estimate');

        //estimate categories
        $categories = $categoryrepo->get('estimate');

        //reponse payload
        $payload = [
            'estimate' => $estimate,
            'tags' => $tags,
            'categories' => $categories,
        ];

        //show the form
        return new CreateCloneResponse($payload);
    }

    /**
     * show the form for cloning an estimate
     * @param object EstimateClone instance of the request validation
     * @param object CloneEstimateRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function storeClone(EstimateClone $request, CloneEstimateRepository $cloneestimaterepo, $id) {

        //get the estimate
        $estimate = \App\Models\Estimate::Where('bill_estimateid', $id)->first();

        //clone data
        $data = [
            'estimate_id' => $id,
            'client_id' => request('bill_clientid'),
            'project_id' => request('bill_projectid'),
            'estimate_date' => request('bill_date'),
            'return' => 'id',
        ];

        //clone estimate
        if (!$estimate_id = $cloneestimaterepo->clone($data)) {
            abort(409, __('lang.cloning_failed'));
        }

        //reponse payload
        $payload = [
            'id' => $estimate_id,
        ];

        //show the form
        return new StoreCloneResponse($payload);
    }

    /**
     * Show the form for editing estimate automation
     * @param int $id estimate  id
     * @return \Illuminate\Http\Response
     */
    public function editAutomation($id) {

        //get the project
        $estimate = $this->estimaterepo->search($id);

        //not found
        if (!$estimate = $estimate->first()) {
            abort(409, __('lang.estimate_not_found'));
        }

        //save to standard array
        $automation = [
            'estimate_automation_default_status' => $estimate->estimate_automation_status,
            'estimate_automation_create_project' => $estimate->estimate_automation_create_project,
            'estimate_automation_project_status' => $estimate->estimate_automation_project_status,
            'estimate_automation_project_title' => $estimate->estimate_automation_project_title,
            'estimate_automation_project_email_client' => $estimate->estimate_automation_project_email_client,
            'estimate_automation_create_invoice' => $estimate->estimate_automation_create_invoice,
            'estimate_automation_invoice_email_client' => $estimate->estimate_automation_invoice_email_client,
            'estimate_automation_invoice_due_date' => $estimate->estimate_automation_invoice_due_date,
            'estimate_automation_create_tasks' => $estimate->estimate_automation_create_tasks,
            'estimate_automation_copy_attachments' => $estimate->estimate_automation_copy_attachments,

        ];

        //[automation] assigned users
        $assigned_users = \App\Models\AutomationAssigned::Where('automationassigned_resource_type', 'estimate')
            ->Where('automationassigned_resource_id', $id)
            ->get();

        $assigned = [];
        foreach ($assigned_users as $user) {
            $assigned[] = $user->automationassigned_userid;
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
            'estimate' => $estimate,
            'automation' => $automation,
            'assigned' => $assigned,
        ];

        //response
        return new EditAutomationResponse($payload);
    }

    /**
     * Update estiate automation
     * @param int $id estimate  id
     * @return \Illuminate\Http\Response
     */
    public function updateAutomation(EstimateUpdateAutomation $request, $id) {

        //get the project
        $estimate = \App\Models\Estimate::Where('bill_estimateid', $id)->first();

        //update settings
        $estimate->estimate_automation_status = request('estimate_automation_status');
        $estimate->estimate_automation_create_project = (request('estimate_automation_create_project') == 'on') ? 'yes' : 'no';
        $estimate->estimate_automation_project_title = request('estimate_automation_project_title');
        $estimate->estimate_automation_project_status = request('estimate_automation_project_status');
        $estimate->estimate_automation_create_tasks = (request('estimate_automation_create_tasks') == 'on') ? 'yes' : 'no';
        $estimate->estimate_automation_project_email_client = (request('estimate_automation_project_email_client') == 'on') ? 'yes' : 'no';
        $estimate->estimate_automation_create_invoice = (request('estimate_automation_create_invoice') == 'on') ? 'yes' : 'no';
        $estimate->estimate_automation_invoice_due_date = request('estimate_automation_invoice_due_date');
        $estimate->estimate_automation_invoice_email_client = (request('estimate_automation_invoice_email_client') == 'on') ? 'yes' : 'no';
        $estimate->estimate_automation_copy_attachments = (request('estimate_automation_copy_attachments') == 'on') ? 'yes' : 'no';
        $estimate->save();

        //additional settings
        if (request('estimate_automation_status') == 'disabled') {
            $estimate->estimate_automation_create_project = 'no';
            $estimate->estimate_automation_project_title = '';
            $estimate->estimate_automation_project_status = 'not_started';
            $estimate->estimate_automation_create_tasks = 'no';
            $estimate->estimate_automation_project_email_client = 'no';
            $estimate->estimate_automation_create_invoice = 'no';
            $estimate->estimate_automation_invoice_due_date = null;
            $estimate->estimate_automation_invoice_email_client = 'no';
            $estimate->estimate_automation_copy_attachments = 'no';
            $estimate->save();
        }

        //assigned users (reset)
        \App\Models\AutomationAssigned::Where('automationassigned_resource_type', 'estimate')
            ->Where('automationassigned_resource_id', $id)
            ->delete();

        //assigned add (reset)
        if (request('estimate_automation_status') == 'enabled' && is_array(request('estimate_automation_assigned_users'))) {
            foreach (request('estimate_automation_assigned_users') as $user_id) {
                $assigned = new \App\Models\AutomationAssigned();
                $assigned->automationassigned_resource_type = 'estimate';
                $assigned->automationassigned_resource_id = $id;
                $assigned->automationassigned_userid = $user_id;
                $assigned->save();
            }
        }

        //get table friendly format
        $estimates = $this->estimaterepo->search($estimate->bill_estimateid);
        $estimate = $estimates->first();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('estimates'),
            'estimate' => $estimate,
            'estimates' => $estimates,
            'tags' => $this->tagrepo->getByType('estimate'),
        ];

        //response
        return new UpdateAutomationResponse($payload);
    }

    /**
     * save an uploaded file
     * @param object Request instance of the request object
     * @param object AttachmentRepository instance of the repository
     * @param int $id task id
     */
    public function attachFiles(Request $request, FileRepository $filerepo, $id) {

        //get the estimate
        $estimate = \App\Models\Estimate::Where('bill_estimateid', $id)->first();

        //save the file in its own folder in the temp folder
        if ($file = $request->file('file')) {

            //defaults
            $file_type = 'file';

            //unique file id & directory name
            $uniqueid = Str::random(40);
            $directory = $uniqueid;

            //original file name
            $filename = $file->getClientOriginalName();

            $filesize = $file->getSize();

            //filepath
            $file_path = BASE_DIR . "/storage/files/$directory/$filename";

            //extension
            $extension = pathinfo($file_path, PATHINFO_EXTENSION);

            //thumb path
            $thumb_name = generateThumbnailName($filename);
            $thumb_path = BASE_DIR . "/storage/files/$directory/$thumb_name";

            //create directory
            Storage::makeDirectory("files/$directory");

            //save file to directory
            Storage::putFileAs("files/$directory", $file, $filename);

            //if the file type is an image, create a thumb by default
            if (is_array(@getimagesize($file_path))) {
                $file_type = 'image';
                try {
                    $img = Image::make($file_path)->resize(null, 90, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->save($thumb_path);
                } catch (NotReadableException $e) {
                    $message = $e->getMessage();
                    Log::error("[Image Library] failed to create uplaoded image thumbnail. Image type is not supported on this server", ['process' => '[permissions]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'error_message' => $message]);
                    abort(409, __('lang.image_file_type_not_supported'));
                }
            }

            //save the file
            $file = new \App\Models\File();
            $file->file_creatorid = auth()->id();
            $file->file_clientid = null;
            $file->file_uniqueid = $uniqueid;
            $file->file_upload_unique_key = Str::random(50);
            $file->file_directory = $directory;
            $file->file_filename = $filename;
            $file->file_extension = $extension;
            $file->file_type = $file_type;
            $file->file_size = $filesize;
            $file->file_thumbname = $thumb_name;
            $file->fileresource_type = 'estimate';
            $file->fileresource_id = $id;
            $file->file_visibility_client = 'yes';
            $file->save();

            config(['visibility.bill_attachments_delete_button' => true]);

            //reponse payload
            $payload = [
                'file' => $file,
            ];

            //show the form
            return new AttachFilesResponse($payload);
        }
    }

    /**
     * delete a record
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteFile() {

        //get the record
        if (!$file = \App\Models\File::Where('file_uniqueid', request('file_uniqueid'))->first()) {
            abort(403);
        }

        //confirm thumb exists
        if ($file->file_directory != '') {
            if (Storage::exists("files/$file->file_directory")) {
                Storage::deleteDirectory("files/$file->file_directory");
            }
        }

        $file->delete();
    }

    /**
     * update the tax type
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateTaxType($id) {

        //check if file exists in the database
        $estimate = \App\Models\Estimate::Where('bill_estimateid', $id)->first();

        //validation
        if (!in_array(request('bill_tax_type'), ['summary', 'inline'])) {
            abort(409, __('lang.invalid_tax_type'));
        }

        //we have made a change -reset all taxes
        if (request('bill_tax_type') != $estimate->bill_tax_type) {
            //delete all taxes
            \App\Models\Tax::Where('taxresource_type', 'estimate')->Where('taxresource_id', $id)->delete();
            $bill_tax_total_amount = $estimate->bill_tax_total_amount;
            //recalculate bill
            $estimate->bill_tax_total_percentage = null;
            $estimate->bill_tax_total_amount = null;
            $estimate->bill_final_amount = $estimate->bill_final_amount - $bill_tax_total_amount;
            $estimate->bill_tax_type = request('bill_tax_type');
            $estimate->save();

            //if the new tax_type is 'inline', create a '0-rated' taxe for each lineitem
            if (request('bill_tax_type') == 'inline') {
                //get the zero-rate tax
                if ($zero_rate_tax = \App\Models\TaxRate::Where('taxrate_uniqueid', 'zero-rated-tax-rate')->first()) {
                    //get all line items
                    if ($lineitems = \App\Models\Lineitem::Where('lineitemresource_type', 'estimate')->Where('lineitemresource_id', $id)->get()) {
                        foreach ($lineitems as $lineitem) {
                            $tax = new \App\Models\Tax();
                            $tax->tax_taxrateid = 'zero-rated-tax-rate';
                            $tax->tax_name = $zero_rate_tax->tax_name;
                            $tax->tax_rate = 0;
                            $tax->tax_type = 'inline';
                            $tax->tax_lineitem_id = $lineitem->lineitem_id;
                            $tax->taxresource_type = 'estimate';
                            $tax->taxresource_id = $id;
                            $tax->save();
                        }
                    }
                }
            }
        }

        //reponse payload
        $payload = [
            'bill_id' => $id,
        ];

        //show the form
        return new UpdateTaxtypeResponse($payload);

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
                __('lang.sales'),
                __('lang.estimates'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'estimates',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_estimates' => 'active',
            'mainmenu_sales' => 'active',
            'mainmenu_client_billing' => 'active',
            'submenu_estimates' => 'active',
            'sidepanel_id' => 'sidepanel-filter-estimates',
            'dynamic_search_url' => url('estimates/search?action=search&estimateresource_id=' . request('estimateresource_id') . '&estimateresource_type=' . request('estimateresource_type')),
            'add_button_classes' => 'add-edit-estimate-button',
            'load_more_button_route' => 'estimates',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => __('lang.add_estimate'),
            'add_modal_create_url' => url('estimates/create?estimateresource_id=' . request('estimateresource_id') . '&estimateresource_type=' . request('estimateresource_type')),
            'add_modal_action_url' => url('estimates?estimateresource_id=' . request('estimateresource_id') . '&estimateresource_type=' . request('estimateresource_type')),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //estimates list page
        if ($section == 'estimates') {
            $page += [
                'meta_title' => __('lang.estimates'),
                'heading' => __('lang.estimates'),
                'sidepanel_id' => 'sidepanel-filter-estimates',
            ];
            if (request('source') == 'ext') {
                $page += [
                    'list_page_actions_size' => 'col-lg-12',
                ];
            }
            return $page;
        }

        //estimate page
        if ($section == 'estimate') {
            //adjust
            $page['page'] = 'estimate';
            //add
            $page += [
                'crumbs' => [
                    __('lang.estimates'),
                ],
                'meta_title' => __('lang.estimate') . ' #' . $data->formatted_bill_estimateid,
                'heading' => __('lang.project') . ' - ' . $data->project_title,
                'bill_estimateid' => request()->segment(2),
                'source_for_filter_panels' => 'ext',
                'section' => 'overview',
            ];

            $page['crumbs'] = [
                __('lang.sales'),
                __('lang.estimates'),
                $data['formatted_bill_estimateid'],
            ];

            return $page;
        }

        //create new resource
        if ($section == 'create') {
            $page += [
                'section' => 'create',
            ];
            return $page;
        }

        //edit new resource
        if ($section == 'edit') {
            $page['mode'] = 'editing';
            $page += [
                'section' => 'edit',
            ];
            return $page;
        }

        //return
        return $page;
    }

    /**
     * data for the stats widget
     * @return array
     */
    private function statsWidget($data = array()) {

        //stats
        $count_new = $this->estimaterepo->search('', ['stats' => 'count-new']);
        $count_accepted = $this->estimaterepo->search('', ['stats' => 'count-accepted']);
        $count_declined = $this->estimaterepo->search('', ['stats' => 'count-declined']);
        $count_expired = $this->estimaterepo->search('', ['stats' => 'count-expired']);

        $sum_new = $this->estimaterepo->search('', ['stats' => 'sum-new']);
        $sum_accepted = $this->estimaterepo->search('', ['stats' => 'sum-accepted']);
        $sum_declined = $this->estimaterepo->search('', ['stats' => 'sum-declined']);
        $sum_expired = $this->estimaterepo->search('', ['stats' => 'sum-expired']);

        //default values
        $stats = [
            [
                'value' => runtimeMoneyFormat($sum_new),
                'title' => __('lang.pending') . " ($count_new)",
                'percentage' => '100%',
                'color' => 'bg-info',
            ],
            [
                'value' => runtimeMoneyFormat($sum_accepted),
                'title' => __('lang.accepted') . " ($count_accepted)",
                'percentage' => '100%',
                'color' => 'bg-success',
            ],
            [
                'value' => runtimeMoneyFormat($sum_expired),
                'title' => __('lang.expired') . " ($count_expired)",
                'percentage' => '100%',
                'color' => 'bg-warning',
            ],
            [
                'value' => runtimeMoneyFormat($sum_declined),
                'title' => __('lang.declined') . " ($count_declined)",
                'percentage' => '100%',
                'color' => 'bg-danger',
            ],
        ];
        //return
        return $stats;
    }
}