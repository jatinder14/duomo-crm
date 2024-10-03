<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for Leave statuses
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\LeaveStatus;
use Log;

class LeaveStatusRepository {

    /**
     * The Leaves repository instance.
     */
    protected $status;

    /**
     * Inject dependecies
     */
    public function __construct(LeaveStatus $status) {
        $this->status = $status;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object Leave status collection
     */
    public function search($id = '') {

        $status = $this->status->newQuery();

        //joins
        $status->leftJoin('users', 'users.id', '=', 'leaves_status.leavestatus_creatorid');

        // all client fields
        $status->selectRaw('*');

        //count Leaves
        $status->selectRaw('(SELECT COUNT(*)
                                      FROM leaves
                                      WHERE Leave_status = leaves_status.leavestatus_id)
                                      AS count_leaves');
        if (is_numeric($id)) {
            $status->where('leavestatus_id', $id);
        }

        //default sorting
        $status->orderBy('leavestatus_position', 'asc');

        // Get the results and return them.
        return $status->paginate(10000);
    }

    /**
     * update a record
     * @param int $id record id
     * @return mixed bool or id of record
     */
    public function update($id) {

        //get the record
        if (!$status = $this->status->find($id)) {
            return false;
        }

        //general
        $status->leavestatus_title = preg_replace('%[\[\'"\/\?\\\{}\]]%', '', request('leavestatus_title'));
        $status->leavestatus_color = request('leavestatus_color');

        //save
        if ($status->save()) {
            return $status->leavestatus_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[LeaveStatusRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
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
        $status = new $this->status;

        //data
        $status->leavestatus_title = preg_replace('%[\[\'"\/\?\\\{}\]]%', '', request('leavestatus_title'));
        $status->leavestatus_color = request('leavestatus_color');
        $status->leavestatus_creatorid = auth()->id();
        $status->leavestatus_position = $position;

        //save and return id
        if ($status->save()) {
            return $status->leavestatus_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[LeaveStatusRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
    

}