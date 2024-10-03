<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [show] process for the estimates
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Estimates;
use Illuminate\Contracts\Support\Responsable;

class ShowPublicResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //visibility of tax selector, files section & bill rendering mode
        config([
            'visibility.bill_files_section' => true,
            'visibility.tax_type_selector' => true,
            'bill.render_mode' => 'web'
        ]);

        //render the page
        return view('pages/bill/wrapper-public', compact('page', 'bill', 'taxrates', 'taxes', 'elements', 'units', 'lineitems', 'customfields', 'files'))->render();

    }

}
