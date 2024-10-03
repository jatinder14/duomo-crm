<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceStatus extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */
    protected $table = 'attendances_status';
    protected $primaryKey = 'attendancestatus_id';
    protected $guarded = ['attendancestatus_id'];
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'attendancestatus_created';
    const UPDATED_AT = 'attendancestatus_updated';

    /**
     * relatioship business rules:
     *         - the Attendance Status can have many attendances
     *         - the Attendance belongs to one Attendance Status
     */
    public function attendances() {
        return $this->hasMany('App\Models\Attendance', 'attendance_status', 'attendancestatus_id');
    }

}
