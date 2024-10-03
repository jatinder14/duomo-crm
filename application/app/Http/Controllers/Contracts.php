<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for contract contracts
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contracts\StoreUpdate;
use App\Http\Responses\Common\ChangeCategoryResponse;
use App\Http\Responses\Contracts\AttachProjectResponse;
use App\Http\Responses\Contracts\ChangeCategoryUpdateResponse;
use App\Http\Responses\Contracts\ChangeStatusResponse;
use App\Http\Responses\Contracts\CreateResponse;
use App\Http\Responses\Contracts\DestroyResponse;
use App\Http\Responses\Contracts\EmailResponse;
use App\Http\Responses\Contracts\IndexResponse;
use App\Http\Responses\Contracts\PublishResponse;
use App\Http\Responses\Contracts\SignatureResponse;
use App\Http\Responses\Contracts\StoreResponse;
use App\Http\Responses\Documents\ShowEditResponse;
use App\Http\Responses\Documents\ShowPreviewResponse;
use App\Http\Responses\Contracts\CreateCloneResponse;
use App\Models\Category;
use App\Models\Contract;
use App\Repositories\CategoryRepository;
use App\Repositories\ContractRepository;
use App\Repositories\EmailerRepository;
use App\Repositories\EstimateGeneratorRepository;
use App\Repositories\EstimateRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventTrackingRepository;
use App\Repositories\UserRepository;
use App\Repositories\CloneContractRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;
use Intervention\Image\Exception\NotReadableException;
use Validator;

class Contracts extends Controller {

    /**
     * The repository instances.
     */
    protected $contractrepo;
    protected $userrepo;
    protected $estimaterepo;
    protected $eventrepo;
    protected $trackingrepo;
    protected $emailerrepo;

    public function __construct(
        ContractRepository $contractrepo,
        UserRepository $userrepo,
        EstimateRepository $estimaterepo,
        EventRepository $eventrepo,
        EventTrackingRepository $trackingrepo,
        EmailerRepository $emailerrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth')->except(
            'showPublic',
            'sign',
        );

        $this->middleware('contractsMiddlewareIndex')->only([
            'index',
            'update',
            'store',
            'changeCategoryUpdate',
            'changeStatus',
        ]);

        $this->middleware('contractsMiddlewareEdit')->only([
            'editingContract',
            'update',
            'resendEmail',
            'publish',
            'changeStatus',
        ]);

        $this->middleware('contractsMiddlewareCreate')->only([
            'create',
            'store',
        ]);

        $this->middleware('contractsMiddlewareShow')->only([
            'show',
        ]);

        $this->middleware('contractsMiddlewareDestroy')->only([
            'destroy',
        ]);

        //only needed for the [action] methods
        $this->middleware('contractsMiddlewareBulkEdit')->only([
            'changeCategoryUpdate',
        ]);

        $this->middleware('contractsMiddlewareShowPublic')->only([
            'showPublic',
            'sign',
        ]);

        $this->middleware('contractsMiddlewareSignClient')->only([
            'signClient',
            'signClientAction',
        ]);

        $this->middleware('contractsMiddlewareSignTeam')->only([
            'signTeam',
            'signTeamAction',
        ]);

