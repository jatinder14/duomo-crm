<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [edit] process for the fooo
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Items;
use Illuminate\Contracts\Support\Responsable;

class CategoryItemsResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for fooo members
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
        $html = view('pages/items/components/modals/category-items', compact('categories'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#categoryItemsModalBody',
            'action' => 'replace',
            'value' => $html);

        $jsondata['skip_checkboxes_reset'] = true;

        $jsondata['skip_dom_reset'] = true;

        $jsondata['skip_dom_tinymce'] = true;


        //ajax response
        return response()->json($jsondata);
    }

}
