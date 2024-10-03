<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Responses\Reports\Timesheets\ClientResponse;
use App\Http\Responses\Reports\Timesheets\TeamResponse;
use App\Http\Responses\Reports\Timesheets\ProjectResponse;
use App\Repositories\Reports\TimesheetReportRepository;

class Timesheets extends Controller {

    /**
     * The reportrepo repository instance.
     */
    protected $reportrepo;

    public function __construct(TimesheetReportRepository $reportrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //route middleware
        $this->middleware('reportsMiddlewareShow')->only([
            'team',
        ]);

        $this->reportrepo = $reportrepo;
    }

    /**
     * grouped by client
     * @return \Illuminate\Http\Response
     */
    public function team() {

        //default search values
        if (!request()->isMethod('post')) {
            request()->merge([
                'page_limit' => 25,
            ]);
        }

        //search
        $timesheets = $this->reportrepo->getTeam();

        //get totals
        $totals = [
            'sum_hours' => $this->reportrepo->getTeam(null, ['totals' => 'sum_hours']),
            'sum_not_invoiced' => $this->reportrepo->getTeam(null, ['totals' => 'sum_not_invoiced']),
            'sum_invoiced' => $this->reportrepo->getTeam(null, ['totals' => 'sum_invoiced']),
        ];

        //reponse payload
        $payload = [
            'timesheets' => $timesheets,
            'page' => $this->pageSettings('team'),
            'totals' => $totals,
        ];

        //process reponse
        return new TeamResponse($payload);

    }

    /**
     * grouped by client
     * @return \Illuminate\Http\Response
     */
    public function client() {

        //default search values
        if (!request()->isMethod('post')) {
            request()->merge([
                'page_limit' => 25,
            ]);
        }

        //search
        $timesheets = $this->reportrepo->getClient();

        //get totals
        $totals = [
            'sum_hours' => $this->reportrepo->getClient(null, ['totals' => 'sum_hours']),
            'sum_not_invoiced' => $this->reportrepo->getClient(null, ['totals' => 'sum_not_invoiced']),
            'sum_invoiced' => $this->reportrepo->getClient(null, ['totals' => 'sum_invoiced']),
        ];

        //reponse payload
        $payload = [
            'timesheets' => $timesheets,
            'page' => $this->pageSettings('client'),
            'totals' => $totals,
        ];

        //process reponse
        return new ClientResponse($payload);

    }

    /**
     * grouped by client
     * @return \Illuminate\Http\Response
     */
    public function project() {

        //default search values
        if (!request()->isMethod('post')) {
            request()->merge([
                'page_limit' => 25,
            ]);
        }

        //search
        $timesheets = $this->reportrepo->getProject();

        //get totals
        $totals = [
            'sum_hours' => $this->reportrepo->getProject(null, ['totals' => 'sum_hours']),
            'sum_not_invoiced' => $this->reportrepo->getProject(null, ['totals' => 'sum_not_invoiced']),
            'sum_invoiced' => $this->reportrepo->getProject(null, ['totals' => 'sum_invoiced']),
        ];

        //reponse payload
        $payload = [
            'timesheets' => $timesheets,
            'page' => $this->pageSettings('project'),
            'totals' => $totals,
        ];

        //process reponse
        return new ProjectResponse($payload);

    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        $page = [];

        //client
        if ($section == 'client') {
            $page += [
                'breadcrumbs-heading' => __('lang.time_sheets'),
                'breadcrumbs-sub-heading' => __('lang.client'),
            ];
        }

        //project
        if ($section == 'project') {
            $page += [
                'breadcrumbs-heading' => __('lang.time_sheets'),
                'breadcrumbs-sub-heading' => __('lang.project'),
            ];
        }

        //project_category
        if ($section == 'team') {
            $page += [
                'breadcrumbs-heading' => __('lang.time_sheets'),
                'breadcrumbs-sub-heading' => __('lang.team_members'),
            ];
        }

        //return
        return $page;
    }
}