        //repos
        $this->contractrepo = $contractrepo;
        $this->userrepo = $userrepo;
        $this->estimaterepo = $estimaterepo;
        $this->eventrepo = $eventrepo;
        $this->trackingrepo = $trackingrepo;
        $this->emailerrepo = $emailerrepo;
    }

    /**
     * Display a listing of contracts
     * @param object CategoryRepository instance of the repository
     * @param object Category instance of the repository
     * @return blade view | ajax view
     */
    public function index(CategoryRepository $categoryrepo, Category $categorymodel) {

        //get contracts
        $contracts = $this->contractrepo->search();

        //get all categories (type: contract) - for filter panel
        $categories = $categoryrepo->get('contract');

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('contracts'),
            'contracts' => $contracts,
            'count' => $contracts->count(),
            'stats' => $this->statsWidget(),
            'categories' => $categories,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new contract
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function create(CategoryRepository $categoryrepo) {

        //client categories
        $categories = $categoryrepo->get('contract');

        //templates
        $templates = \App\Models\ContractTemplate::orderBy('contract_template_id', 'ASC')->get();

        //we are on client page
        if (config('modal.type') == 'preset-client') {
            //get projects
            $projects = \App\Models\Project::Where('project_clientid', request('contractresource_id'))
                ->orderBy('project_title', 'asc')
                ->get();
            //save to config
            config([
                'client.id' => request('contractresource_id'),
                'client.projects' => $projects,
            ]);
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('create'),
            'categories' => $categories,
            'templates' => $templates,
        ];

        //show the form
        return new CreateResponse($payload);
    }

    /**
     * Store a newly created contractin storage.
     * @param object StoreUpdate instance of the repository
     * @param object UnitRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdate $request) {

        //get client
        if (!$client = \App\Models\Client::Where('client_id', request('doc_client_id'))->first()) {
            abort(409, __('lang.client_not_found'));
        }
        //set the fall back details
        $user = $this->userrepo->getClientAccountOwner(request('doc_client_id'));
        $doc_fallback_client_first_name = $user->first_name;
        $doc_fallback_client_last_name = $user->last_name;
        $doc_fallback_client_email = $user->email;

        //create the contract
        $contract = new \App\Models\Contract();
        $contract->doc_unique_id = str_unique();
        $contract->doc_creatorid = auth()->id();
        $contract->doc_type = 'contract';
        $contract->doc_categoryid = request('doc_categoryid');
        $contract->doc_client_id = request('doc_client_id');
        $contract->doc_lead_id = request('doc_lead_id');
        $contract->docresource_type = 'client';
        $contract->docresource_id = request('doc_client_id');
        $contract->doc_heading = __('lang.contract');
        $contract->doc_heading_color = '#FFFFFF';
        $contract->doc_title_color = '#FFFFFF';
        $contract->doc_title = request('doc_title');
        $contract->doc_date_start = request('doc_date_start');
        $contract->doc_date_end = request('doc_date_end');
        $contract->doc_value = request('doc_value');
        $contract->doc_fallback_client_first_name = $doc_fallback_client_first_name;
        $contract->doc_fallback_client_last_name = $doc_fallback_client_last_name;
        $contract->doc_fallback_client_email = $doc_fallback_client_email;
        $contract->save();

        //options
        if (is_numeric(request('contract_template'))) {
            if ($template = \App\Models\ContractTemplate::Where('contract_template_id', request('contract_template'))->first()) {
                $contract->doc_heading_color = $template->contract_template_heading_color;
                $contract->doc_title_color = $template->contract_template_title_color;
                $contract->doc_body = $template->contract_template_body;
                $contract->save();
            }
        }

        //create an estimate record
        $estimate_id = $this->estimaterepo->createContractEstimate($contract->doc_id);

        //get the contract object (friendly for rendering in blade template)
        $contracts = $this->contractrepo->search($contract->doc_id);

        //counting rows
        $rows = $this->contractrepo->search();
        $count = $rows->total();

        //reponse payload
        $payload = [
            'contracts' => $contracts,
            'id' => $contract->doc_id,
            'count' => $count,
        ];

        //process reponse
        return new StoreResponse($payload);

    }

    /**
     * Show the resource
     * @return blade view | ajax view
     */
    public function show(EstimateGeneratorRepository $estimategenerator, $id) {

        //defaults
        $has_estimate = false;

        $payload = [];

        //refresh contract
        $this->contractrepo->refreshContract($id);

        //get the project
        $documents = $this->contractrepo->search($id);
        $document = $documents->first();

        //get the estimate
        if ($estimate = \App\Models\Estimate::Where('bill_contractid', $id)->Where('bill_estimate_type', 'document')->first()) {
            request()->merge([
                'generate_estimate_mode' => 'document',
            ]);
            if ($payload = $estimategenerator->generate($estimate->bill_estimateid)) {
                $has_estimate = true;
            }
        }

        //mark events as read
        \App\Models\EventTracking::where('parent_id', $id)
            ->where('parent_type', 'contract')
            ->where('eventtracking_userid', auth()->id())
            ->update(['eventtracking_status' => 'read']);

        //custom fields
        $customfields = \App\Models\CustomField::Where('customfields_type', 'clients')->get();

        //set page
        $page = $this->pageSettings('contract', $document);

        //payload
        $payload += [
            'document' => $document,
            'page' => $page,
            'customfields' => $customfields,
            'estimate' => $estimate,
            'has_estimate' => $has_estimate,
        ];

        //show the view
        return new ShowPreviewResponse($payload);
    }

    /**
     * Show the resource on a public url
     * @return blade view | ajax view
     */
    public function showPublic($id) {

        //get contract
        $contract = \App\Models\Contract::Where('doc_unique_id', $id)->first();

        //authenticated
        if (auth()->check()) {
            $redirect_url = url('contracts/' . $contract->doc_id);
        } else {
            $redirect_url = url('login?action=redirect&redirect_url=' . request()->url());
        }

        return redirect($redirect_url);
    }

    /**
     * edit the cover
     * @return blade view | ajax view
     */
    public function editingContract(CategoryRepository $categoryrepo, $id) {

        //refresh contract
        $this->contractrepo->refreshContract($id);

        //get the project
        $documents = $this->contractrepo->search($id);
        $document = $documents->first();

        //make sure we have an estimate record
        $estimate_id = $this->estimaterepo->createContractEstimate($id);

        //get the estimate (or create if does not exist)
        if (!$estimate = \App\Models\Estimate::Where('bill_contractid', $id)->Where('bill_estimate_type', 'document')->first()) {
            //create an estimate record
            $this->estimaterepo->createContractEstimate($document->doc_id);
            $estimate = \App\Models\Estimate::Where('bill_contractid', $id)->Where('bill_estimate_type', 'document')->first();
        }

        //client categories
        $categories = $categoryrepo->get('contract');

        //custom fields
        $customfields = \App\Models\CustomField::Where('customfields_type', 'clients')->get();

        //set page
        $page = $this->pageSettings('contract', $document);

        //payload
        $payload = [
            'document' => $document,
            'page' => $page,
            'categories' => $categories,
            'customfields' => $customfields,
            'estimate' => $estimate,
            'mode' => 'editing',
        ];

        //show the view
        return new ShowEditResponse($payload);
    }

    /**
     * Remove the specified contract from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy() {

        //delete each record in the array
        $allrows = array();
        foreach (request('ids') as $id => $value) {
            //only checked contracts
            if ($value == 'on') {
                //get the contract
                $contract = \App\Models\Contract::Where('doc_id', $id)->first();
                //delete client
                $contract->delete();
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
     * Bulk change category for contracts
     * @url baseusr/contracts/bulkdelete
     * @return \Illuminate\Http\Response
     */
    public function bulkDelete() {

        //validation - post
        if (!is_array(request('contract'))) {
            abort(409);
        }

        //loop through and delete each one
        $deleted = 0;
        foreach (request('contract') as $contract_id => $value) {
            if ($value == 'on') {
                //get the contract
                if ($contracts = $this->contractrepo->search($contract_id)) {
                    //remove the contract
                    $contracts->first()->delete();
                    //hide and remove row
                    $jsondata['dom_visibility'][] = array(
                        'selector' => '#contract_' . $contract_id,
                        'action' => 'slideup-remove',
                    );
                }
                $deleted++;
            }
        }

        //something went wrong
        if ($deleted == 0) {
            abort(409);
        }

        //success
        $jsondata['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        //ajax response
        return response()->json($jsondata);
    }

    /**
     * Show the form for updating the contract
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function changeCategory(CategoryRepository $categoryrepo) {

        //get all contract categories
        $categories = $categoryrepo->get('contract');

        //reponse payload
        $payload = [
            'categories' => $categories,
        ];

        //show the form
        return new ChangeCategoryResponse($payload);
    }

    /**
     * Show the form for updating the contract
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function changeCategoryUpdate(CategoryRepository $categoryrepo) {

        //validate the category exists
        if (!\App\Models\Category::Where('category_id', request('category'))
            ->Where('category_type', 'contract')
            ->first()) {
            abort(409, __('lang.category_not_found'));
        }

        //update each contract
        $allrows = array();
        foreach (request('ids') as $contract_id => $value) {
            if ($value == 'on') {
                $contract = \App\Models\Contract::Where('doc_id', $contract_id)->first();
                //update the category
                $contract->doc_categoryid = request('category');
                $contract->save();
                //get the contract in rendering friendly format
                $contracts = $this->contractrepo->search($contract_id);
                //add to array
                $allrows[] = $contracts;
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
     * publish the resource
     * @return blade view | ajax view
     */
    public function publish($id) {

        //get the project
        $documents = $this->contractrepo->search($id);
        $document = $documents->first();

        //get the estimate
        if ($estimate = \App\Models\Estimate::Where('bill_contractid', $id)->Where('bill_estimate_type', 'document')->first()) {
            $value = $estimate->bill_final_amount;
        } else {
            $value = 0;
        }

        //mark as published
        $document->doc_status = 'awaiting_signatures';
        $document->doc_date_published = now();
        $document->doc_date_last_emailed = now();
        $document->save();

        //refresh contract
        $this->contractrepo->refreshContract($document->doc_id);

        /** ----------------------------------------------
         * record event [comment]
         * ----------------------------------------------*/
        $data = [
            'event_creatorid' => auth()->id(),
            'event_item' => 'contract',
            'event_item_id' => $document->doc_id,
            'event_item_lang' => 'event_created_contract',
            'event_item_content' => __('lang.contract') . ' - ' . runtimeContractIdFormat($document->doc_id),
            'event_item_content2' => '',
            'event_parent_type' => 'contract',
            'event_parent_id' => $document->doc_id,
            'event_parent_title' => $document->doc_title,
            'event_clientid' => $document->doc_client_id,
            'event_show_item' => 'yes',
            'event_show_in_timeline' => 'yes',
            'eventresource_type' => (is_numeric($document->doc_project_id)) ? 'project' : 'client',
            'eventresource_id' => (is_numeric($document->doc_project_id)) ? $document->doc_project_id : $document->doc_client_id,
            'event_notification_category' => 'notifications_billing_activity',
        ];
        $event_id = $this->eventrepo->create($data);

        /** ----------------------------------------------
         * send email - client users - [queued]
         * ----------------------------------------------*/
        if ($document->docresource_type == 'client') {
            if ($event_id = $this->eventrepo->create($data)) {
                //get users (main client)
                $users = $this->userrepo->getClientUsers($document->doc_client_id, 'owner', 'ids');
                //record notification
                $emailusers = $this->trackingrepo->recordEvent($data, $users, $event_id);
            }
            if (isset($emailusers) && is_array($emailusers)) {
                $data = [
                    'user_type' => 'client',
                    'contract_value' => $value,
                ];
                //send to users
                if ($users = \App\Models\User::WhereIn('id', $emailusers)->get()) {
                    foreach ($users as $user) {
                        $mail = new \App\Mail\ContractCreated($user, $data, $document);
                        $mail->build();
                    }
                }
            }
        }

        //payload
        $payload = [

        ];

        //return the reposnse
        return new PublishResponse($payload);
    }

    /**
     * email the resource
     * @return blade view | ajax view
     */
    public function resendEmail($id) {

        //get the project
        $documents = $this->contractrepo->search($id);
        $document = $documents->first();

        //get the estimate
        if ($estimate = \App\Models\Estimate::Where('bill_contractid', $id)->Where('bill_estimate_type', 'document')->first()) {
            $value = $estimate->bill_final_amount;
        } else {
            $value = 0;
        }

        //mark as published (fro draft status)
        if ($document->doc_status == 'draft') {
            $document->doc_status = 'new';
            $document->doc_date_published = now();
        }
        $document->doc_date_last_emailed = now();
        $document->save();

        /** ----------------------------------------------
         * send email - client users - [queued]
         * ----------------------------------------------*/
        if ($document->docresource_type == 'client') {
            $data = [
                'user_type' => 'client',
                'contract_value' => $value,
            ];
            if ($users = $this->userrepo->getClientUsers($document->doc_client_id, 'owner', 'collection')) {
                foreach ($users as $user) {
                    $mail = new \App\Mail\ContractCreated($user, $data, $document);
                    $mail->build();
                }
            }
        }

        //payload
        $payload = [

        ];

        //return the reposnse
        return new EmailResponse($payload);
    }

    /**
     * change the resource status
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($id) {

        //valid statuses
        $valid_statuses = [
            'accepted',
            'declined',
            'revised',
            'draft',
            'new',
        ];

        //validate
        if (!in_array(request('status'), $valid_statuses)) {
            abort(409, __('lang.invalid_status'));
        }

        //get contract
        $contract = \App\Models\Contract::Where('doc_id', $id)->first();

        //update
        $contract->doc_status = request('status');
        $contract->doc_signed_date = null;
        $contract->doc_signed_first_name = null;
        $contract->doc_signed_last_name = null;
        $contract->doc_signed_signature_directory = null;
        $contract->doc_signed_signature_filename = null;
        $contract->doc_signed_ip_address = null;
        $contract->save();

        //get the refreshed contract
        $contracts = $this->contractrepo->search($id);
        $contract = $contracts->first();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings(),
            'id' => $id,
            'contracts' => $contracts,
            'contract' => $contract,
            'stats' => $this->statsWidget(),
        ];

        //return the reposnse
        return new ChangeStatusResponse($payload);

    }

    /**
     * Show the form for attaching a project to an contract
     * @return \Illuminate\Http\Response
     */
    public function attachProject() {

        //get client id
        $client_id = request('client_id');

        //reponse payload
        $payload = [
            'projects_feed_url' => url("/feed/projects?ref=clients_projects&client_id=$client_id"),
            'type' => 'form',
        ];

        //show the form
        return new AttachProjectResponse($payload);
    }

    /**
     * attach a project to an contract
     * @return \Illuminate\Http\Response
     */
    public function attachProjectUpdate() {

        //validate the contract exists
        $contract = \App\Models\Contract::Where('bill_contractid', request()->route('contract'))->first();

        //validate the project exists
        if (!$project = \App\Models\Project::Where('project_id', request('attach_project_id'))->first()) {
            abort(409, __('lang.item_not_found'));
        }

        //update the contract
        $contract->doc_project_id = request('attach_project_id');
        $contract->doc_client_id = $project->project_clientid;
        $contract->save();

        //get refreshed contract
        $contracts = $this->contractrepo->search(request()->route('contract'));
        $contract = $contracts->first();

        //refresh contract
        $this->contractrepo->refreshcontract($contract);

        //reponse payload
        $payload = [
            'contracts' => $contracts,
            'type' => 'update',
        ];

        //show the form
        return new AttachProjectResponse($payload);
    }

    /**
     * dettach contract from a project
     * @return \Illuminate\Http\Response
     */
    public function dettachProject() {

        //validate the contract exists
        $contract = \App\Models\contract::Where('bill_contractid', request()->route('contract'))->first();

        //update the contract
        $contract->bill_projectid = null;
        $contract->save();

        //get refreshed contract
        $contracts = $this->contractrepo->search(request()->route('contract'));

        //reponse payload
        $payload = [
            'contracts' => $contracts,
        ];

        //show the form
        return new UpdateResponse($payload);
    }

    /**
     * show the form to sign a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function signTeam($id) {

        //get the contract
        $contract = \App\Models\Contract::Where('doc_unique_id', $id)->first();

        //page
        $html = view('pages/documents/components/contract/sign', compact('contract'))->render();
        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html,
        ];

        //postrun
        $jsondata['postrun_functions'][] = [
            'value' => 'NXSignDocument',
        ];

        //render
        return response()->json($jsondata);

    }

    /**
     * sign the contract
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function signTeamAction($id) {

        //get the contract
        $contract = \App\Models\Contract::Where('doc_unique_id', $id)->first();

        //custom error messages
        $messages = [
            'doc_signed_first_name.required' => __('lang.first_name') . ' - ' . __('lang.is_required'),
            'doc_signed_last_name.required' => __('lang.last_name') . ' - ' . __('lang.is_required'),
            'signature_code.required' => __('lang.signature') . ' - ' . __('lang.is_required'),
        ];

        //validate
        $validator = Validator::make(request()->all(), [
            'signature_code' => [
                'required',
            ],
            'doc_signed_first_name' => [
                'required',
            ],
            'doc_signed_last_name' => [
                'required',
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

        //generate the signature image
        $signature = $this->saveSignature();

        //update contract
        $contract->doc_provider_signed_date = now();
        $contract->doc_provider_signed_userid = auth()->id();
        $contract->doc_provider_signed_first_name = auth()->user()->first_name;
        $contract->doc_provider_signed_last_name = auth()->user()->last_name;
        $contract->doc_provider_signed_signature_directory = $signature['directory'];
        $contract->doc_provider_signed_signature_filename = $signature['file_name'];
        $contract->doc_provider_signed_ip_address = request()->ip();
        $contract->doc_provider_signed_status = 'signed';
        $contract->save();

        //get the refreshed contract
        $contract = \App\Models\Contract::Where('doc_unique_id', $id)->first();

        //set signatures visibility
        $this->contractrepo->visibilitySignatures($contract, 'edit');

        //reponse payload
        $payload = [
            'document' => $contract,
        ];

        //return the reposnse
        return new SignatureResponse($payload);

    }

    /**
     * show the form to sign a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function signClient($id) {

        //get the contract
        $contract = \App\Models\Contract::Where('doc_unique_id', $id)->first();

        //page
        $html = view('pages/documents/components/contract/sign', compact('contract'))->render();
        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html,
        ];

        //postrun
        $jsondata['postrun_functions'][] = [
            'value' => 'NXSignDocument',
        ];

        //render
        return response()->json($jsondata);

    }

    /**
     * sign the contract
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function signClientAction($id) {

        //get the contract
        $contract = \App\Models\Contract::Where('doc_unique_id', $id)->first();

        //custom error messages
        $messages = [
            'doc_signed_first_name.required' => __('lang.first_name') . ' - ' . __('lang.is_required'),
            'doc_signed_last_name.required' => __('lang.last_name') . ' - ' . __('lang.is_required'),
            'signature_code.required' => __('lang.signature') . ' - ' . __('lang.is_required'),
        ];

        //validate
        $validator = Validator::make(request()->all(), [
            'signature_code' => [
                'required',
            ],
            'doc_signed_first_name' => [
                'required',
            ],
            'doc_signed_last_name' => [
                'required',
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

        //generate the signature image
        $signature = $this->saveSignature();

        //update contract
        $contract->doc_signed_date = now();
        $contract->doc_signed_userid = (auth()->check()) ? auth()->id() : null;
        $contract->doc_signed_first_name = request('doc_signed_first_name');
        $contract->doc_signed_last_name = request('doc_signed_last_name');
        $contract->doc_signed_signature_directory = $signature['directory'];
        $contract->doc_signed_signature_filename = $signature['file_name'];
        $contract->doc_signed_ip_address = request()->ip();
        $contract->doc_signed_status = 'signed';
        $contract->save();

        //refresh contract
        $this->contractrepo->refreshContract($contract->doc_id);

        //get refreshed contract
        $contract = \App\Models\Contract::Where('doc_unique_id', $id)->first();

        /** ----------------------------------------------
         * record event [comment]
         * see database table to details of each key
         * ----------------------------------------------*/
        $data = [
            'event_creatorid' => -1,
            'event_creator_name' => $contract->doc_signed_first_name, //(optional) non-registered users
            'event_item' => 'contract',
            'event_item_id' => $contract->doc_id,
            'event_item_lang' => 'event_signed_contract',
            'event_item_content' => $contract->doc_title,
            'event_item_content2' => '',
            'event_clientid' => $contract->doc_client_id,
            'event_parent_type' => 'contract',
            'event_parent_id' => $contract->doc_id,
            'event_parent_title' => $contract->doc_title,
            'event_show_item' => 'yes',
            'event_show_in_timeline' => 'yes',
            'eventresource_type' => 'contract',
            'eventresource_id' => $contract->doc_id,
            'event_notification_category' => 'notifications_billing_activity',
        ];
        //record event
        if ($event_id = $this->eventrepo->create($data)) {
            //get users
            $users = $this->userrepo->mailingListProposals();
            //dd($users);
            //record notification
            $emailusers = $this->trackingrepo->recordEvent($data, $users, $event_id);
        }

        /** ----------------------------------------------
         * send email [comment
         * ----------------------------------------------*/
        if (isset($emailusers) && is_array($emailusers)) {
            //send to users
            if ($users = \App\Models\User::WhereIn('id', $emailusers)->get()) {
                foreach ($users as $user) {
                    $mail = new \App\Mail\ContractSigned($user, [], $contract);
                    $mail->build();
                }
            }
        }

        //redirect
        if (auth()->check()) {
            $jsondata['redirect_url'] = url("contracts/view/$id");
        } else {
            $jsondata['redirect_url'] = url("contracts/view/" . $contract->doc_unique_id . "?action=preview");
        }

        //thank you message
        request()->session()->flash('success-notification-long', __('lang.request_has_been_completed'));

        //ajax response
        return response()->json($jsondata);

    }

    /**
     * delete team signature
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function signDeleteSignature($id) {

        //get the contract
        $contract = \App\Models\Contract::Where('doc_unique_id', $id)->first();

        //check if the client has not already signed the contract
        if ($contract->doc_signed_status == 'signed') {
            abort(409, __('lang.contract_signature_cannot_be_delete'));
        }

        //update contract
        $contract->doc_provider_signed_date = null;
        $contract->doc_provider_signed_userid = null;
        $contract->doc_provider_signed_first_name = '';
        $contract->doc_provider_signed_last_name = '';
        $contract->doc_provider_signed_signature_directory = '';
        $contract->doc_provider_signed_signature_filename = '';
        $contract->doc_provider_signed_ip_address = '';
        $contract->doc_provider_signed_status = 'unsigned';
        $contract->save();

        //get the refreshed contract
        $contract = \App\Models\Contract::Where('doc_unique_id', $id)->first();

        //set signatures visibility
        $this->contractrepo->visibilitySignatures($contract, 'edit');

        //reponse payload
        $payload = [
            'document' => $contract,
        ];

        //return the reposnse
        return new SignatureResponse($payload);

    }

    /**
     * save signature as an image
     * @return array
     */
    public function saveSignature() {

        //unique file id & directory name
        $directory = Str::random(40);
        $file_name = 'signature.png';
        $file_path = "files/$directory/$file_name";
        $file_full_path = path_storage() . '/' . $file_path;

        //create signature image
        $signature_data = request('signature_code');
        $encoded_image = explode(",", $signature_data)[1];
        $decoded_image = base64_decode($encoded_image);

        //save file to directory
        Storage::put($file_path, $decoded_image);

        //trim white spaces from the image: https://image.intervention.io/v2/api/trim
        try {
            Image::make($file_full_path)->trim('top-left', null, 60)->save();
        } catch (NotReadableException $e) {
            Log::error("Unable to crop signature image", ['process' => '[accept-contract]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        }

        return [
            'directory' => $directory,
            'file_name' => $file_name,
            'file_path' => $file_path,
        ];

    }

    /**
     * data for the stats widget
     * @return array
     */
    private function statsWidget($data = array()) {

        //stats
        $sum_active = $this->contractrepo->search('', ['stats' => 'sum-active']);
        $count_active = $this->contractrepo->search('', ['stats' => 'count-active']);
        $count_awaiting_signatures = $this->contractrepo->search('', ['stats' => 'count-awaiting_signatures']);
        $count_expired = $this->contractrepo->search('', ['stats' => 'count-expired']);

        //default values
        $stats = [
            [
                'value' => runtimeMoneyFormat($sum_active),
                'title' => __('lang.active'),
                'percentage' => '100%',
                'color' => 'bg-info',
            ],
            [
                'value' => $count_active,
                'title' => __('lang.active'),
                'percentage' => '100%',
                'color' => 'bg-success',
            ],
            [
                'value' => $count_awaiting_signatures,
                'title' => __('lang.awaiting_signatures'),
                'percentage' => '100%',
                'color' => 'bg-warning',
            ],
            [
                'value' => $count_expired,
                'title' => __('lang.expired'),
                'percentage' => '100%',
                'color' => 'bg-danger',
            ],
        ];
        //return
        return $stats;
    }


        /**
     * show the form for cloning an project
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function createClone(CategoryRepository $categoryrepo, $id) {

        //get the project
        $contract = \App\Models\Contract::Where('doc_id', $id)->first();

        //project categories
        $categories = $categoryrepo->get('contract');

        //reponse payload
        $payload = [
            'response' => 'create',
            'contract' => $contract,
            'categories' => $categories,
        ];

        //show the form
        return new CreateCloneResponse($payload);
    }

    /**
     * show the form for cloning an project
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function storeClone(CloneContractRepository $clonerepo, $id) {

        //data
        $data = [
            'doc_id' => $id,
            'doc_title' => request('doc_title'),
            'doc_date_start' => request('doc_date_start'),
            'doc_date_end' => (request()->filled('doc_date_end')) ? request('doc_date_end') : null,
            'docresource_type' => 'client',
            'doc_client_id' => request('doc_client_id'),
            'doc_project_id' => request('doc_project_id'),
            'doc_categoryid' => request('doc_categoryid'),
            'doc_value' => request('doc_value'),
        ];

        //get the project
        if (!$contract = $clonerepo->clone($data)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //reponse payload
        $payload = [
            'response' => 'store',
            'contract' => $contract,
        ];

        //show the form
        return new CreateCloneResponse($payload);
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
                __('lang.contracts'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'contracts',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_contracts' => 'active',
            'submenu_contracts' => 'active',
            'sidepanel_id' => 'sidepanel-filter-contracts',
            'dynamic_search_url' => url('contracts/search?action=search&contractresource_id=' . request('contractresource_id') . '&contractresource_type=' . request('contractresource_type')),
            'load_more_button_route' => 'contracts',
            'source' => 'list',
        ];

        //contracts list page
        if ($section == 'contracts') {

            //adjust
            $page['page'] = 'contract';

            $page += [
                'meta_title' => __('lang.contracts'),
                'heading' => __('lang.contracts'),
            ];

            return $page;
        }

        //contracts list page
        if ($section == 'contract') {

            //crumbs
            $page['crumbs'] = [
                __('lang.contract'),
                '#' . $data->formatted_id,
            ];

            $page += [
                'meta_title' => __('lang.contract'),
                'heading' => __('lang.contract'),
            ];
            if (request('source') == 'ext') {
                $page += [
                    'list_page_actions_size' => 'col-lg-12',
                ];
            }
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
            $page += [
                'section' => 'edit',
            ];
            return $page;
        }

        //return
        return $page;
    }
}