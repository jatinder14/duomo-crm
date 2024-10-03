<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @product_task    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\ProductTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProductTaskRepository {

    /**
     * The leads repository instance.
     */
    protected $product_task;

    /**
     * Inject dependecies
     */
    public function __construct(ProductTask $product_task) {
        $this->product_task = $product_task;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object product_tasks collection
     */
    public function search($id = '', $data = []) {

        $product_tasks = $this->product_task->newQuery();

        //default - always apply filters
        if (!isset($data['apply_filters'])) {
            $data['apply_filters'] = true;
        }

        //joins
        $product_tasks->leftJoin('users', 'users.id', '=', 'product_tasks.product_task_creatorid');

        // all client fields
        $product_tasks->selectRaw('*');

        //default where
        $product_tasks->whereRaw("1 = 1");

        //filters: id
        if (request()->filled('filter_product_task_id')) {
            $product_tasks->where('product_task_id', request('filter_product_task_id'));
        }
        if (is_numeric($id)) {
            $product_tasks->where('product_task_id', $id);
        }

        //apply filters
        if ($data['apply_filters']) {

            //filter product_task_itemid id
            if (request()->filled('filter_product_task_itemid')) {
                $product_tasks->where('product_task_itemid', request('filter_product_task_itemid'));
            }

            //filter product_task_itemid id
            if (request()->filled('filter_exclude_tasks') && is_array(request('filter_exclude_tasks'))) {
                $product_tasks->whereNotIn('product_task_id', request('filter_exclude_tasks'));
            }

        }

        //sorting
        $product_tasks->orderBy('product_task_title', 'asc');

        // Get the results and return them.
        return $product_tasks->paginate(1000);
    }
}