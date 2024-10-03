<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Responses\Reports\Clients\OverviewResponse;
use App\Repositories\Reports\ClientReportRepository;
use DB;

class Clients extends Controller {

    /**
     * The reportrepo repository instance.
     */
    protected $reportrepo;

    public function __construct(ClientReportRepository $reportrepo) {

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
     * grouped by client
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
        $clients = $this->reportrepo->getOverview();

        //get totals
        $totals = [
            'count_projects' => $this->reportrepo->getOverview(null, ['totals' => 'count_projects']),
            'count_projects_pending' => $this->reportrepo->getOverview(null, ['totals' => 'count_projects_pending']),
            'count_projects_completed' => $this->reportrepo->getOverview(null, ['totals' => 'count_projects_completed']),
            'sum_invoices_due' => $this->reportrepo->getOverview(null, ['totals' => 'sum_invoices_due']),
            'sum_invoices_paid' => $this->reportrepo->getOverview(null, ['totals' => 'sum_invoices_paid']),
            'sum_invoices_overdue' => $this->reportrepo->getOverview(null, ['totals' => 'sum_invoices_overdue']),
            'sum_estimates_accepted' => $this->reportrepo->getOverview(null, ['totals' => 'sum_estimates_accepted']),
            'sum_estimates_declined' => $this->reportrepo->getOverview(null, ['totals' => 'sum_estimates_declined']),
            'sum_expenses' => $this->reportrepo->getOverview(null, ['totals' => 'sum_expenses']),
            'sum_expenses_invoiced' => $this->reportrepo->getOverview(null, ['totals' => 'sum_expenses_invoiced']),
            'sum_expenses_not_invoiced' => $this->reportrepo->getOverview(null, ['totals' => 'sum_expenses_not_invoiced']),
            'sum_expenses_not_billable' => $this->reportrepo->getOverview(null, ['totals' => 'sum_expenses_not_billable']),
        ];

        //reponse payload
        $payload = [
            'clients' => $clients,
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
                'breadcrumbs-heading' => __('lang.clients'),
                'breadcrumbs-sub-heading' => __('lang.overview'),
            ];
        }

        //return
        return $page;
    }
}