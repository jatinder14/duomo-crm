<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [edit] process for the fooo
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Search;
use Illuminate\Contracts\Support\Responsable;

class IndexResponse implements Responsable {

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

        $page = [];

        //load more button - change the page number and determine buttons visibility
        if ($search_type != 'all') {
            if (count($results) > 0) {
                if ($results->currentPage() < $results->lastPage()) {
                    $url = loadMoreButtonUrl($results->currentPage() + 1, request('source'));
                    $jsondata['dom_attributes'][] = array(
                        'selector' => '#load-more-button',
                        'attr' => 'data-url',
                        'value' => $url);
                    //load more - visible
                    $jsondata['dom_visibility'][] = array('selector' => '.loadmore-button-container', 'action' => 'show');
                    //load more: (intial load - sanity)
                    $page['visibility_show_load_more'] = true;
                    $page['url'] = $url;
                } else {
                    $jsondata['dom_visibility'][] = array('selector' => '.loadmore-button-container', 'action' => 'hide');
                }
            }
        }

        //new or load-more
        if (request('action') == 'load') {
            $action = 'append';
            $selector = '#search-results-container';
        } else {
            $template = 'pages/search/results/results';
            $action = 'replace';
            $selector = '#searchModalBody';
        }

        //render the page
        $html = view($template, compact(
            'search_query',
            'current_category',
            'count',
            'clients',
            'projects',
            'contacts',
            'contracts',
            'tasks',
            'leads',
            'files',
            'attachments',
            'tickets',
            'proposals',
            'page',
            'search_type',
        ))->render();

        $jsondata['dom_html'][] = array(
            'selector' => $selector,
            'action' => $action,
            'value' => $html);
        //ajax response
        return response()->json($jsondata);

    }

}
