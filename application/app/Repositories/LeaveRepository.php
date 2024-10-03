<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for leaves
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Log;

class LeaveRepository {

    /**
     * The leads repository instance.
     */
    protected $leave;

    /**
     * Inject dependecies
     */
    public function __construct(Leave $leave) {
        $this->leave = $leave;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object leave collection
     */
    public function search($id = '', $data = []) {

        $leaves = $this->leave->newQuery();

        //joins
        $leaves->leftJoin('users', 'users.id', '=', 'leaves.leave_creatorid');

        // all client fields
        $leaves->selectRaw('*');

        //default where
        $leaves->whereRaw("1 = 1");

        if (is_numeric($id)) {
            $leaves->where('leave_id', $id);
        }

        if (auth()->user()->role->role_leaves_scope == 'own') {
            $leaves->whereIn('leave_creatorid', [auth()->id()]);
        }

        //filter - users
        if (request()->filled('filter_leave_creatorid') && is_array(request('filter_leave_creatorid'))) {
            $leaves->whereIn('leave_creatorid', request('filter_leave_creatorid'));
        }

        //filter - user
        if (request()->filled('filter_leave_creatorid') && is_numeric(request('filter_leave_creatorid'))) {
            $leaves->where('leave_creatorid', request('filter_leave_creatorid'));
        }

        //filter - leave_date_from
        if (request()->filled('filter_leave_date_from')) {
            $leaves->where('leave_date_from', date('Y-m-d', strtotime(request('filter_leave_date_from'))));
        }

        //filter - leave_date_to
        if (request()->filled('filter_leave_date_to')) {
            $leaves->where('leave_date_to', date('Y-m-d', strtotime(request('filter_leave_date_to'))));
        }

        
        //filter - leave_category
        if (request()->filled('filter_leave_category')) {
            $leaves->where('leave_categoryid', request('filter_leave_category'));
        }

        //filter - leave_status
        if (request()->filled('filter_leave_status')) {
            $leaves->whereHas('leave_status', request('filter_leave_status'));
        }

        //filter - leave_reason
        if (request()->filled('filter_leave_reason')) {
            $leaves->where('leave_reason', 'LIKE', '%' . request('filter_leave_reason') . '%');
        }

        //filter - leave_comment
        if (request()->filled('filter_leave_comment')) {
            $leaves->where('leave_comment', 'LIKE', '%' . request('filter_leave_comment') . '%');
        }

        //filter - leave_created
        if (request()->filled('filter_leave_created')) {
            $leaves->where('leave_created', date('Y-m-d', strtotime(request('filter_leave_created'))));
        }

        //search: various client columns and relationships (where first, then wherehas)
        if (request()->filled('search_query') || request()->filled('query')) {
            $leaves->where(function ($query) {
                $query->Where('first_name', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('last_name', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere(\DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', '%' . request('search_query') . '%');
                $query->Where('leave_date_from', '=', date('Y-m-d', strtotime(request('search_query'))));
                $query->orWhere('leave_date_from', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('leave_date_to', '=', date('Y-m-d', strtotime(request('search_query'))));
                $query->orWhere('leave_date_to', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('leave_reason', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('leave_comment', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhereHas('leavecategory', function($q) {
                    $q->where('leavecategory_title', 'LIKE', '%' . request('search_query') . '%');
                });
                $query->orWhereHas('leavestatus', function($q) {
                    $q->where('leavestatus_title', 'LIKE', '%' . request('search_query') . '%');
                });
                $query->orWhere('leave_created', '=', date('Y-m-d', strtotime(request('search_query'))));
                $query->orWhere('leave_created', 'LIKE', '%' . request('search_query') . '%');
                
            });
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('leaves', request('orderby'))) {
                $leaves->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
                case 'leave_':
                    $leaves->orderBy('leave_date_from', request('sortorder'));
                    break;
                case 'leave_date_from':
                    $leaves->orderBy('leave_date_from', request('sortorder'));
                    break;
                case 'leave_date_to':
                    $leaves->orderBy('leave_date_to', request('sortorder'));
                    break;
                case 'leave_reason':
                    $leaves->orderBy('leave_reason', request('sortorder'));
                    break;
                case 'leave_categoryid':
                    $leaves->orderBy('leave_categoryid', request('sortorder'));
                    break;
                case 'leave_status':
                    $leaves->orderBy('leave_status', request('sortorder'));
                    break;
                case 'leave_comment':
                    $leaves->orderBy('leave_comment', request('sortorder'));
                    break;
                case 'leave_created':
                    $leaves->orderBy('leave_created', request('sortorder'));
                    break;
            }
        } else {
            //default sorting
            $leaves->orderBy('leave_id', 'desc');
        }

        //we are not paginating (e.g. when doing exports)
        if (isset($data['no_pagination']) && $data['no_pagination'] === true) {
            return $leaves->get();
        }

        // Get the results and return them.
        return $leaves->paginate(config('system.settings_system_pagination_limits'));
    }
}