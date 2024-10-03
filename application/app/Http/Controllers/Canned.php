<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\Canned\CannedMessagesResponse;
use App\Http\Responses\Canned\CreateResponse;
use App\Http\Responses\Canned\DestroyResponse;
use App\Http\Responses\Canned\EditResponse;
use App\Http\Responses\Canned\IndexResponse;
use App\Http\Responses\Canned\StoreResponse;
use App\Http\Responses\Canned\UpdateResponse;
use App\Repositories\CannedRepository;
use App\Rules\NoTags;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class Canned extends Controller {

    /**
     * The canned repository instance.
     */
    protected $cannedrepo;

    public function __construct(CannedRepository $cannedrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('cannedMiddlewareIndex')->only([
            'index',
            'create',
            'store',
            'update',
        ]);

        $this->middleware('cannedMiddlewareEdit')->only([
            'edit',
            'update',
        ]);

        $this->middleware('cannedMiddlewareCreate')->only([
            'create',
            'store',
        ]);

        $this->middleware('cannedMiddlewareDestroy')->only([
            'destroy',
        ]);

        $this->cannedrepo = $cannedrepo;
    }

    /**
     * Display a listing of canned
     * @url baseusr/canned?page=1&source=ext&action=load
     * @urlquery
     *    - [page] numeric|null (pagination page number)
     *    - [source] ext|null  (ext: when called from embedded pages)
     *    - [action] load | null (load: when making additional ajax calls)
     * @return blade view | ajax view
     */
    public function index() {

        $category_list = [];

        //get team members
        $canned_responses = $this->cannedrepo->search();

        $categories = \App\Models\Category::Where('category_type', 'canned')->orderBy('category_name', 'asc')->get();

        foreach ($categories as $category) {
            request()->merge([
                'filter_index_categoryid' => $category->category_id,
            ]);
            $rows = $this->cannedrepo->search();
            $category_list[] = [
                'category_id' => $category->category_id,
                'category_name' => $category->category_name,
                'count_canned' => count($rows),
            ];
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('canned'),
            'canned_responses' => $canned_responses,
            'category_list' => $category_list,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $categories = \App\Models\Category::Where('category_type', 'canned')->orderBy('category_name', 'asc')->get();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('create'),
            'categories' => $categories,
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
            'canned_title.required' => __('lang.response_title') . ' - ' . __('lang.is_required'),
            'filter_categoryid.required' => __('lang.category') . ' - ' . __('lang.is_required'),
            'canned_categoryid.exists' => __('lang.category') . ' - ' . __('lang.is_invalid'),
            'html_canned_message.required' => __('lang.message') . ' - ' . __('lang.is_required'),
        ];

        //validate
        $validator = Validator::make(request()->all(), [
            'canned_title' => [
                'required',
            ],
            'filter_categoryid' => [
                'required',
                Rule::exists('categories', 'category_id')->where(function ($query) {
                    $query->where('category_type', 'canned');
                }),
            ],
            'html_canned_message' => [
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

        $canned_response = new \App\Models\Canned();
        $canned_response->canned_creatorid = auth()->id();
        $canned_response->canned_title = request('canned_title');
        $canned_response->canned_categoryid = request('filter_categoryid');
        $canned_response->canned_message = request('html_canned_message');
        $canned_response->canned_visibility = (request('canned_visibility') == 'on') ? 'private' : 'public';
        $canned_response->save();

        //permissions
        if (auth()->user()->role->role_canned == 'no') {
            $canned_response->canned_visibility = 'private';
            $canned_response->save();
        }

        //count rows
        $canned = $this->cannedrepo->search();
        $count = count($canned);

        //get the canned object (friendly for rendering in blade template)
        $canned_responses = $this->cannedrepo->search($canned_response->canned_id);

        //reponse payload
        $payload = [
            'canned_responses' => $canned_responses,
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

        //get the canned
        $canned = $this->cannedrepo->search($id);

        //not found
        if (!$canned = $canned->first()) {
            abort(409, __('lang.hello'));
        }

        //reponse payload
        $payload = [
            'page' => $page,
            'canned' => $canned,
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

        //get the canned
        $canned = $this->cannedrepo->search($id);
        $categories = \App\Models\Category::Where('category_type', 'canned')->orderBy('category_name', 'asc')->get();

        //not found
        if (!$canned = $canned->first()) {
            abort(409, 'The requested canned could not be loaded');
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
            'canned' => $canned,
            'categories' => $categories,
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

        $canned_response = \App\Models\Canned::Where('canned_id', $id)->first();

        //category
        $old_category = $canned_response->canned_categoryid;
        $new_category = request('filter_categoryid');

        //custom error messages
        $messages = [
            'canned_categoryid.exists' => __('lang.item_not_found'),
        ];

        //validate
        $validator = Validator::make(request()->all(), [
            'canned_title' => [
                'required',
                new NoTags,
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

        //save
        $canned_response->canned_title = request('canned_title');
        $canned_response->canned_categoryid = request('filter_categoryid');
        $canned_response->canned_message = request('html_canned_message');
        $canned_response->canned_visibility = (request('canned_visibility') == 'on') ? 'private' : 'public';
        $canned_response->save();

        //get canned
        $canned_responses = $this->cannedrepo->search($id);

        $count_old_category = \App\Models\Canned::where('canned_categoryid', $old_category)->count();
        $count_new_category = \App\Models\Canned::where('canned_categoryid', $new_category)->count();

        //reponse payload
        $payload = [
            'canned_responses' => $canned_responses,
            'old_category' => $old_category,
            'new_category' => $new_category,
            'count_old_category' => $count_old_category,
            'count_new_category' => $count_new_category,
            'id' => $id,
        ];

        //generate a response
        return new UpdateResponse($payload);
    }

    /**
     * Remove the specified item from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        //count
        $count = 0;
        $category_id = '';

        //delete each record in the array
        if ($canned = \App\Models\Canned::Where('canned_id', $id)->first()) {
            //category id
            $category_id = $canned->canned_categoryid;
            //delete client
            $canned->delete();
            //count rows
            $count = \App\Models\Canned::where('canned_categoryid', $category_id)->count();

        }

        //reponse payload
        $payload = [
            'id' => $id,
            'count' => $count,
            'category_id' => $category_id,
        ];

        //generate a response
        return new DestroyResponse($payload);
    }

    /**
     * get the recently used canned messages for this user
     *
     * @return \Illuminate\Http\Response
     */
    public function search() {

        //security
        if (auth()->user()->is_client) {
            abort(403);
        }

        //get list
        $canned_responses = $this->cannedrepo->search();

        //reponse payload
        $payload = [
            'canned_responses' => $canned_responses,
        ];

        //response
        return new CannedMessagesResponse($payload);
    }

    /**
     * update this users last used canned respoonse - we limit to 10 last used
     *
     * @return \Illuminate\Http\Response
     */
    public function updateRecentlyUsed($id) {

        //validate
        if (!$canned = \App\Models\Canned::Where('canned_id', $id)->first()) {
            return;
        }

        // check if the user already has 10 records
        $user_records_count = \App\Models\CannedRecentlyUsed::where('cannedrecent_userid', auth()->id())->count();

        // if the user already has 10 records, delete the oldest record
        if ($user_records_count >= 10) {
            // get the oldest record for the user
            $oldest_record = \App\Models\CannedRecentlyUsed::where('cannedrecent_userid', auth()->id())
                ->orderBy('cannedrecent_id', 'asc')
                ->first();

            // delete the oldest record
            $oldest_record->delete();
        }

        //delete if same record already exists
        \App\Models\CannedRecentlyUsed::where('cannedrecent_cannedid', $id)->where('cannedrecent_userid', auth()->id())->delete();

        //create a new record
        $recent = new \App\Models\CannedRecentlyUsed();
        $recent->cannedrecent_cannedid = $id;
        $recent->cannedrecent_userid = auth()->id();
        $recent->save();

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
                __('lang.canned_messages'),
            ],
            'meta_title' => __('lang.canned_messages'),
            'heading' => __('lang.canned_messages'),
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'canned',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_canned' => 'active',
            'sidepanel_id' => 'sidepanel-filter-canned',
            'dynamic_search_url' => url('canned/search?action=search&cannedresource_id=' . request('cannedresource_id') . '&cannedresource_type=' . request('cannedresource_type')),
            'add_button_classes' => 'add-edit-canned-button',
            'load_more_button_route' => 'canned',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => __('lang.add_new_canned'),
            'add_modal_create_url' => url('canned/create?cannedresource_id=' . request('cannedresource_id') . '&cannedresource_type=' . request('cannedresource_type')),
            'add_modal_action_url' => url('canned?cannedresource_id=' . request('cannedresource_id') . '&cannedresource_type=' . request('cannedresource_type')),
            'add_modal_action_ajax_class' => 'js-ajax-ux-request',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //canned list page
        if ($section == 'canned') {
            $page += [
                'meta_title' => __('lang.canned'),
                'heading' => __('lang.canned'),
                'sidepanel_id' => 'sidepanel-filter-canned',
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