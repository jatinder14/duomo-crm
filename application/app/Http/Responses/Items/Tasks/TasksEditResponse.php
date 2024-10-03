<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the project
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Items\Tasks;
use Illuminate\Contracts\Support\Responsable;

class TasksEditResponse implements Responsable {

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

        //render the form
        $html = view('pages/itemtasks/modals/add-edit-inc', compact('task', 'assigned', 'tasks', 'cannot_complete_dependencies','cannot_start_dependencies'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $jsondata['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        // POSTRUN FUNCTIONS------
        $jsondata['postrun_functions'][] = [
            'value' => 'NXCreateProductTask',
        ];

        //ajax response
        return response()->json($jsondata);

    }

}