<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update status] process for the tasks
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Tasks;
use Illuminate\Contracts\Support\Responsable;

class UpdateLockedResponse implements Responsable {

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

        //notice error
        $jsondata['notification'] = [
            'type' => 'force-error',
            'value' => __('lang.task_action_permission_error'),
        ];

        //update display text
        $jsondata['dom_html'][] = [
            'selector' => '#card-task-status-text',
            'action' => 'replace',
            'value' => runtimeLang($task->taskstatus_title),
        ];

        //remove loading
        $jsondata['dom_classes'][] = array(
            'selector' => '#card-task-status-text',
            'action' => 'remove',
            'value' => 'loading');

        //kanban view (if we had dragged and dropped)

        if (auth()->user()->pref_view_tasks_layout == 'kanban') {
            
            //kanban - format
            $board['tasks'] = $tasks;
            $html = view('pages/tasks/components/kanban/card', compact('board'))->render();

            //remove from complated board
            $jsondata['dom_visibility'][] = [
                'selector' => '#card_task_' . $task->task_id,
                'action' => 'hide-remove',
            ];

            //return to original board
            $jsondata['dom_html_end'][] = [
                'selector' => '#kanban-board-wrapper-' . $task->task_status,
                'action' => 'prepend',
                'value' => $html,
            ];
        }

        //response
        return response()->json($jsondata);

    }

}
