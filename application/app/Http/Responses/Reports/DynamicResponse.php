<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the fooo
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Reports;
use Illuminate\Contracts\Support\Responsable;

class DynamicResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for fooo members
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        return view('pages/reports/wrapper', compact('page'))->render();

    }

}
