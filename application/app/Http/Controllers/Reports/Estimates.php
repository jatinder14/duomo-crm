<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Responses\Reports\Estimates\CategoryResponse;
use App\Http\Responses\Reports\Estimates\ClientResponse;
use App\Http\Responses\Reports\Estimates\MonthlyResponse;
use App\Http\Responses\Reports\Estimates\OverviewResponse;
use App\Http\Responses\Reports\Estimates\ProjectResponse;
use App\Repositories\Reports\EstimateReportRepository;

class Estimates extends Controller {

    /**
     * The reportrepo repository instance.
     */
    protected $reportrepo;

    public function __construct(EstimateReportRepository $reportrepo) {

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
     * estimates overview
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
        $estimates = $this->reportrepo->getOverview();

        //get totals
        $totals = [
            'sum_before_tax' => $this->reportrepo->getOverview(null, ['totals' => 'sum_before_tax']),
            'sum_tax' => $this->reportrepo->getOverview(null, ['totals' => 'sum_tax']),
            'sum_discount' => $this->reportrepo->getOverview(null, ['totals' => 'sum_discount']),
            'sum_adjustment' => $this->reportrepo->getOverview(null, ['totals' => 'sum_adjustment']),
            'sum_final_amount' => $this->reportrepo->getOverview(null, ['totals' => 'sum_final_amount']),
            'sum_balance_due' => $this->reportrepo->getOverview(null, ['totals' => 'sum_balance_due']),
        ];

        //reponse payload
        $payload = [
            'estimates' => $estimates,
            'page' => $this->pageSettings('overview'),
            'totals' => $totals,
        ];

        //process reponse
        return new OverviewResponse($payload);

    }

    /**
     * grouped by months
     * @return \Illuminate\Http\Response
     */
    public function month() {

        //default search values
        if (!request()->isMethod('post')) {
            request()->merge([
                'filter_year' => now()->year,
            ]);
        }

        //search
        $estimates = $this->reportrepo->getMonths();

        //get totals
        $totals = [
            'count_estimates' => $this->reportrepo->getMonths(null, ['totals' => 'count_estimates']),
            'sum_before_tax' => $this->reportrepo->getMonths(null, ['totals' => 'sum_before_tax']),
            'sum_tax' => $this->reportrepo->getMonths(null, ['totals' => 'sum_tax']),
            'sum_discount' => $this->reportrepo->getMonths(null, ['totals' => 'sum_discount']),
            'sum_adjustment' => $this->reportrepo->getMonths(null, ['totals' => 'sum_adjustment']),
            'sum_final_amount' => $this->reportrepo->getMonths(null, ['totals' => 'sum_final_amount']),
        ];


        //reponse payload
        $payload = [
            'estimates' => $estimates,
            'page' => $this->pageSettings('monthly'),
            'totals' => $totals,
            'years' => $this->reportrepo->getYearsRange(),
        ];

        //process reponse
        return new MonthlyResponse($payload);

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
        $estimates = $this->reportrepo->getClient();

        //get totals
        $totals = [
            'sum_before_tax' => $this->reportrepo->getClient(null, ['sum' => 'sum_before_tax']),
            'sum_tax' => $this->reportrepo->getClient(null, ['sum' => 'sum_tax']),
            'sum_discount' => $this->reportrepo->getClient(null, ['sum' => 'sum_discount']),
            'sum_adjustment' => $this->reportrepo->getClient(null, ['sum' => 'sum_adjustment']),
            'sum_final_amount' => $this->reportrepo->getClient(null, ['sum' => 'sum_final_amount']),
            'sum_estimate_count' => $this->reportrepo->getClient(null, ['sum' => 'sum_estimate_count']),
        ];

        //reponse payload
        $payload = [
            'estimates' => $estimates,
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
        $estimates = $this->reportrepo->getCategory();

        //get totals
        $totals = [
            'sum_before_tax' => $this->reportrepo->getCategory(null, ['sum' => 'sum_before_tax']),
            'sum_tax' => $this->reportrepo->getCategory(null, ['sum' => 'sum_tax']),
            'sum_discount' => $this->reportrepo->getCategory(null, ['sum' => 'sum_discount']),
            'sum_adjustment' => $this->reportrepo->getCategory(null, ['sum' => 'sum_adjustment']),
            'sum_final_amount' => $this->reportrepo->getCategory(null, ['sum' => 'sum_final_amount']),
            'sum_estimate_count' => $this->reportrepo->getCategory(null, ['sum' => 'sum_estimate_count']),
        ];

        //reponse payload
        $payload = [
            'estimates' => $estimates,
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
                'breadcrumbs-heading' => __('lang.estimates'),
                'breadcrumbs-sub-heading' => __('lang.overview'),
            ];
        }

        //monthly
        if ($section == 'monthly') {
            $page += [
                'breadcrumbs-heading' => __('lang.estimates'),
                'breadcrumbs-sub-heading' => __('lang.monthly'),
            ];
        }

        //client
        if ($section == 'client') {
            $page += [
                'breadcrumbs-heading' => __('lang.estimates'),
                'breadcrumbs-sub-heading' => __('lang.client'),
            ];
        }

        //project
        if ($section == 'project') {
            $page += [
                'breadcrumbs-heading' => __('lang.estimates'),
                'breadcrumbs-sub-heading' => __('lang.project'),
            ];
        }

        //project_category
        if ($section == 'projectcategory') {
            $page += [
                'breadcrumbs-heading' => __('lang.estimates'),
                'breadcrumbs-sub-heading' => __('lang.project_category'),
            ];
        }

        //estimate_category
        if ($section == 'category') {
            $page += [
                'breadcrumbs-heading' => __('lang.estimates'),
                'breadcrumbs-sub-heading' => __('lang.estimate_category'),
            ];
        }

        //return
        return $page;
    }
}