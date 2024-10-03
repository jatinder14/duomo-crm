<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [attach files] process for the tasks
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Estimates;
use Illuminate\Contracts\Support\Responsable;

class AttachFilesResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for comments
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //prepend content on top of list
        $html = view('pages/bill/components/elements/attachment', compact('file'))->render();
        $jsondata['attachment'] = $html;

        //response
        return response()->json($jsondata);
    }
}
