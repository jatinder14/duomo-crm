<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the files
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Files;
use Illuminate\Contracts\Support\Responsable;

class BulkDownloadResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for files
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //[action options] add|remove
        $jsondata['dom_classes'][] = [
            'selector' => 'html',
            'action' => 'remove',
            'value' => 'nprogress-busy',
        ];

        //[action options] add|remove
        $jsondata['dom_classes'][] = [
            'selector' => '#files-bulk-download-button',
            'action' => 'remove',
            'value' => 'button-loading-annimation',
        ];

        //delayed redirect
        $jsondata['delayed_redirect_url'] = url("/storage/temp/$temp_directory/files.zip");

        //ajax response
        return response()->json($jsondata);

    }

}
