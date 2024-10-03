<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [destroy] precheck processes for tasks
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Tasks;
use App\Models\Task;
use App\Permissions\TaskPermissions;
use Closure;
use Log;

class ManageDependencies {

    /**
     * The permisson repository instance.
     */
    protected $taskpermissions;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(TaskPermissions $taskpermissions, Task $task_model) {

        //task permissions repo
        $this->taskpermissions = $taskpermissions;

    }

    /**
     * Check user permissions to destroy a task
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //task id
        $task_id = $request->route('task');

        //does the task exist
        if ($task_id == '' || !$task = \App\Models\Task::Where('task_id', $task_id)->first()) {
            abort(409, __('lang.task_not_found'));
        }

        //permission on each one
        if ($this->taskpermissions->check('manage-dependencies', $task_id)) {
            return $next($request);
        }

        //no items were passed with this request
        abort(403);
    }
}
