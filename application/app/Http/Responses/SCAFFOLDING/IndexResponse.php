<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [index] process for the fooo
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Fooos;
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

        //was this call made from an embedded page/ajax or directly on fooo page
        if (request('source') == 'ext' || request('action') == 'search' || request()->ajax()) {

            //template and dom - for additional ajax loading
            switch (request('action')) {

            //typically from the loadmore button
            case 'load':
                $template = 'pages/fooos/components/table/ajax';
                $dom_container = '#fooo-td-container';
                $dom_action = 'append';
                break;

            //from the sorting links
            case 'sort':
                $template = 'pages/fooos/components/table/ajax';
                $dom_container = '#fooo-td-container';
                $dom_action = 'replace';
                break;

            //from search box or filter panel
            case 'search':
                $template = 'pages/fooos/components/table/table';
                $dom_container = '#fooo-table-wrapper';
                $dom_action = 'replace-with';
                break;

            //template and dom - for ajax initial loading
            default:
                $template = 'pages/fooos/tabswrapper';
                $dom_container = '#embed-content-container';
                $dom_action = 'replace';
                break;
            }

            //load more button - change the page number and determine buttons visibility
            if ($fooos->currentPage() < $fooos->lastPage()) {
                $url = loadMoreButtonUrl($fooos->currentPage() + 1, request('source'));
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

            //flip sorting url for this particular link - only is we clicked sort menu links
            if (request('action') == 'sort') {
                $sort_url = flipSortingUrl(request()->fullUrl(), request('sortorder'));
                $element_id = '#sort_' . request('orderby');
                $jsondata['dom_attributes'][] = array(
                    'selector' => $element_id,
                    'attr' => 'data-url',
                    'value' => $sort_url);
            }

            //render the view and save to json
            $html = view($template, compact('page', 'fooos'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => $dom_container,
                'action' => $dom_action,
                'value' => $html);

            //for embedded - change breadcrumb title
            $jsondata['dom_html'][] = [
                'selector' => '.active-bread-crumb',
                'action' => 'replace',
                'value' => strtoupper(__('lang.fooo')),
            ];

            //ajax response
            return response()->json($jsondata);

        } else {
            //standard view
            $page['url'] = loadMoreButtonUrl($fooos->currentPage() + 1, request('source'));
            $page['loading_target'] = 'fooo-td-container';
            $page['visibility_show_load_more'] = ($fooos->currentPage() < $fooos->lastPage()) ? true : false;
            return view('pages/fooos/wrapper', compact('page', 'fooos'))->render();
        }

    }

}
