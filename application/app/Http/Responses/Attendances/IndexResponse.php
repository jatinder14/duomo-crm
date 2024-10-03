<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [index] process for the attendances
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Attendances;
use Illuminate\Contracts\Support\Responsable;

class IndexResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for attendances
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //was this call made from an embedded page/ajax or directly on temp page
        if (request('source') == 'ext' || request('action') == 'search' || request()->ajax()) {

            //template and dom - for additional ajax loading
            switch (request('action')) {

            //typically from the loadmore button
            case 'load':
                $template = 'pages/attendances/components/table/ajax';
                $dom_container = '#attendances-td-container';
                $dom_action = 'append';
                break;

            //from the sorting links
            case 'sort':
                $template = 'pages/attendances/components/table/ajax';
                $dom_container = '#attendances-td-container';
                $dom_action = 'replace';
                break;

            //from search box or filter panel
            case 'search':
                $template = 'pages/attendances/components/table/table';
                $dom_container = '#attendances-table-wrapper';
                $dom_action = 'replace';
                break;

            //template and dom - for ajax initial loading
            default:
                $template = 'pages/attendances/tabswrapper';
                $dom_container = '#embed-content-container';
                $dom_action = 'replace';
                break;
            }

            //load more button - change the page number and determine buttons visibility
            if ($attendances->currentPage() < $attendances->lastPage()) {
                $url = loadMoreButtonUrl($attendances->currentPage() + 1, request('source'));
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
            $html = view($template, compact('page', 'attendances'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => $dom_container,
                'action' => $dom_action,
                'value' => $html);

            //move the actions buttons
            if (request('source') == 'ext' && request('action') == '') {
                $jsondata['dom_move_element'][] = array(
                    'element' => '#list-page-actions',
                    'newparent' => '.parent-page-actions',
                    'method' => 'replace',
                    'visibility' => 'show');
                $jsondata['dom_visibility'][] = [
                    'selector' => '#list-page-actions-container',
                    'action' => 'show',
                ];
            }

            //for embedded - change breadcrumb title
            $jsondata['dom_html'][] = [
                'selector' => '.active-bread-crumb',
                'action' => 'replace',
                'value' => strtoupper(__('lang.attendances')),
            ];

            //for embedded request -change active tabs menu
            $jsondata['dom_classes'][] = [
                'selector' => '.tabs-menu-item',
                'action' => 'remove',
                'value' => 'active',
            ];
            $jsondata['dom_classes'][] = [
                'selector' => '#tabs-menu-billing, #tabs-menu-expenses',
                'action' => 'add',
                'value' => 'active',
            ];

            //remove kanban mode
            $jsondata['dom_classes'][] = [
                'selector' => '#main-body',
                'action' => 'remove',
                'value' => 'kanban',
            ];

            //update export URL
            $query_params = urlQuery();
            $export_url = url('/export/attendances');
            if (!empty($query_params)) {
                $export_url .= '?' . http_build_query($query_params);
            }
            $jsondata['dom_attributes'][] = [
                'selector' => '#export-attendances-button',
                'attr' => 'data-url',
                'value' => $export_url,
            ];

            //ajax response
            return response()->json($jsondata);

        } else {
            //standard view
            $page['url'] = loadMoreButtonUrl($attendances->currentPage() + 1, request('source'));
            $page['loading_target'] = 'attendances-td-container';
            $page['visibility_show_load_more'] = ($attendances->currentPage() < $attendances->lastPage()) ? true : false;
            return view('pages/attendances/wrapper', compact('page', 'attendances', 'statuses'))->render();
        }

    }

}
