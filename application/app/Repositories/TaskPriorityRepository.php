<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for task priorityes
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\TaskPriority;
use Log;

class TaskPriorityRepository {

    /**
     * The tasks repository instance.
     */
    protected $priority;

    /**
     * Inject dependecies
     */
    public function __construct(TaskPriority $priority) {
        $this->priority = $priority;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object task priority collection
     */
    public function search($id = '') {

        $priority = $this->priority->newQuery();

        //joins
        $priority->leftJoin('users', 'users.id', '=', 'tasks_priority.taskpriority_creatorid');

        // all client fields
        $priority->selectRaw('*');

        //count tasks
        $priority->selectRaw('(SELECT COUNT(*)
                                      FROM tasks
                                      WHERE task_priority = tasks_priority.taskpriority_id)
                                      AS count_tasks');
        if (is_numeric($id)) {
            $priority->where('taskpriority_id', $id);
        }

        //default sorting
        $priority->orderBy('taskpriority_position', 'asc');

        // Get the results and return them.
        return $priority->paginate(10000);
    }

    /**
     * update a record
     * @param int $id record id
     * @return mixed bool or id of record
     */
    public function update($id) {

        //get the record
        if (!$priority = $this->priority->find($id)) {
            return false;
        }

        //general
        $priority->taskpriority_title = preg_replace('%[\[\'"\/\?\\\{}\]]%', '', request('taskpriority_title'));
        $priority->taskpriority_color = request('taskpriority_color');

        //save
        if ($priority->save()) {
            return $priority->taskpriority_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[TaskPriorityRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
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
            Log::error("error creating a new task priority record in DB - (position) value is invalid", ['process' => '[create()]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //save
        $priority = new $this->priority;

        //data
        $priority->taskpriority_title = preg_replace('%[\[\'"\/\?\\\{}\]]%', '', request('taskpriority_title'));
        $priority->taskpriority_color = request('taskpriority_color');
        $priority->taskpriority_creatorid = auth()->id();
        $priority->taskpriority_position = $position;

        //save and return id
        if ($priority->save()) {
            return $priority->taskpriority_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[TaskPriorityRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
    

}