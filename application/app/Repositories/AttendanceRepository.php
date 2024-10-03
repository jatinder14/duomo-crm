<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for attendances
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Log;

class AttendanceRepository {

    /**
     * The leads repository instance.
     */
    protected $attendance;

    /**
     * Inject dependecies
     */
    public function __construct(Attendance $attendance) {
        $this->attendance = $attendance;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object attendance collection
     */
    public function search($id = '', $data = []) {

        $attendances = $this->attendance->newQuery();

        //joins
        $attendances->leftJoin('users', 'users.id', '=', 'attendances.user_id');

        // all client fields
        $attendances->selectRaw('*');

        //default where
        $attendances->whereRaw("1 = 1");

        if (is_numeric($id)) {
            $attendances->where('attendance_id', $id);
        }

        if (auth()->user()->role->role_attendances_scope == 'own') {
            $attendances->whereIn('user_id', [auth()->id()]);
        }

        //filter - users
        if (request()->filled('filter_user_id') && is_array(request('filter_user_id'))) {
            $attendances->whereIn('user_id', request('filter_user_id'));
        }

        //filter - user
        if (request()->filled('filter_user_id') && is_numeric(request('filter_user_id'))) {
            $attendances->where('user_id', request('filter_user_id'));
        }

        //filter - date
        if (request()->filled('filter_date')) {
            $attendances->where('date', date('Y-m-d', strtotime(request('filter_date'))));
        }

        //filter - in_time
        if (request()->filled('filter_in_time')) {
            $attendances->where('in_time', request('filter_in_time'));
        }

        //filter - out_time
        if (request()->filled('filter_out_time')) {
            $attendances->where('out_time', request('filter_out_time'));
        }

        //filter - attendance_status
        if (request()->filled('filter_attendance_status')) {
            $attendances->where('attendance_status', 'LIKE', '%' . request('filter_attendance_status') . '%');
        }

        //filter - reason
        if (request()->filled('filter_reason')) {
            $attendances->where('reason', 'LIKE', '%' . request('filter_reason') . '%');
        }

        //search: various client columns and relationships (where first, then wherehas)
        if (request()->filled('search_query') || request()->filled('query')) {
            $attendances->where(function ($query) {
                $query->Where('first_name', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('last_name', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere(\DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('date', '=', date('Y-m-d', strtotime(request('search_query'))));
                $query->orWhere('date', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('in_time', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('out_time', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('reason', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhereHas('attendancestatus', function($q) {
                    $q->where('attendancestatus_title', 'LIKE', '%' . request('search_query') . '%');
                });
            });
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('attendances', request('orderby'))) {
                $attendances->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'team_member':
                $attendances->orderBy('first_name', request('sortorder'));
                break;
            case 'date':
                $attendances->orderBy('date', request('sortorder'));
                break;
            case 'in_time':
                $attendances->orderBy('in_time', request('sortorder'));
                break;
            case 'out_time':
                $attendances->orderBy('out_time', request('sortorder'));
                break;
            case 'reason':
                $attendances->orderBy('reason', request('sortorder'));
                break;
            case 'attendance_status':
                $attendances->orderBy('attendance_status', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $attendances->orderBy('attendance_id', 'desc');
        }

        //we are not paginating (e.g. when doing exports)
        if (isset($data['no_pagination']) && $data['no_pagination'] === true) {
            return $attendances->get();
        }

        // Get the results and return them.
        return $attendances->paginate(config('system.settings_system_pagination_limits'));
    }
}