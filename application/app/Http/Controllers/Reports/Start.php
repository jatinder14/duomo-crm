<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Responses\Reports\StartResponse;

class Start extends Controller {

    public function __construct() {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //route middleware
        $this->middleware('reportsMiddlewareShow')->only([
            'start',
        ]);

    }

    /**
     * Update a resource
     * @return \Illuminate\Http\Response
     */
    public function showStart() {

        //reponse payload
        $payload = [

        ];

        //process reponse
        return new StartResponse($payload);

    }

}