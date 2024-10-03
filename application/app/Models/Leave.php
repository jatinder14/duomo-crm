<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */
    protected $primaryKey = 'leave_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['leave_id'];
    const CREATED_AT = 'leave_created';
    const UPDATED_AT = 'leave_updated';

    /**
     * relatioship business rules:
     *         - the Creator (user) can have many Leaves
     *         - the Leave belongs to one Creator (user)
     */
    public function creator() {
        return $this->belongsTo('App\Models\User', 'leave_creatorid', 'id');
    }

    /**
     * relatioship business rules:
     *         - the Updator (user) can have many Leaves
     *         - the Leave belongs to one Updator (user)
     */
    public function updator() {
        return $this->belongsTo('App\Models\User', 'leave_updatorid', 'id');
    }

    /**
     * relatioship business rules:
     *         - the User can have many Leaves
     *         - the Leave belongs to one User
     */
    public function leavecategory() {
        return $this->belongsTo('App\Models\LeaveCategory', 'leave_categoryid', 'leavecategory_id');
    }

    /**
     * relatioship business rules:
     *         - the User can have many Leaves
     *         - the Leave belongs to one User
     */
    public function leavestatus() {
        return $this->belongsTo('App\Models\LeaveStatus', 'leave_status', 'leavestatus_id');
    }
}
