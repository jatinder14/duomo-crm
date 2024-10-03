<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Responses\Reports\Income\IncomeResponse;
use App\Repositories\Reports\IncomeStatementRepository;

class IncomeStatement extends Controller {

    /**
     * The reportrepo repository instance.
     */
    protected $reportrepo;

    public function __construct(IncomeStatementRepository $reportrepo) {

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
     * grouped by months
     * @return \Illuminate\Http\Response
     */
    public function report() {

        //default search values
        if (!request()->isMethod('post')) {
            request()->merge([
                'filter_year' => now()->year,
            ]);
        }

        //search
        $report = $this->reportrepo->getMonths();

        //reponse payload
        $payload = [
            'report' => $report,
            'page' => $this->pageSettings('monthly'),
        ];

        //process reponse
        return new IncomeResponse($payload);

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
                'breadcrumbs-heading' => __('lang.invoices'),
                'breadcrumbs-sub-heading' => __('lang.overview'),
            ];
        }

        //monthly
        if ($section == 'monthly') {
            $page += [
                'breadcrumbs-heading' => __('lang.invoices'),
                'breadcrumbs-sub-heading' => __('lang.monthly'),
            ];
        }

        //client
        if ($section == 'client') {
            $page += [
                'breadcrumbs-heading' => __('lang.invoices'),
                'breadcrumbs-sub-heading' => __('lang.client'),
            ];
        }

        //project
        if ($section == 'project') {
            $page += [
                'breadcrumbs-heading' => __('lang.invoices'),
                'breadcrumbs-sub-heading' => __('lang.project'),
            ];
        }

        //project_category
        if ($section == 'projectcategory') {
            $page += [
                'breadcrumbs-heading' => __('lang.invoices'),
                'breadcrumbs-sub-heading' => __('lang.project_category'),
            ];
        }

        //invoice_category
        if ($section == 'category') {
            $page += [
                'breadcrumbs-heading' => __('lang.invoices'),
                'breadcrumbs-sub-heading' => __('lang.invoice_category'),
            ];
        }

        //return
        return $page;
    }
}