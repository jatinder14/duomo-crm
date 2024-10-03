<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the tasks
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Tasks;
use Illuminate\Contracts\Support\Responsable;

class UpdateTagsResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for tasks
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //full payload array
        $payload = $this->payload;

        $html = view('pages.task.components.tags', compact('tags', 'task', 'current_tags'))->render();
        $jsondata['dom_html'][] = [
            'selector' => '#card-tags-container',
            'action' => 'replace',
            'value' => $html,
        ];

        //update whole card (for kanban view)
        $board['tasks'] = $tasks;
        $html = view('pages/tasks/components/kanban/card', compact('board'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => "#card_task_" . $task->task_id,
            'action' => 'replace-with',
            'value' => $html);

        //response
        return response()->json($jsondata);

    }

}
