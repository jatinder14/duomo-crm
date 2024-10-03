<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories\Reports;

use App\Models\Category;
use App\Models\Estimate;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EstimateReportRepository {

    /**
     * The repository instance.
     */
    protected $estimate;
    protected $category;

    /**
     * Inject dependecies
     */
    public function __construct(Estimate $estimate, Category $category) {
        $this->estimate = $estimate;
        $this->category = $category;

    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object estimates collection
     */
    public function getOverview($id = '', $data = []) {

        $estimates = $this->estimate->newQuery();

        $estimates->leftJoin('clients', 'clients.client_id', '=', 'estimates.bill_clientid');
        $estimates->leftJoin('projects', 'projects.project_id', '=', 'estimates.bill_projectid');
        $estimates->leftJoin('categories', 'categories.category_id', '=', 'estimates.bill_categoryid');

        // all client fields
        $estimates->selectRaw('*');

        //default where
        $estimates->whereRaw("1 = 1");

        //timestamp dates (for sorting)
        $estimates->selectRaw('(SELECT UNIX_TIMESTAMP(bill_date))
                AS timestamp_bill_date');

        //skip draft estimates
        $estimates->whereNotIn('bill_status', ['draft']);

        //[date] - range
        if (request('filter_report_date_range') == 'custom_range') {
            //start date
            if (request()->filled('filter_report_date_start')) {
                $estimates->whereDate('bill_date', '>=', request('filter_report_date_start'));
            }
            //end date
            if (request()->filled('filter_report_date_end')) {
                $estimates->whereDate('bill_date', '<=', request('filter_report_date_end'));
            }
        }

        //[date] - this month
        if (request('filter_report_date_range') == 'this_month') {
            //start date
            $estimates->whereDate('bill_date', '>=', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'));
            //end date
            $estimates->whereDate('bill_date', '<=', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'));
        }

        //[date] - last month
        if (request('filter_report_date_range') == 'last_month') {
            //start date
            $estimates->whereDate('bill_date', '>=', \Carbon\Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'));
            //end date
            $estimates->whereDate('bill_date', '<=', \Carbon\Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d'));
        }

        //[date] - this year
        if (request('filter_report_date_range') == 'this_year') {
            //start date
            $estimates->whereDate('bill_date', '>=', \Carbon\Carbon::now()->startOfYear()->format('Y-m-d'));
            //end date
            $estimates->whereDate('bill_date', '<=', \Carbon\Carbon::now()->endOfYear()->format('Y-m-d'));
        }

        //[date] - last year
        if (request('filter_report_date_range') == 'last_year') {
            //start date
            $estimates->whereDate('bill_date', '>=', \Carbon\Carbon::now()->subYear()->startOfYear()->format('Y-m-d'));
            //end date
            $estimates->whereDate('bill_date', '<=', \Carbon\Carbon::now()->subYear()->endOfYear()->format('Y-m-d'));
        }

        //estimate status
        if (is_array(request('filter_bill_status')) && !empty(array_filter(request('filter_bill_status')))) {
            $estimates->whereIn('bill_status', request('filter_bill_status'));
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('estimates', request('orderby'))) {
                $estimates->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'category':
                $estimates->orderBy('category_name', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $estimates->orderBy('bill_estimateid', 'asc');
        }

        //page limit
        if (request()->filled('page_limit') && is_numeric(request('page_limit'))) {
            $rows = $estimates->paginate(request('page_limit'));
        } else {
            $rows = $estimates->paginate(1000000000000);
        }

        //we are returning sums
        if (isset($data['totals'])) {
            switch ($data['totals']) {
            case 'sum_before_tax':
                return $rows->sum('bill_amount_before_tax');
            case 'sum_tax':
                return $rows->sum('bill_tax_total_amount');
            case 'sum_discount':
                return $rows->sum('bill_discount_amount');
            case 'sum_adjustment':
                return $rows->sum('bill_adjustment_amount');
            case 'sum_final_amount':
                return $rows->sum('bill_final_amount');
            case 'sum_balance_due':
                return $rows->sum('estimate_balance');
            default:
                return 0;
            }
        }

        //[pagination-links] - set the base link
        $rows->withPath('/report/estimates/overview');

        //add some params
        $rows->appends([
            'action' => 'load',
            'type' => 'pagination',
        ]);

        //return
        return $rows;

    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object estimates collection
     */
    public function getClient($id = '', $data = []) {

        $estimates = $this->estimate->newQuery();

        $estimates->leftJoin('clients', 'clients.client_id', '=', 'estimates.bill_clientid');
        $estimates->leftJoin('projects', 'projects.project_id', '=', 'estimates.bill_projectid');
        $estimates->leftJoin('categories', 'categories.category_id', '=', 'estimates.bill_categoryid');

        // all client fields
        $estimates->selectRaw('*');

        //default where
        $estimates->whereRaw("1 = 1");

        //sum the values
        $estimates->selectRaw('COALESCE(SUM(estimates.bill_amount_before_tax), 0) as sum_bill_amount_before_tax');
        $estimates->selectRaw('COALESCE(SUM(estimates.bill_tax_total_amount), 0) as sum_bill_tax_total_amount');
        $estimates->selectRaw('COALESCE(SUM(estimates.bill_discount_amount), 0) as sum_bill_discount_amount');
        $estimates->selectRaw('COALESCE(SUM(estimates.bill_adjustment_amount), 0) as sum_bill_adjustment_amount');
        $estimates->selectRaw('COALESCE(SUM(estimates.bill_final_amount), 0) as sum_bill_final_amount');
        $estimates->selectRaw('COUNT(estimates.bill_estimateid) AS estimate_count');

        //skip draft estimates
        $estimates->whereNotIn('bill_status', ['draft']);

        //[date] - range
        if (request('filter_report_date_range') == 'custom_range') {
            //start date
            if (request()->filled('filter_report_date_start')) {
                $estimates->whereDate('bill_date', '>=', request('filter_report_date_start'));
            }
            //end date
            if (request()->filled('filter_report_date_end')) {
                $estimates->whereDate('bill_date', '<=', request('filter_report_date_end'));
            }
        }

        //[date] - this month
        if (request('filter_report_date_range') == 'this_month') {
            //start date
            $estimates->whereDate('bill_date', '>=', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'));
            //end date
            $estimates->whereDate('bill_date', '<=', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'));
        }

        //[date] - last month
        if (request('filter_report_date_range') == 'last_month') {
            //start date
            $estimates->whereDate('bill_date', '>=', \Carbon\Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'));
            //end date
            $estimates->whereDate('bill_date', '<=', \Carbon\Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d'));
        }

        //[date] - this year
        if (request('filter_report_date_range') == 'this_year') {
            //start date
            $estimates->whereDate('bill_date', '>=', \Carbon\Carbon::now()->startOfYear()->format('Y-m-d'));
            //end date
            $estimates->whereDate('bill_date', '<=', \Carbon\Carbon::now()->endOfYear()->format('Y-m-d'));
        }

        //[date] - last year
        if (request('filter_report_date_range') == 'last_year') {
            //start date
            $estimates->whereDate('bill_date', '>=', \Carbon\Carbon::now()->subYear()->startOfYear()->format('Y-m-d'));
            //end date
            $estimates->whereDate('bill_date', '<=', \Carbon\Carbon::now()->subYear()->endOfYear()->format('Y-m-d'));
        }

        //estimate status
        if (is_array(request('filter_bill_status')) && !empty(array_filter(request('filter_bill_status')))) {
            $estimates->whereIn('bill_status', request('filter_bill_status'));
        }

        //group
        $estimates->groupBy('estimates.bill_clientid');

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('estimates', request('orderby'))) {
                $estimates->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'category':
                $estimates->orderBy('category_name', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $estimates->orderBy('clients.client_company_name', 'asc');
        }

        //page limit
        if (request()->filled('page_limit') && is_numeric(request('page_limit'))) {
            $rows = $estimates->paginate(request('page_limit'));
        } else {
            $rows = $estimates->paginate(1000000000000);
        }

        //we are returning sums
        if (isset($data['sum'])) {
            switch ($data['sum']) {
            case 'sum_estimate_count':
                return $rows->sum('estimate_count');
            case 'sum_before_tax':
                return $rows->sum('sum_bill_amount_before_tax');
            case 'sum_tax':
                return $rows->sum('sum_bill_tax_total_amount');
            case 'sum_discount':
                return $rows->sum('sum_bill_discount_amount');
            case 'sum_adjustment':
                return $rows->sum('sum_bill_adjustment_amount');
            case 'sum_final_amount':
                return $rows->sum('sum_bill_final_amount');
            default:
                return 0;
            }
        }


        //[pagination-links] - set the base link
        $rows->withPath('/report/estimates/client');

        //add some params
        $rows->appends([
            'action' => 'load',
            'type' => 'pagination',
        ]);

        return $rows;

    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object estimates collection
     */
    public function getMonths($id = '', $data = []) {

        //create the months
        $estimates = DB::table(DB::raw('(SELECT 1 AS month
                                             UNION SELECT 2
                                             UNION SELECT 3
                                             UNION SELECT 4
                                             UNION SELECT 5
                                             UNION SELECT 6
                                             UNION SELECT 7
                                             UNION SELECT 8
                                             UNION SELECT 9
                                             UNION SELECT 10
                                             UNION SELECT 11
                                             UNION SELECT 12) months'));

        //join months to estimates
        $estimates->leftJoin('estimates', function ($join) {
            //join
            $join->on(DB::raw('MONTH(estimates.bill_date)'), '=', 'months.month');

            //APPLY ALL FILTERS HERE

            //filter year
            if (request()->filled('filter_year') && request('filter_year') != 'all') {
                $join->whereYear('estimates.bill_date', '=', request('filter_year'));
            }

            //exclude drat
            $join->whereNotIn('bill_status', ['draft']);

            //filter status
            if (request()->filled('filter_bill_status')) {
                $join->whereIn('bill_status', (request('filter_bill_status')));
            }
        });

        // all fields
        $estimates->selectRaw('*');

        //default where
        $estimates->whereRaw("1 = 1");

        //frienly month names
        $estimates->selectRaw('CASE months.month
                                   WHEN 1 THEN "january"
                                   WHEN 2 THEN "february"
                                   WHEN 3 THEN "march"
                                   WHEN 4 THEN "april"
                                   WHEN 5 THEN "may"
                                   WHEN 6 THEN "june"
                                   WHEN 7 THEN "july"
                                   WHEN 8 THEN "august"
                                   WHEN 9 THEN "september"
                                   WHEN 10 THEN "october"
                                   WHEN 11 THEN "november"
                                   ELSE "december"
                                   END as estimate_month');
        //sum the values
        $estimates->selectRaw('COALESCE(SUM(estimates.bill_amount_before_tax), 0) as sum_bill_amount_before_tax');
        $estimates->selectRaw('COALESCE(SUM(estimates.bill_tax_total_amount), 0) as sum_bill_tax_total_amount');
        $estimates->selectRaw('COALESCE(SUM(estimates.bill_discount_amount), 0) as sum_bill_discount_amount');
        $estimates->selectRaw('COALESCE(SUM(estimates.bill_adjustment_amount), 0) as sum_bill_adjustment_amount');
        $estimates->selectRaw('COALESCE(SUM(estimates.bill_final_amount), 0) as sum_bill_final_amount');
        $estimates->selectRaw('COUNT(estimates.bill_estimateid) AS estimate_count');

        $estimates->groupBy('months.month');

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('estimates', request('orderby'))) {
                $estimates->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'count':
                $estimates->orderBy('estimate_count', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $estimates->orderBy('months.month', 'asc');
        }

        //get all for the year
        $rows = $estimates->get();

        //we are returning sums
        if (isset($data['totals'])) {
            switch ($data['totals']) {
            case 'count_estimates':
                return $rows->sum('estimate_count');
            case 'sum_before_tax':
                return $rows->sum('sum_bill_amount_before_tax');
            case 'sum_tax':
                return $rows->sum('sum_bill_tax_total_amount');
            case 'sum_discount':
                return $rows->sum('sum_bill_discount_amount');
            case 'sum_adjustment':
                return $rows->sum('sum_bill_adjustment_amount');
            case 'sum_final_amount':
                return $rows->sum('sum_bill_final_amount');
            default:
                return 0;
            }
        }

        return $rows;
    }

    /**
     * get a range of years to use in the dropdown filter. Will be based on the oldest estimate and a 3 year buffer
     * @return array year
     */
    public function getYearsRange() {

        // check if there are any estimates in the database
        if (Estimate::count() > 0) {

            // get the oldest estimate date from the estimates table
            $oldest_estimate_date = Estimate::oldest('bill_date')->value('bill_date');

            // Determine the current year
            $current_year = now()->year;

            // add 2 years from the oldest estimate year to create a buffer
            $oldest_year = date('Y', strtotime($oldest_estimate_date));
            $buffered_year = $oldest_year - 3;

            // get the range of years
            $years = range($buffered_year, $current_year);

            // Reverse the array
            $years = array_reverse($years);

        } else {

            // if there are no estimates, set default values
            $current_year = now()->year;
            $years = range($current_year, $current_year);
        }

        return $years;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object estimates collection
     */
    public function getCategory($id = '', $data = []) {

        //get all categories
        $estimates = DB::table('categories');

        //join estimates and add all teh conditions on estimates in this join
        $estimates->leftJoin('estimates', function ($join) {

            $join->on('categories.category_id', '=', 'estimates.bill_categoryid');

            //skip draft estimates
            $join->whereNotIn('estimates.bill_status', ['draft']);

            //[date] - range
            if (request('filter_report_date_range') == 'custom_range') {
                //start date
                if (request()->filled('filter_report_date_start')) {
                    $join->whereDate('bill_date', '>=', request('filter_report_date_start'));
                }
                //end date
                if (request()->filled('filter_report_date_end')) {
                    $join->whereDate('bill_date', '<=', request('filter_report_date_end'));
                }
            }

            //[date] - this month
            if (request('filter_report_date_range') == 'this_month') {
                //start date
                $join->whereDate('bill_date', '>=', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'));
                //end date
                $join->whereDate('bill_date', '<=', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'));
            }

            //[date] - last month
            if (request('filter_report_date_range') == 'last_month') {
                //start date
                $join->whereDate('bill_date', '>=', \Carbon\Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'));
                //end date
                $join->whereDate('bill_date', '<=', \Carbon\Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d'));
            }

            //[date] - this year
            if (request('filter_report_date_range') == 'this_year') {
                //start date
                $join->whereDate('bill_date', '>=', \Carbon\Carbon::now()->startOfYear()->format('Y-m-d'));
                //end date
                $join->whereDate('bill_date', '<=', \Carbon\Carbon::now()->endOfYear()->format('Y-m-d'));
            }

            //[date] - last year
            if (request('filter_report_date_range') == 'last_year') {
                //start date
                $join->whereDate('bill_date', '>=', \Carbon\Carbon::now()->subYear()->startOfYear()->format('Y-m-d'));
                //end date
                $join->whereDate('bill_date', '<=', \Carbon\Carbon::now()->subYear()->endOfYear()->format('Y-m-d'));
            }

            //estimate status
            if (is_array(request('filter_bill_status')) && !empty(array_filter(request('filter_bill_status')))) {
                $join->whereIn('bill_status', request('filter_bill_status'));
            }

        });

        // get all fields
        $estimates->selectRaw('*');

        //only get the estimates category
        $estimates->where('categories.category_type', 'estimate');

        //sum the values
        $estimates->selectRaw('COALESCE(SUM(estimates.bill_amount_before_tax), 0) as sum_bill_amount_before_tax');
        $estimates->selectRaw('COALESCE(SUM(estimates.bill_tax_total_amount), 0) as sum_bill_tax_total_amount');
        $estimates->selectRaw('COALESCE(SUM(estimates.bill_discount_amount), 0) as sum_bill_discount_amount');
        $estimates->selectRaw('COALESCE(SUM(estimates.bill_adjustment_amount), 0) as sum_bill_adjustment_amount');
        $estimates->selectRaw('COALESCE(SUM(estimates.bill_final_amount), 0) as sum_bill_final_amount');
        $estimates->selectRaw('COUNT(estimates.bill_estimateid) AS estimate_count');

        //group
        $estimates->groupBy('categories.category_id');
        $estimates->groupBy('categories.category_name');

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('estimates', request('orderby'))) {
                $estimates->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'estimate_count':
                $estimates->orderBy('estimate_count', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $estimates->orderBy('categories.category_name', 'asc');
        }

        //page limit
        if (request()->filled('page_limit') && is_numeric(request('page_limit'))) {
            $rows = $estimates->paginate(request('page_limit'));
        } else {
            $rows = $estimates->paginate(1000000000000);
        }

        //we are returning sums
        if (isset($data['sum'])) {
            switch ($data['sum']) {
            case 'sum_estimate_count':
                return $rows->sum('estimate_count');
            case 'sum_before_tax':
                return $rows->sum('sum_bill_amount_before_tax');
            case 'sum_tax':
                return $rows->sum('sum_bill_tax_total_amount');
            case 'sum_discount':
                return $rows->sum('sum_bill_discount_amount');
            case 'sum_adjustment':
                return $rows->sum('sum_bill_adjustment_amount');
            case 'sum_final_amount':
                return $rows->sum('sum_bill_final_amount');
            default:
                return 0;
            }
        }

        //return
        return $rows;

    }

}