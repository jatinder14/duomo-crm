<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SpaceRepository {

    /**
     * The space repository instance.
     */
    protected $space;

    /**
     * Inject dependecies
     */
    public function __construct(Space $space) {
        $this->space = $space;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object spaces collection
     */
    public function search($id = '') {

        $spaces = $this->space->newQuery();

        // all client fields
        $spaces->selectRaw('*');

        //default where
        $spaces->whereRaw("1 = 1");

        //filters: id
        if (request()->filled('filter_space_uniqueid')) {
            $spaces->where('space_uniqueid', request('filter_space_uniqueid'));
        }
        if (is_numeric($id)) {
            $spaces->where('space_id', $id);
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('spaces', request('orderby'))) {
                $spaces->orderBy(request('orderby'), request('sortorder'));
            }
        } else {
            //default sorting
            $spaces->orderBy('space_id', 'desc');
        }

        //eager load
        $spaces->with(['category']);

        // Get the results and return them.
        return $spaces->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * show the form to create a new resource
     *
     * @return \Illuminate\Http\Response
     */
    public function create($userid = null) {

        //save new space
        $space = new $this->spaces;

        $space->space_uniqueid = str_unique();
        $space->space_creatorid = 0;
        $space->space_title = 'my_space';
        $space->space_title_type = 'lang';
        $space->space_owner_id = $ownerid;
        $space->space_type = 'team';

        //save
        if ($space->save()) {
            return $space->space_uniqueid;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[SpaceRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

    }
}