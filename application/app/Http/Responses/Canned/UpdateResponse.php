<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the canned
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Canned;
use Illuminate\Contracts\Support\Responsable;

class UpdateResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for canned members
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //replace the row of this record
        $html = view('pages/canned/components/table/ajax', compact('canned_responses'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => "#canned_" . $id,
            'action' => 'replace-with',
            'value' => $html);

        //for own profile, replace user name in top nav
        if ($request->input('id') == auth()->id()) {
            $jsondata['dom_html'][] = array(
                'selector' => "#topnav_username",
                'action' => 'replace',
                'value' => safestr($request->input('first_name')));
        }

        //did category change
        if ($old_category != $new_category) {
            //hide and remove all deleted rows
            $jsondata['dom_visibility'][] = array(
                'selector' => '#canned_' . $id,
                'action' => 'slideup-slow-remove',
            );

            //update counter
            $jsondata['dom_html'][] = [
                'selector' => '#canned_category_count_' . $new_category,
                'action' => 'replace',
                'value' => $count_new_category,
            ];

            $jsondata['dom_html'][] = [
                'selector' => '#canned_category_count_' . $old_category,
                'action' => 'replace',
                'value' => $count_old_category,
            ];

        }

        //close modal
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);

    }

}
