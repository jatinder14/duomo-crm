<?php
/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the temp settings
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/
namespace App\Http\Responses\Settings\Tap;
use Illuminate\Contracts\Support\Responsable;

class UpdateResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for tap
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //close modal
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        $jsondata['skip_dom_reset'] = true;

        $jsondata['skip_checkboxes_reset'] = true;

        //response
        return response()->json($jsondata);

    }
}
