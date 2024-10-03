<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for Leave statuses
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\AttendanceStatus;
use Log;

class AttendanceStatusRepository {

    /**
     * The Leaves repository instance.
     */
    protected $status;

    /**
     * Inject dependecies
     */
    public function __construct(AttendanceStatus $status) {
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
        $status->leftJoin('users', 'users.id', '=', 'leaves_status.attendancestatus_creatorid');

        // all client fields
        $status->selectRaw('*');

        //count Leaves
        $status->selectRaw('(SELECT COUNT(*)
                                      FROM leaves
                                      WHERE Leave_status = leaves_status.attendancestatus_id)
                                      AS count_leaves');
        if (is_numeric($id)) {
            $status->where('attendancestatus_id', $id);
        }

        //default sorting
        $status->orderBy('attendancestatus_position', 'asc');

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
        $status->attendancestatus_title = preg_replace('%[\[\'"\/\?\\\{}\]]%', '', request('attendancestatus_title'));
        $status->attendancestatus_color = request('attendancestatus_color');

        //save
        if ($status->save()) {
            return $status->attendancestatus_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[AttendanceStatusRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
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
        $status->attendancestatus_title = preg_replace('%[\[\'"\/\?\\\{}\]]%', '', request('attendancestatus_title'));
        $status->attendancestatus_color = request('attendancestatus_color');
        $status->attendancestatus_creatorid = auth()->id();
        $status->attendancestatus_position = $position;

        //save and return id
        if ($status->save()) {
            return $status->attendancestatus_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[AttendanceStatusRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
    

}