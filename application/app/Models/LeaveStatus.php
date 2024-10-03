<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveStatus extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */
    protected $table = 'leaves_status';
    protected $primaryKey = 'leavestatus_id';
    protected $guarded = ['leavestatus_id'];
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'leavestatus_created';
    const UPDATED_AT = 'leavestatus_updated';

    /**
     * relatioship business rules:
     *         - the Leave Status can have many Leaves
     *         - the Leave belongs to one Leave Status
     */
    public function leaves() {
        return $this->hasMany('App\Models\Leave', 'leave_status', 'leavestatus_id');
    }

}
