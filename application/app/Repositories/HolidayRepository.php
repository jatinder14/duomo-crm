<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for holidays
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Log;

class HolidayRepository {

    /**
     * The leads repository instance.
     */
    protected $holiday;

    /**
     * Inject dependecies
     */
    public function __construct(Holiday $holiday) {
        $this->holiday = $holiday;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object holiday collection
     */
    public function search($id = '', $data = []) {

        $holidays = $this->holiday->newQuery();

        // all client fields
        $holidays->selectRaw('*');

        //default where
        $holidays->whereRaw("1 = 1");

        if (is_numeric($id)) {
            $holidays->where('holiday_id', $id);
        }

        //filter - date
        if (request()->filled('filter_holiday_date')) {
            $holidays->where('holiday_date', date('Y-m-d', strtotime(request('filter_holiday_date'))));
        }
        
        //filter - description
        if (request()->filled('filter_holiday_description')) {
            $holidays->where('holiday_description', 'LIKE', '%' . request('filter_holiday_description') . '%');
        }

        //search: various client columns and relationships (where first, then wherehas)
        if (request()->filled('search_query') || request()->filled('query')) {
            $holidays->where(function ($query) {
                $query->orWhere('holiday_date', '=', date('Y-m-d', strtotime(request('search_query'))));
                $query->orWhere('holiday_description', 'LIKE', '%' . request('search_query') . '%');
            });
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('holidays', request('orderby'))) {
                $holidays->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'holiday_date':
                $holidays->orderBy('holiday_date', request('sortorder'));
                break;
            case 'holiday_description':
                $holidays->orderBy('holiday_description', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $holidays->orderBy('holiday_id', 'desc');
        }

        //we are not paginating (e.g. when doing exports)
        if (isset($data['no_pagination']) && $data['no_pagination'] === true) {
            return $holidays->get();
        }

        // Get the results and return them.
        return $holidays->paginate(config('system.settings_system_pagination_limits'));
    }
}