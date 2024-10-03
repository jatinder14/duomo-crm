<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for ticket statuses
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\TicketStatus;
use Log;

class TicketStatusRepository {

    /**
     * The tickets repository instance.
     */
    protected $status;

    /**
     * Inject dependecies
     */
    public function __construct(TicketStatus $status) {
        $this->status = $status;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object ticket status collection
     */
    public function search($id = '') {

        $status = $this->status->newQuery();

        //joins
        $status->leftJoin('users', 'users.id', '=', 'tickets_status.ticketstatus_creatorid');

        // all client fields
        $status->selectRaw('*');

        //count tickets
        $status->selectRaw('(SELECT COUNT(*)
                                      FROM tickets
                                      WHERE ticket_status = tickets_status.ticketstatus_id)
                                      AS count_tickets');
        if (is_numeric($id)) {
            $status->where('ticketstatus_id', $id);
        }

        //default sorting
        $status->orderBy('ticketstatus_position', 'asc');

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
        $status->ticketstatus_title = preg_replace('%[\[\'"\/\?\\\{}\]]%', '', request('ticketstatus_title'));
        $status->ticketstatus_color = request('ticketstatus_color');

        //save
        if ($status->save()) {
            return $status->ticketstatus_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[TicketStatusRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
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
            Log::error("error creating a new ticket status record in DB - (position) value is invalid", ['process' => '[create()]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //save
        $status = new $this->status;

        //data
        $status->ticketstatus_title = preg_replace('%[\[\'"\/\?\\\{}\]]%', '', request('ticketstatus_title'));
        $status->ticketstatus_color = request('ticketstatus_color');
        $status->ticketstatus_creatorid = auth()->id();
        $status->ticketstatus_position = $position;

        //save and return id
        if ($status->save()) {
            return $status->ticketstatus_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[TicketStatusRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
    

}