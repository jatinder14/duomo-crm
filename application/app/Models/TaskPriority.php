<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskPriority extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */
    protected $table = 'tasks_priority';
    protected $primaryKey = 'taskpriority_id';
    protected $guarded = ['taskpriority_id'];
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'taskpriority_created';
    const UPDATED_AT = 'taskpriority_updated';

    /**
     * relatioship business rules:
     *         - the Task Status can have many Tasks
     *         - the Task belongs to one Task Status
     */
    public function tasks() {
        return $this->hasMany('App\Models\Task', 'task_priority', 'taskpriority_id');
    }

}
