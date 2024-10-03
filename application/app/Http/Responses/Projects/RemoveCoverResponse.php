<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the projects
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Projects;
use Illuminate\Contracts\Support\Responsable;

class RemoveCoverResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for team members
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //update card
        $html = view('pages/projects/views/cards/layout/ajax', compact('projects'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => "#project_" . $project->project_id,
            'action' => 'replace-with',
            'value' => $html);

        //notice error
        $jsondata['notification'] = [
            'type' => 'success',
            'value' => __('lang.request_has_been_completed'),
        ];

        //close modal
        $jsondata['dom_visibility'][] = [
            'selector' => '#commonModal', 'action' => 'close-modal',
        ];

        //update image on project page
        $jsondata['dom_visibility'][] = [
            'selector' => '.project-cover-image-wrapper',
            'action' => 'hide',
        ];

        //response
        return response()->json($jsondata);
    }

}
