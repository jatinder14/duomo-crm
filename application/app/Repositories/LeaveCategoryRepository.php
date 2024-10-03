<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for Leave statuses
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\LeaveCategory;
use Log;

class LeaveCategoryRepository {

    /**
     * The Leaves repository instance.
     */
    protected $category;

    /**
     * Inject dependecies
     */
    public function __construct(LeaveCategory $category) {
        $this->category = $category;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object Leave status collection
     */
    public function search($id = '') {

        $category = $this->category->newQuery();

        //joins
        $category->leftJoin('users', 'users.id', '=', 'leaves_categories.leavecategory_creatorid');

        // all client fields
        $category->selectRaw('*');

        //count Leaves
        $category->selectRaw('(SELECT COUNT(*)
                                      FROM leaves
                                      WHERE leave_categoryid = leaves_categories.leavecategory_id)
                                      AS count_leaves');
        if (is_numeric($id)) {
            $category->where('leavecategory_id', $id);
        }

        //default sorting
        $category->orderBy('leavecategory_position', 'asc');

        // Get the results and return them.
        return $category->paginate(10000);
    }

    /**
     * update a record
     * @param int $id record id
     * @return mixed bool or id of record
     */
    public function update($id) {

        //get the record
        if (!$category = $this->category->find($id)) {
            return false;
        }

        //general
        $category->leavecategory_title = preg_replace('%[\[\'"\/\?\\\{}\]]%', '', request('leavecategory_title'));
        $category->leavecategory_color = request('leavecategory_color');

        //save
        if ($category->save()) {
            return $category->leavecategory_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[LeaveCategoryRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * Create a new record
     * @param int $position position of new record
     * @return mixed object|bool
     */
    public function create($position = '') {

        //validate
        if (!is_numeric($position)) {
            Log::error("error creating a new Leave status record in DB - (position) value is invalid", ['process' => '[create()]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //save
        $category = new $this->category;

        //data
        $category->leavecategory_title = preg_replace('%[\[\'"\/\?\\\{}\]]%', '', request('leavecategory_title'));
        $category->leavecategory_color = request('leavecategory_color');
        $category->leavecategory_creatorid = auth()->id();
        $category->leavecategory_position = $position;

        //save and return id
        if ($category->save()) {
            return $category->leavecategory_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[LeaveCategoryRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
    

}