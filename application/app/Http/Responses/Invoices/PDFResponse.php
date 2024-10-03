<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [pdf] process for the invoices
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Invoices;
use App\Http\Responses\Invoices\PDFResponse;
use Illuminate\Contracts\Support\Responsable;
use PDF;

class PDFResponse implements Responsable {

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

        //[debugging purposes] view invoice in browser (https://domain.com/invoice/1/pdf?view=preview)
        if (request('view') == 'preview') {
            config([
                'css.bill_mode' => 'pdf-mode-preview',
                'bill.render_mode' => 'web',
            ]);
            return view('pages/bill/bill-pdf', compact('page', 'bill', 'taxrates', 'taxes', 'elements', 'lineitems', 'customfields'))->render();
        }

        //visibility render mode & css mode
        config([
            'css.bill_mode' => 'pdf-mode-download',
            'bill.render_mode' => 'web',
        ]);

        //render the bill
        $pdf = PDF::loadView('pages/bill/bill-pdf', compact('page', 'bill', 'taxrates', 'taxes', 'elements', 'lineitems', 'customfields'));
        $filename = strtoupper(__('lang.invoice')) . '-' . $bill->formatted_bill_invoiceid . '.pdf'; //invoice_inv0001.pdf
        return $pdf->download($filename);
    }
}
