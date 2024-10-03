<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */
    protected $primaryKey = 'attendance_id';
    protected $guarded = ['attendance_id'];
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * relatioship business rules:
     *         - the User can have many Attendances
     *         - the Attendance belongs to one User
     */
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * relatioship business rules:
     *         - the User can have many Attendances
     *         - the Attendance belongs to one User
     */
    public function attendancestatus() {
        return $this->belongsTo('App\Models\AttendanceStatus', 'attendance_status', 'attendancestatus_id');
    }
}
