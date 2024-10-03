<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Templates;

use App\Http\Controllers\Controller;
use App\Http\Responses\Templates\Contracts\CreateResponse;
use App\Http\Responses\Templates\Contracts\DestroyResponse;
use App\Http\Responses\Templates\Contracts\EditResponse;
use App\Http\Responses\Templates\Contracts\IndexResponse;
use App\Http\Responses\Templates\Contracts\StoreResponse;
use App\Http\Responses\Templates\Contracts\UpdateResponse;
use App\Repositories\ContractTemplateRepository;
use App\Rules\NoTags;
use Illuminate\Http\Request;
use Validator;

class Contracts extends Controller {

    /**
     * The contract repository instance.
     */
    protected $templaterepo;

    public function __construct(ContractTemplateRepository $templaterepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('contractTemplatesMiddlewareIndex')->only([
            'index',
            'create',
            'store',
            'update',
        ]);

        $this->middleware('contractTemplatesMiddlewareEdit')->only([
            'edit',
            'update',
        ]);

        $this->middleware('contractTemplatesMiddlewareCreate')->only([
            'create',
            'store',
        ]);

        $this->middleware('contractTemplatesMiddlewareDestroy')->only([
            'destroy',
        ]);

        $this->templaterepo = $templaterepo;
    }

    /**
     * Display a listing of templates
     * @urlquery
     *    - [page] numeric|null (pagination page number)
     *    - [source] ext|null  (ext: when called from embedded pages)
     *    - [action] load | null (load: when making additional ajax calls)
     * @return blade view | ajax view
     */
    public function index() {

        //get team members
        $templates = $this->templaterepo->search();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('templates'),
            'templates' => $templates,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create() {

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('create'),
        ];

        //show the form
        return new CreateResponse($payload);
    }


    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\Response
     */
    public function store() {

        //custom error messages
        $messages = [
            'contract_template_title.required' => __('lang.title') . '-' . __('lang.is_required'),
            'contract_template_body.required' => __('lang.content') . '-' . __('lang.is_required'),
        ];

        //validate
        $validator = Validator::make(request()->all(), [
            'contract_template_title' => [
                'required',
                new NoTags,
            ],
            'contract_template_body' => 'required',
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

        $template = new \App\Models\ContractTemplate();
        $template->contract_template_creatorid = auth()->id();
        $template->contract_template_title = request('contract_template_title');
        $template->contract_template_body = request('contract_template_body');
        $template->save();

        //count rows
        $templates = $this->templaterepo->search();
        $count = count($templates);

        //get the template object (friendly for rendering in blade template)
        $templates = $this->templaterepo->search($template->contract_template_id);

        //reponse payload
        $payload = [
            'templates' => $templates,
            'count' => $count,
        ];

        //process reponse
        return new StoreResponse($payload);

    }

    /**
     * Display the specified resource.
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        //page settings
        $page = $this->pageSettings('edit');

        //get the contract
        $contract = $this->templaterepo->search($id);

        //not found
        if (!$contract = $contract->first()) {
            abort(409, __('lang.hello'));
        }

        //reponse payload
        $payload = [
            'page' => $page,
            'contract' => $contract,
        ];

        //response
        return new EditResponse($payload);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        //get the contract
        $templates = $this->templaterepo->search($id);

        //not found
        if (!$template = $templates->first()) {
            abort(409, 'The requested contract could not be loaded');
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
            'template' => $template,
        ];

        //response
        return new EditResponse($payload);
    }

    /**
     * Update the specified resource in storage.
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function update($id) {

        //get the template
        $template = \App\Models\ContractTemplate::Where('contract_template_id', $id)->first();

        //custom error messages
        $messages = [
            'contract_template_title.required' => __('lang.title') . '-' . __('lang.is_required'),
            'contract_template_body.required' => __('lang.content') . '-' . __('lang.is_required'),
        ];

        //validate
        $validator = Validator::make(request()->all(), [
            'contract_template_title' => 'required',
            'contract_template_body' => 'required',
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
        $template->contract_template_title = request('contract_template_title');
        $template->contract_template_body = request('contract_template_body');
        $template->save();

        //get contract
        $templates = $this->templaterepo->search($id);

        //reponse payload
        $payload = [
            'templates' => $templates,
            'id' => $id,
        ];

        //generate a response
        return new UpdateResponse($payload);
    }

    /**
     * Remove the specified item from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy() {

        //delete each record in the array
        $allrows = array();
        foreach (request('ids') as $id => $value) {
            //only checked template
            if ($value == 'on') {
                if ($template = \App\Models\ContractTemplate::Where('contract_template_id', $id)->first()) {
                    //delete client
                    $template->delete();
                }
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
                __('lang.templates'),
                __('lang.contracts'),
            ],
            'meta_title' => __('lang.templates') . ' - ' . __('lang.contracts'),
            'heading' => __('lang.templates'),
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'contracts',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_contracts' => 'active',
            'submenu_templates' => 'active',
            'sidepanel_id' => 'sidepanel-filter-contracts',
            'dynamic_search_url' => url('contracts/search?action=search&contractresource_id=' . request('contractresource_id') . '&contractresource_type=' . request('contractresource_type')),
            'add_button_classes' => 'add-edit-contract-button',
            'load_more_button_route' => 'contracts',
            'source' => 'list',
        ];

        //return
        return $page;
    }
}