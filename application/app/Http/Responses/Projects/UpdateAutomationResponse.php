<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [edit] process for the projects
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Projects;
use Illuminate\Contracts\Support\Responsable;

class UpdateAutomationResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for projects
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        if (auth()->user()->pref_view_projects_layout == 'list') {
            $html = view('pages/projects/views/list/table/ajax', compact('projects'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => "#project_" . $project->project_id,
                'action' => 'replace-with',
                'value' => $html);
        } else {
            $html = view('pages/projects/views/cards/layout/ajax', compact('projects'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => "#project_" . $project->project_id,
                'action' => 'replace-with',
                'value' => $html);
        }

        if ($project->project_automation_status == 'enabled') {
            $jsondata['dom_visibility'][] = [
                'selector' => '#project-automation-icon',
                'action' => 'show',
            ];
            $jsondata['dom_visibility'][] = [
                'selector' => '#project_automation_icon_'. $project->project_id,
                'action' => 'show',
            ];
        } else {
            $jsondata['dom_visibility'][] = [
                'selector' => '#project-automation-icon',
                'action' => 'hide',
            ];
            $jsondata['dom_visibility'][] = [
                'selector' => '#project_automation_icon_'. $project->project_id,
                'action' => 'hide',
            ];
        }

        //close modals
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //ajax response
        return response()->json($jsondata);
    }

}
