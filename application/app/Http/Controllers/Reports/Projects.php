<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Responses\Reports\Projects\CategoryResponse;
use App\Http\Responses\Reports\Projects\ClientResponse;
use App\Http\Responses\Reports\Projects\OverviewResponse;
use App\Repositories\Reports\ProjectReportRepository;
use DB;

class Projects extends Controller {

    /**
     * The reportrepo repository instance.
     */
    protected $reportrepo;

    public function __construct(ProjectReportRepository $reportrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //route middleware
        $this->middleware('reportsMiddlewareShow')->only([
            'overview',
        ]);

        $this->reportrepo = $reportrepo;
    }

    /**
     * projects overview
     * @return \Illuminate\Http\Response
     */
    public function overview() {

        //default search values
        if (!request()->isMethod('post')) {
            request()->merge([
                'page_limit' => 25,
            ]);
        }

        //search
        $projects = $this->reportrepo->getOverview();

        //get totals
        $totals = [
            'count_tasks_due' => $this->reportrepo->getOverview(null, ['totals' => 'count_tasks_due']),
            'count_tasks_completed' => $this->reportrepo->getOverview(null, ['totals' => 'count_tasks_completed']),
            'sum_hours' => $this->reportrepo->getOverview(null, ['totals' => 'sum_hours']),
            'sum_expenses' => $this->reportrepo->getOverview(null, ['totals' => 'sum_expenses']),
            'sum_invoices' => $this->reportrepo->getOverview(null, ['totals' => 'sum_invoices']),
            'sum_payments' => $this->reportrepo->getOverview(null, ['totals' => 'sum_payments']),
        ];

        //reponse payload
        $payload = [
            'projects' => $projects,
            'page' => $this->pageSettings('overview'),
            'totals' => $totals,
        ];

        //process reponse
        return new OverviewResponse($payload);

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
        $projects = $this->reportrepo->getClient();

        //get totals
        $totals = [
            'count_projects' => $this->reportrepo->getClient(null, ['totals' => 'count_projects']),
            'count_projects_not_started' => $this->reportrepo->getClient(null, ['totals' => 'count_projects_not_started']),
            'count_projects_on_hold' => $this->reportrepo->getClient(null, ['totals' => 'count_projects_on_hold']),
            'count_projects_cancelled' => $this->reportrepo->getClient(null, ['totals' => 'count_projects_cancelled']),
            'count_projects_pending' => $this->reportrepo->getClient(null, ['totals' => 'count_projects_pending']),
            'count_projects_completed' => $this->reportrepo->getClient(null, ['totals' => 'count_projects_completed']),
            'count_tasks_due' => $this->reportrepo->getClient(null, ['totals' => 'count_tasks_due']),
            'count_tasks_completed' => $this->reportrepo->getClient(null, ['totals' => 'count_tasks_completed']),
            'sum_hours' => $this->reportrepo->getClient(null, ['totals' => 'sum_hours']),
            'sum_expenses' => $this->reportrepo->getClient(null, ['totals' => 'sum_expenses']),
            'sum_invoices' => $this->reportrepo->getClient(null, ['totals' => 'sum_invoices']),
            'sum_payments' => $this->reportrepo->getClient(null, ['totals' => 'sum_payments']),
        ];

        //reponse payload
        $payload = [
            'projects' => $projects,
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
    public function category() {

        //default search values
        if (!request()->isMethod('post')) {
            request()->merge([
                'page_limit' => 25,
            ]);
        }

        //search
        $projects = $this->reportrepo->getCategory('projects');

        //get totals
        $totals =   $this->reportrepo->getCategory('totals');


        //reponse payload
        $payload = [
            'projects' => $projects,
            'page' => $this->pageSettings('category'),
            'totals' => $totals,
        ];

        //process reponse
        return new CategoryResponse($payload);

    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        $page = [];

        //overview
        if ($section == 'overview') {
            $page += [
                'breadcrumbs-heading' => __('lang.projects'),
                'breadcrumbs-sub-heading' => __('lang.overview'),
            ];
        }

        //monthly
        if ($section == 'monthly') {
            $page += [
                'breadcrumbs-heading' => __('lang.projects'),
                'breadcrumbs-sub-heading' => __('lang.monthly'),
            ];
        }

        //client
        if ($section == 'client') {
            $page += [
                'breadcrumbs-heading' => __('lang.projects'),
                'breadcrumbs-sub-heading' => __('lang.client'),
            ];
        }

        //project
        if ($section == 'project') {
            $page += [
                'breadcrumbs-heading' => __('lang.projects'),
                'breadcrumbs-sub-heading' => __('lang.project'),
            ];
        }

        //project_category
        if ($section == 'projectcategory') {
            $page += [
                'breadcrumbs-heading' => __('lang.projects'),
                'breadcrumbs-sub-heading' => __('lang.project_category'),
            ];
        }

        //project_category
        if ($section == 'category') {
            $page += [
                'breadcrumbs-heading' => __('lang.projects'),
                'breadcrumbs-sub-heading' => __('lang.project_category'),
            ];
        }

        //return
        return $page;
    }
}