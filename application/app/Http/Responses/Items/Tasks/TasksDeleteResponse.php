<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the project
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Items\Tasks;
use Illuminate\Contracts\Support\Responsable;

class TasksDeleteResponse implements Responsable {

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

        //hide the row
        $jsondata['dom_visibility'][] = [
            'selector' => "#task_$id",
            'action' => 'hide-remove',
        ];

        //ajax response
        return response()->json($jsondata);

    }

}