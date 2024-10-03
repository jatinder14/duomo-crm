<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for product items
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Items\ItemStoreUpdate;
use App\Http\Requests\Items\StoreUpdateTask;
use App\Http\Responses\Common\ChangeCategoryResponse;
use App\Http\Responses\Items\ChangeCategoryUpdateResponse;
use App\Http\Responses\Items\CreateResponse;
use App\Http\Responses\Items\DestroyResponse;
use App\Http\Responses\Items\EditResponse;
use App\Http\Responses\Items\IndexResponse;
use App\Http\Responses\Items\StoreResponse;
use App\Http\Responses\Items\CategoryItemsResponse;
use App\Http\Responses\Items\Tasks\TasksCreateResponse;
use App\Http\Responses\Items\Tasks\TasksDeleteResponse;
use App\Http\Responses\Items\Tasks\TasksEditResponse;
use App\Http\Responses\Items\Tasks\TasksIndexResponse;
use App\Http\Responses\Items\UpdateResponse;
use App\Models\Category;
use App\Models\Item;
use App\Repositories\CategoryRepository;
use App\Repositories\ItemRepository;
use App\Repositories\ProductTaskRepository;
use App\Repositories\UnitRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class Items extends Controller {

    /**
     * The item repository instance.
     */
    protected $itemrepo;

    /**
     * The user repository instance.
     */
    protected $userrepo;

    /**
     * The settings repository instance.
     */
    protected $unitrepo;

    protected $producttaskrepo;

    public function __construct(ItemRepository $itemrepo, UserRepository $userrepo, UnitRepository $unitrepo, ProductTaskRepository $producttaskrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('itemsMiddlewareIndex')->only([
            'index',
            'update',
            'store',
            'changeCategoryUpdate',
            'indexTasks',
        ]);

        $this->middleware('itemsMiddlewareEdit')->only([
            'edit',
            'update',
        ]);

        $this->middleware('itemsMiddlewareCreate')->only([
            'create',
            'store',
        ]);

        $this->middleware('itemsMiddlewareDestroy')->only([
            'destroy',
        ]);

        //only needed for the [action] methods
        $this->middleware('itemsMiddlewareBulkEdit')->only([
            'changeCategoryUpdate',
        ]);

        $this->middleware('productTasksMiddlewareView')->only([
            'indexTasks',
        ]);

        $this->middleware('productTasksMiddlewareEdit')->only([
            'createTask',
            'storeTask',
            'editTask',
            'DeleteTask',
        ]);

        //repos
        $this->itemrepo = $itemrepo;
        $this->userrepo = $userrepo;
        $this->unitrepo = $unitrepo;
        $this->producttaskrepo = $producttaskrepo;

    }

    /**
     * Display a listing of items
     * @param object CategoryRepository instance of the repository
     * @param object Category instance of the repository
     * @return blade view | ajax view
     */
    public function index(CategoryRepository $categoryrepo, Category $categorymodel) {

        //get items
        $items = $this->itemrepo->search();

        //get all categories (type: item) - for filter panel
        $categories = $categoryrepo->get('item');

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('items'),
            'items' => $items,
            'count' => $items->count(),
            'categories' => $categories,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * fetch all categories and their items
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryItems(CategoryRepository $categoryrepo) {

        //get all categories (type: item) - for filter panel
        $categories = $categoryrepo->getCategoryItems();

        //reponse payload
        $payload = [
            'categories' => $categories,
        ];

        //show the form
        return new CategoryItemsResponse($payload);

    }

    /**
     * Show the form for creating a new item
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function create(CategoryRepository $categoryrepo) {

        //client categories
        $categories = $categoryrepo->get('item');

        //units
        $units = $this->unitrepo->search();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('create'),
            'categories' => $categories,
            'units' => $units,
        ];

        //show the form
        return new CreateResponse($payload);
    }

    /**
     * Store a newly created itemin storage.
     * @param object ItemStoreUpdate instance of the repository
     * @param object UnitRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function store(ItemStoreUpdate $request, UnitRepository $unitrepo) {

        //create the item
        if (!$item_id = $this->itemrepo->create()) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //get the item object (friendly for rendering in blade template)
        $items = $this->itemrepo->search($item_id);

        //update units list
        $unitrepo->updateList(request('item_unit'));

        //counting rows
        $rows = $this->itemrepo->search();
        $count = $rows->total();

        //reponse payload
        $payload = [
            'items' => $items,
            'count' => $count,
        ];

        //process reponse
        return new StoreResponse($payload);

    }

    /**
     * Display the specified item
     * @param int $id item id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified item
     * @param object CategoryRepository instance of the repository
     * @param int $id item id
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryRepository $categoryrepo, $id) {

        //get the item
        $item = $this->itemrepo->search($id);

        //client categories
        $categories = $categoryrepo->get('item');

        //units
        $units = $this->unitrepo->search();

        //not found
        if (!$item = $item->first()) {
            abort(409, __('lang.product_not_found'));
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
            'item' => $item,
            'categories' => $categories,
            'units' => $units,
        ];

        //response
        return new EditResponse($payload);
    }

    /**
     * Update the specified itemin storage.
     * @param object ItemStoreUpdate instance of the repository
     * @param object UnitRepository instance of the repository
     * @param int $id item id
     * @return \Illuminate\Http\Response
     */
    public function update(ItemStoreUpdate $request, UnitRepository $unitrepo, $id) {

        //update
        if (!$this->itemrepo->update($id)) {
            abort(409);
        }

        //update tax status of line items that were created with this item
        \App\Models\Lineitem::where('lineitem_linked_product_id', $id)
            ->update(['lineitem_tax_status' => request('item_tax_status')]);

        //get item
        $items = $this->itemrepo->search($id);

        //update units list
        $unitrepo->updateList(request('item_unit'));

        //reponse payload
        $payload = [
            'items' => $items,
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
            //only checked items
            if ($value == 'on') {
                //get the item
                $item = \App\Models\Item::Where('item_id', $id)->first();
                //delete client
                $item->delete();

                //delete any automation tasks
                if ($tasks = \App\Models\ProductTask::Where('product_task_itemid', $id)->get()) {
                    foreach ($tasks as $task) {
                        //delete assigned users
                        \App\Models\AutomationAssigned::Where('automationassigned_resource_type', 'product_task')
                            ->Where('automationassigned_resource_id', $task->product_task_id)->delete();

                        //delete dependencies
                        \App\Models\ProductTasksDependency::Where('product_task_dependency_taskid', $task->product_task_id)->delete();

                        //delete the task
                        $task->delete();

                    }
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
     * Bulk change category for items
     * @url baseusr/items/bulkdelete
     * @return \Illuminate\Http\Response
     */
    public function bulkDelete() {

        //validation - post
        if (!is_array(request('item'))) {
            abort(409);
        }

        //loop through and delete each one
        $deleted = 0;
        foreach (request('item') as $item_id => $value) {
            if ($value == 'on') {
                //get the item
                if ($items = $this->itemrepo->search($item_id)) {
                    //remove the item
                    $items->first()->delete();
                    //hide and remove row
                    $jsondata['dom_visibility'][] = array(
                        'selector' => '#item_' . $item_id,
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
     * Show the form for updating the item
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function changeCategory(CategoryRepository $categoryrepo) {

        //get all item categories
        $categories = $categoryrepo->get('item');

        //reponse payload
        $payload = [
            'categories' => $categories,
        ];

        //show the form
        return new ChangeCategoryResponse($payload);
    }

    /**
     * Show the form for updating the item
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function changeCategoryUpdate(CategoryRepository $categoryrepo) {

        //validate the category exists
        if (!\App\Models\Category::Where('category_id', request('category'))
            ->Where('category_type', 'item')
            ->first()) {
            abort(409, __('lang.category_not_found'));
        }

        //update each item
        $allrows = array();
        foreach (request('ids') as $item_id => $value) {
            if ($value == 'on') {
                $item = \App\Models\Item::Where('item_id', $item_id)->first();
                //update the category
                $item->item_categoryid = request('category');
                $item->save();
                //get the item in rendering friendly format
                $items = $this->itemrepo->search($item_id);
                //add to array
                $allrows[] = $items;
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
     * Show the resource
     * @return blade view | ajax view
     */
    public function indexTasks($id) {

        request()->merge([
            'filter_product_task_itemid' => $id,
        ]);

        //get the item
        $tasks = $this->producttaskrepo->search();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings(),
            'tasks' => $tasks,
        ];

        //return the reposnse
        return new TasksIndexResponse($payload);

    }

    /**
     * show the form to create a new resource
     *
     * @return \Illuminate\Http\Response
     */
    public function createTask() {

        //get all the tasks again
        request()->merge([
            'filter_product_task_itemid' => request('item_id'),
        ]);
        $tasks = $this->producttaskrepo->search();

        $payload = [
            'tasks' => $tasks,
        ];

        //return the reposnse
        return new TasksCreateResponse($payload);

    }

    /**
     * show the form to create a new resource
     *
     * @return \Illuminate\Http\Response
     */
    public function storeTask(StoreUpdateTask $request) {

        //store record
        $task = new \App\Models\ProductTask();
        $task->product_task_title = request('product_task_title');
        $task->product_task_description = request('product_task_description');
        $task->product_task_itemid = request('item_id');
        $task->product_task_creatorid = auth()->id();
        $task->save();

        //assigned add (reset)
        if (is_array(request('automation_assigned_users'))) {
            foreach (request('automation_assigned_users') as $user_id) {
                $assigned = new \App\Models\AutomationAssigned();
                $assigned->automationassigned_resource_type = 'product_task';
                $assigned->automationassigned_resource_id = $task->product_task_id;
                $assigned->automationassigned_userid = $user_id;
                $assigned->save();
            }
        }

        //[dependencies][cannot complete] - add all
        if (request()->filled('dependencies_cannot_complete') && is_array(request('dependencies_cannot_complete'))) {
            foreach (request('dependencies_cannot_complete') as $blocking_task_id) {
                $dependency = new \App\Models\ProductTasksDependency();
                $dependency->product_task_dependency_taskid = $task->product_task_id;
                $dependency->product_task_dependency_blockerid = $blocking_task_id;
                $dependency->product_task_dependency_type = 'cannot_complete';
                $dependency->save();
            }
        }

        //[dependencies][cannot start] - add all
        if (request()->filled('dependencies_cannot_start') && is_array(request('dependencies_cannot_start'))) {
            foreach (request('dependencies_cannot_start') as $blocking_task_id) {
                $dependency = new \App\Models\ProductTasksDependency();
                $dependency->product_task_dependency_taskid = $task->product_task_id;
                $dependency->product_task_dependency_blockerid = $blocking_task_id;
                $dependency->product_task_dependency_type = 'cannot_start';
                $dependency->save();
            }
        }

        //get all the tasks again
        request()->merge([
            'filter_product_task_itemid' => $task->product_task_itemid,
        ]);

        //get the item
        $tasks = $this->producttaskrepo->search();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings(),
            'tasks' => $tasks,
            'action' => 'create-edit-task',
        ];

        //return the reposnse
        return new TasksIndexResponse($payload);
    }

    /**
     * show the form to edit a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function editTask($id) {

        //get the foo
        if (!$task = \App\Models\ProductTask::Where('product_task_id', $id)->first()) {
            abort(404);
        }

        //assigned users
        $assigned_users = \App\Models\AutomationAssigned::Where('automationassigned_resource_type', 'product_task')
            ->Where('automationassigned_resource_id', $id)
            ->get();

        $assigned = [];
        foreach ($assigned_users as $user) {
            $assigned[] = $user->automationassigned_userid;
        }

        //[dependency management] - get all tasks
        request()->merge([
            'filter_product_task_itemid' => $task->product_task_itemid,
            'filter_exclude_tasks' => [$id],
        ]);
        $tasks = $this->producttaskrepo->search();

        //[dependency management] - get dependencies for this task which are of type [cannot complete]
        $dependencies = \App\Models\ProductTasksDependency::Where('product_task_dependency_type', 'cannot_complete')
            ->Where('product_task_dependency_taskid', $id)
            ->get();

        $cannot_complete_dependencies = [];
        foreach ($dependencies as $dependency) {
            $cannot_complete_dependencies[] = $dependency->product_task_dependency_blockerid;
        }

        //[dependency management] - get dependencies for this task which are of type [cannot start]
        $dependencies = \App\Models\ProductTasksDependency::Where('product_task_dependency_type', 'cannot_start')
            ->Where('product_task_dependency_taskid', $id)
            ->get();

        $cannot_start_dependencies = [];
        foreach ($dependencies as $dependency) {
            $cannot_start_dependencies[] = $dependency->product_task_dependency_blockerid;
        }

        //reponse payload
        $payload = [
            'task' => $task,
            'tasks' => $tasks,
            'assigned' => $assigned,
            'cannot_complete_dependencies' => $cannot_complete_dependencies,
            'cannot_start_dependencies' => $cannot_start_dependencies,
        ];

        //return the reposnse
        return new TasksEditResponse($payload);
    }

    /**
     * show the form to edit a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function updateTask(StoreUpdateTask $request, $id) {

        //get the foo
        if (!$task = \App\Models\ProductTask::Where('product_task_id', $id)->first()) {
            abort(404);
        }

        //save changes
        $task->product_task_title = request('product_task_title');
        $task->product_task_description = request('product_task_description');
        $task->save();

        //delete assigned users
        \App\Models\AutomationAssigned::Where('automationassigned_resource_id', 'product')
            ->Where('automationassigned_resource_id', $id)
            ->delete();

        //assigned add (reset)
        if (is_array(request('automation_assigned_users'))) {
            foreach (request('automation_assigned_users') as $user_id) {
                $assigned = new \App\Models\AutomationAssigned();
                $assigned->automationassigned_resource_type = 'product_task';
                $assigned->automationassigned_resource_id = $id;
                $assigned->automationassigned_userid = $user_id;
                $assigned->save();
            }
        }

        //[dependencies] - delete all
        \App\Models\ProductTasksDependency::Where('product_task_dependency_taskid', $task->product_task_id)->delete();

        //[dependencies][cannot complete] - add all
        if (request()->filled('dependencies_cannot_complete') && is_array(request('dependencies_cannot_complete'))) {
            foreach (request('dependencies_cannot_complete') as $blocking_task_id) {
                $dependency = new \App\Models\ProductTasksDependency();
                $dependency->product_task_dependency_taskid = $task->product_task_id;
                $dependency->product_task_dependency_blockerid = $blocking_task_id;
                $dependency->product_task_dependency_type = 'cannot_complete';
                $dependency->save();
            }
        }

        //[dependencies][cannot start] - add all
        if (request()->filled('dependencies_cannot_start') && is_array(request('dependencies_cannot_start'))) {
            foreach (request('dependencies_cannot_start') as $blocking_task_id) {
                $dependency = new \App\Models\ProductTasksDependency();
                $dependency->product_task_dependency_taskid = $task->product_task_id;
                $dependency->product_task_dependency_blockerid = $blocking_task_id;
                $dependency->product_task_dependency_type = 'cannot_start';
                $dependency->save();
            }
        }

        //get all the tasks again
        request()->merge([
            'filter_product_task_itemid' => $task->product_task_itemid,
        ]);
        $tasks = $this->producttaskrepo->search();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings(),
            'tasks' => $tasks,
            'action' => 'create-edit-task',
        ];

        //return the reposnse
        return new TasksIndexResponse($payload);

    }

    /**
     * show the form to edit a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyTask($id) {

        //get the foo
        if (!$task = \App\Models\ProductTask::Where('product_task_id', $id)->first()) {
            abort(404);
        }

        //delete it
        $task->delete();

        //[dependencies] - delete any that are based in this task
        \App\Models\ProductTasksDependency::Where('product_task_dependency_blockerid', $task->id)->delete();

        //reponse payload
        $payload = [
            'id' => $id,
        ];

        //return the reposnse
        return new TasksDeleteResponse($payload);
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
                __('lang.products'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'items',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_sales' => 'active',
            'mainmenu_products' => 'active',
            'submenu_products' => 'active',
            'sidepanel_id' => 'sidepanel-filter-items',
            'dynamic_search_url' => url('items/search?action=search&itemresource_id=' . request('itemresource_id') . '&itemresource_type=' . request('itemresource_type')),
            'add_button_classes' => 'add-edit-item-button',
            'load_more_button_route' => 'items',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => __('lang.add_product'),
            'add_modal_create_url' => url('items/create?itemresource_id=' . request('itemresource_id') . '&itemresource_type=' . request('itemresource_type')),
            'add_modal_action_url' => url('items?itemresource_id=' . request('itemresource_id') . '&itemresource_type=' . request('itemresource_type')),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //items list page
        if ($section == 'items') {
            $page += [
                'meta_title' => __('lang.products'),
                'heading' => __('lang.products'),
                'sidepanel_id' => 'sidepanel-filter-items',
            ];
            if (request('source') == 'ext') {
                $page += [
                    'list_page_actions_size' => 'col-lg-12',
                ];
            }
            if (request('itemresource_type') == 'invoice') {
                $page['dynamic_search_url'] = url('items/search?action=search&itemresource_type=invoice');
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