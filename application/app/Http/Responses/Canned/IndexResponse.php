<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [index] process for the canned
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Canned;
use Illuminate\Contracts\Support\Responsable;

class IndexResponse implements Responsable {

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

        //was this call made from an embedded page/ajax or directly on canned page
        if (request('source') == 'ext' || request('action') == 'search' || request()->ajax()) {

            //template and dom - for additional ajax loading
            switch (request('action')) {

            //typically from the loadmore button
            case 'load':
                $template = 'pages/canned/components/table/ajax';
                $dom_container = '#canned-td-container';
                $dom_action = 'append';
                break;

            //from the sorting links
            case 'sort':
                $template = 'pages/canned/components/table/ajax';
                $dom_container = '#canned-td-container';
                $dom_action = 'replace';
                break;

            //from search box or filter panel
            case 'search':
                $template = 'pages/canned/components/table/table';
                $dom_container = '#canned-table-container';
                $dom_action = 'replace-with';
                break;

            //template and dom - for ajax initial loading
            default:
                $template = 'pages/canned/components/table/table';
                $dom_container = '#canned-table-container';
                $dom_action = 'replace';
                break;
            }

            //load more button - change the page number and determine buttons visibility
            if ($canned_responses->currentPage() < $canned_responses->lastPage()) {
                $url = loadMoreButtonUrl($canned_responses->currentPage() + 1, request('source'));
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
            $html = view($template, compact('page', 'canned_responses', 'category_list'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => $dom_container,
                'action' => $dom_action,
                'value' => $html);

            //for embedded - change breadcrumb title
            $jsondata['dom_html'][] = [
                'selector' => '.active-bread-crumb',
                'action' => 'replace',
                'value' => strtoupper(__('lang.canned')),
            ];

            //update canned add url
            $jsondata['dom_attributes'][] = [
                'selector' => '#canned-add-button',
                'attr' => 'data-action-url',
                'value' => 'canned?filter_categoryid=' . request('filter_categoryid'),
            ];

            //make menu active
            $jsondata['dom_classes'][] = [
                'selector' => '.canned_category',
                'action' => 'remove',
                'value' => 'active',
            ];
            $jsondata['dom_classes'][] = [
                'selector' => '#canned_category_' . request('filter_categoryid'),
                'action' => 'add',
                'value' => 'active',
            ];

            //ajax response
            return response()->json($jsondata);

        } else {
            //standard view
            $page['url'] = loadMoreButtonUrl($canned_responses->currentPage() + 1, request('source'));
            $page['loading_target'] = 'canned-td-container';
            $page['visibility_show_load_more'] = ($canned_responses->currentPage() < $canned_responses->lastPage()) ? true : false;
            return view('pages/canned/wrapper', compact('page', 'canned_responses', 'category_list'))->render();
        }

    }

}
