<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class Preferences extends Controller {

    public function __construct() {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');
    }

    /**
     * update the users config for a particular table
     * @return blade view | ajax view
     */
    public function updateTableConfig() {

        if (!request()->filled('tableconfig_table_name')) {

            //feedback to browser (for debugging)
            return response()->json(array(
                'preference-updated' => false,
                'error' => 'A table name was not specified',
            ));
        }

        //update for this user, for the specified table
        \App\Models\TableConfig::where('tableconfig_userid', auth()->id())->Where('tableconfig_table_name', request('tableconfig_table_name'))
            ->update([
                'tableconfig_column_1' => (request('tableconfig_column_1') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_2' => (request('tableconfig_column_2') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_3' => (request('tableconfig_column_3') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_4' => (request('tableconfig_column_4') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_5' => (request('tableconfig_column_5') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_6' => (request('tableconfig_column_6') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_7' => (request('tableconfig_column_7') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_8' => (request('tableconfig_column_8') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_9' => (request('tableconfig_column_9') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_10' => (request('tableconfig_column_10') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_11' => (request('tableconfig_column_11') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_12' => (request('tableconfig_column_12') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_13' => (request('tableconfig_column_13') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_14' => (request('tableconfig_column_14') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_15' => (request('tableconfig_column_15') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_16' => (request('tableconfig_column_16') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_17' => (request('tableconfig_column_17') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_18' => (request('tableconfig_column_18') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_19' => (request('tableconfig_column_19') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_20' => (request('tableconfig_column_20') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_21' => (request('tableconfig_column_21') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_22' => (request('tableconfig_column_22') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_23' => (request('tableconfig_column_23') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_24' => (request('tableconfig_column_24') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_25' => (request('tableconfig_column_25') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_26' => (request('tableconfig_column_26') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_27' => (request('tableconfig_column_27') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_28' => (request('tableconfig_column_28') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_29' => (request('tableconfig_column_29') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_30' => (request('tableconfig_column_30') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_31' => (request('tableconfig_column_31') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_32' => (request('tableconfig_column_32') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_33' => (request('tableconfig_column_33') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_34' => (request('tableconfig_column_34') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_35' => (request('tableconfig_column_35') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_36' => (request('tableconfig_column_36') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_37' => (request('tableconfig_column_37') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_38' => (request('tableconfig_column_38') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_39' => (request('tableconfig_column_39') == 'on') ? 'displayed' : 'hidden',
                'tableconfig_column_40' => (request('tableconfig_column_40') == 'on') ? 'displayed' : 'hidden',
            ]);

        //just some feedback to the browser (for debugging)
        return response()->json(array(
            'preference-updated' => true,
        ));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create() {

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('create'),
        ];

        //show the form
        return new CreateResponse($payload);
    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\Response
     */
    public function store() {

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('create'),
        ];

        //process reponse
        return new StoreResponse($payload);

    }

    /**
     * show form to edit a resource
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
        ];

        //process reponse
        return new StoreResponse($payload);

    }

    /**
     * Update a resource
     * @return \Illuminate\Http\Response
     */
    public function update($id) {

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('update'),
        ];

        //process reponse
        return new StoreResponse($payload);

    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        //common settings
        $page = [

        ];

        //return
        return $page;
    }
}