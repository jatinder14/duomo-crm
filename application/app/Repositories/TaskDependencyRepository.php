<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\TaskDependency;
use Illuminate\Http\Request;

class TaskDependencyRepository {

    /**
     * The taskdependency repository instance.
     */
    protected $taskdependency;

    /**
     * Inject dependecies
     */
    public function __construct(TaskDependency $taskdependency) {
        $this->taskdependency = $taskdependency;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object taskdependencys collection
     */
    public function search($id = '') {

        $taskdependencys = $this->taskdependency->newQuery();

        // all client fields
        $taskdependencys->selectRaw('*');

        //joins
        $taskdependencys->leftJoin('tasks', 'tasks.task_id', '=', 'tasks_dependencies.tasksdependency_blockerid');

        //default where
        $taskdependencys->whereRaw("1 = 1");

        //task id
        if(is_numeric($id)){
            $taskdependencys->Where('tasksdependency_taskid', $id);
        }

        //filter: currently blocking
        if (request('filter_currently_blocking')) {
            $taskdependencys->Where('task_status', '!=', 'completed');
        }

        //sorting
        $taskdependencys->orderBy('task_title', 'asc');

        // Get the results and return them.
        return $taskdependencys->paginate(1000);
    }
}