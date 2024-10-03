<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the fooo
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Reports;
use Illuminate\Contracts\Support\Responsable;

class StartResponse implements Responsable {

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

        $html = view('pages/reports/start/page')->render();
        $jsondata['dom_html'][] = [
            'selector' => '#embed-content-container',
            'action' => 'replace',
            'value' => $html,
        ];

        //ajax response
        return response()->json($jsondata);
    }

}
