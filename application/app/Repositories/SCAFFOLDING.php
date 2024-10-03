<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Fooo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Log;


class FoooRepository{



    /**
     * The fooo repository instance.
     */
    protected $fooo;

    /**
     * Inject dependecies
     */
    public function __construct(Fooo $fooo) {
        $this->fooo = $fooo;
    }


        /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object fooos collection
     */
    public function search($id = '') {

        $fooos = $this->fooo->newQuery();

        // all client fields
        $fooos->selectRaw('*');

        //joins
        $fooos->leftJoin('categories', 'categories.category_id', '=', 'fooos.fooo_categoryid');

        //default where
        $fooos->whereRaw("1 = 1");

        //filters: id
        if (request()->filled('filter_fooo_id')) {
            $fooos->where('fooo_id', request('filter_fooo_id'));
        }
        if (is_numeric($id)) {
            $fooos->where('fooo_id', $id);
        }

        //filter: rate (min)
        if (request()->filled('filter_fooo_rate_min')) {
            $fooos->where('fooo_rate', '>=', request('filter_fooo_rate_min'));
        }

        //filter: rate (max)
        if (request()->filled('filter_fooo_rate_max')) {
            $fooos->where('fooo_rate', '>=', request('filter_fooo_rate_max'));
        }

        //filter category
        if (is_array(request('filter_fooo_categoryid')) && !empty(array_filter(request('filter_fooo_categoryid')))) {
            $fooos->whereIn('fooo_categoryid', request('filter_fooo_categoryid'));
        }

        //search: various client columns and relationships (where first, then wherehas)
        if (request()->filled('search_query') || request()->filled('query')) {
            $fooos->where(function ($query) {
                $query->orWhere('fooo_description', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('fooo_rate', '=', request('search_query'));
                $query->orWhere('fooo_unit', '=', request('search_query'));
                $query->orWhereHas('category', function ($q) {
                    $q->where('category_name', 'LIKE', '%' . request('search_query') . '%');
                });
            });
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('fooos', request('orderby'))) {
                $fooos->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'category':
                $fooos->orderBy('category_name', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $fooos->orderBy('fooo_id', 'desc');
        }

        //eager load
        $fooos->with(['category']);

        // Get the results and return them.
        return $fooos->paginate(config('system.settings_system_pagination_limits'));
    }
}