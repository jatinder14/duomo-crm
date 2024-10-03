<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the project
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Messages;
use Illuminate\Contracts\Support\Responsable;

class IndexResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for project members
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        return view('pages/messages/wrapper', compact('page', 'users'))->render();

    }

}