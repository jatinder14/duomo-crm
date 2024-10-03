<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Canned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CannedRepository {

    /**
     * The canned repository instance.
     */
    protected $canned;

    /**
     * Inject dependecies
     */
    public function __construct(Canned $canned) {
        $this->canned = $canned;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object canned collection
     */
    public function search($id = '') {

        $canned = $this->canned->newQuery();

        // all client fields
        $canned->selectRaw('*');

        //joins
        $canned->leftJoin('categories', 'categories.category_id', '=', 'canned.canned_categoryid');
        $canned->leftJoin('users', 'users.id', '=', 'canned.canned_creatorid');

        //default where
        $canned->whereRaw("1 = 1");

        //filters: id
        if (request()->filled('filter_canned_id')) {
            $canned->where('canned_id', request('filter_canned_id'));
        }
        if (is_numeric($id)) {
            $canned->where('canned_id', $id);
        }

        //filter: rate (min)
        if (request()->filled('filter_canned_rate_min')) {
            $canned->where('canned_rate', '>=', request('filter_canned_rate_min'));
        }

        //filter: rate (max)
        if (request()->filled('filter_canned_rate_max')) {
            $canned->where('canned_rate', '>=', request('filter_canned_rate_max'));
        }

        //filter category
        if (request()->filled('filter_categoryid')) {
            $canned->where('canned_categoryid', request('filter_categoryid'));
        }

        if (request()->filled('filter_index_categoryid')) {
            $canned->where('canned_categoryid', request('filter_index_categoryid'));
        }

        //browse category
        if (request()->filled('browse_canned')) {
            $canned->where('canned_categoryid', request('browse_canned'));
        }

        //public or mine
        if (request('show_type') == 'own') {
            $canned->where('canned_creatorid', auth()->id());
        } else {
            $canned->where(function ($query) {
                $query->Where('canned_visibility', 'public');
                $query->orWhere('canned_creatorid', auth()->id());
            });
        }

        //search: form
        if (request()->filled('search_canned') || request()->filled('query')) {
            $canned->where(function ($query) {
                $query->orWhere('canned_title', 'LIKE', '%' . request('search_canned') . '%');
            });
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('canned', request('orderby'))) {
                $canned->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'category':
                $canned->orderBy('category_name', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $canned->orderBy('canned_id', 'desc');
        }

        //eager load
        $canned->with(['category']);

        // Get the results and return them.
        return $canned->paginate(config('system.settings_system_pagination_limits'));
    }
}