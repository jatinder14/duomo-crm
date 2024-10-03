<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories\Reports;

use App\Models\Category;
use App\Models\Invoice;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class InvoiceReportRepository {

    /**
     * The repository instance.
     */
    protected $invoice;
    protected $category;

    /**
     * Inject dependecies
     */
    public function __construct(Invoice $invoice, Category $category) {
        $this->invoice = $invoice;
        $this->category = $category;

    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object invoices collection
     */
    public function getOverview($id = '', $data = []) {

        $invoices = $this->invoice->newQuery();

        $invoices->leftJoin('clients', 'clients.client_id', '=', 'invoices.bill_clientid');
        $invoices->leftJoin('projects', 'projects.project_id', '=', 'invoices.bill_projectid');
        $invoices->leftJoin('categories', 'categories.category_id', '=', 'invoices.bill_categoryid');

        // all client fields
        $invoices->selectRaw('*');

        //default where
        $invoices->whereRaw("1 = 1");

        //sum payments
        $invoices->selectRaw('(SELECT COALESCE(SUM(payment_amount), 0)
                                      FROM payments WHERE payment_invoiceid = invoices.bill_invoiceid
                                      GROUP BY payment_invoiceid)
                                      AS x_sum_payments');
        $invoices->selectRaw('(SELECT COALESCE(x_sum_payments, 0.00))
                                      AS sum_payments');

        //invoice balance
        $invoices->selectRaw('(SELECT COALESCE(bill_final_amount - sum_payments, 0.00))
                                      AS invoice_balance');

        //timestamp dates (for sorting)
        $invoices->selectRaw('(SELECT UNIX_TIMESTAMP(bill_date))
                                      AS timestamp_bill_date');

        //skip draft invoices
        $invoices->whereNotIn('bill_status', ['draft']);

        //[date] - range
        if (request('filter_report_date_range') == 'custom_range') {
            //start date
            if (request()->filled('filter_report_date_start')) {
                $invoices->whereDate('bill_date', '>=', request('filter_report_date_start'));
            }
            //end date
            if (request()->filled('filter_report_date_end')) {
                $invoices->whereDate('bill_date', '<=', request('filter_report_date_end'));
            }
        }

        //[date] - this month
        if (request('filter_report_date_range') == 'this_month') {
            //start date
            $invoices->whereDate('bill_date', '>=', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'));
            //end date
            $invoices->whereDate('bill_date', '<=', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'));
        }

        //[date] - last month
        if (request('filter_report_date_range') == 'last_month') {
            //start date
            $invoices->whereDate('bill_date', '>=', \Carbon\Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'));
            //end date
            $invoices->whereDate('bill_date', '<=', \Carbon\Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d'));
        }

        //[date] - this year
        if (request('filter_report_date_range') == 'this_year') {
            //start date
            $invoices->whereDate('bill_date', '>=', \Carbon\Carbon::now()->startOfYear()->format('Y-m-d'));
            //end date
            $invoices->whereDate('bill_date', '<=', \Carbon\Carbon::now()->endOfYear()->format('Y-m-d'));
        }

        //[date] - last year
        if (request('filter_report_date_range') == 'last_year') {
            //start date
            $invoices->whereDate('bill_date', '>=', \Carbon\Carbon::now()->subYear()->startOfYear()->format('Y-m-d'));
            //end date
            $invoices->whereDate('bill_date', '<=', \Carbon\Carbon::now()->subYear()->endOfYear()->format('Y-m-d'));
        }

        //invoice status
        if (is_array(request('filter_bill_status')) && !empty(array_filter(request('filter_bill_status')))) {
            $invoices->whereIn('bill_status', request('filter_bill_status'));
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('invoices', request('orderby'))) {
                $invoices->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'category':
                $invoices->orderBy('category_name', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $invoices->orderBy('bill_invoiceid', 'asc');
        }

        //page limit
        if (request()->filled('page_limit') && is_numeric(request('page_limit'))) {
            $rows = $invoices->paginate(request('page_limit'));
        } else {
            $rows = $invoices->paginate(1000000000000);
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
            case 'sum_payments':
                return $rows->sum('sum_payments');
            case 'sum_balance_due':
                return $rows->sum('invoice_balance');
            default:
                return 0;
            }
        }

        //[pagination-links] - set the base link
        $rows->withPath('/report/invoices/overview');

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
     * @return object invoices collection
     */
    public function getClient($id = '', $data = []) {

        $invoices = $this->invoice->newQuery();

        $invoices->leftJoin('clients', 'clients.client_id', '=', 'invoices.bill_clientid');
        $invoices->leftJoin('projects', 'projects.project_id', '=', 'invoices.bill_projectid');
        $invoices->leftJoin('categories', 'categories.category_id', '=', 'invoices.bill_categoryid');

        // all client fields
        $invoices->selectRaw('*');

        //default where
        $invoices->whereRaw("1 = 1");

        //sum the values
        $invoices->selectRaw('COALESCE(SUM(invoices.bill_amount_before_tax), 0) as sum_bill_amount_before_tax');
        $invoices->selectRaw('COALESCE(SUM(invoices.bill_tax_total_amount), 0) as sum_bill_tax_total_amount');
        $invoices->selectRaw('COALESCE(SUM(invoices.bill_discount_amount), 0) as sum_bill_discount_amount');
        $invoices->selectRaw('COALESCE(SUM(invoices.bill_adjustment_amount), 0) as sum_bill_adjustment_amount');
        $invoices->selectRaw('COALESCE(SUM(invoices.bill_final_amount), 0) as sum_bill_final_amount');
        $invoices->selectRaw('COUNT(invoices.bill_invoiceid) AS invoice_count');

        //skip draft invoices
        $invoices->whereNotIn('bill_status', ['draft']);

        //[date] - range
        if (request('filter_report_date_range') == 'custom_range') {
            //start date
            if (request()->filled('filter_report_date_start')) {
                $invoices->whereDate('bill_date', '>=', request('filter_report_date_start'));
            }
            //end date
            if (request()->filled('filter_report_date_end')) {
                $invoices->whereDate('bill_date', '<=', request('filter_report_date_end'));
            }
        }

        //[date] - this month
        if (request('filter_report_date_range') == 'this_month') {
            //start date
            $invoices->whereDate('bill_date', '>=', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'));
            //end date
            $invoices->whereDate('bill_date', '<=', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'));
        }

        //[date] - last month
        if (request('filter_report_date_range') == 'last_month') {
            //start date
            $invoices->whereDate('bill_date', '>=', \Carbon\Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'));
            //end date
            $invoices->whereDate('bill_date', '<=', \Carbon\Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d'));
        }

        //[date] - this year
        if (request('filter_report_date_range') == 'this_year') {
            //start date
            $invoices->whereDate('bill_date', '>=', \Carbon\Carbon::now()->startOfYear()->format('Y-m-d'));
            //end date
            $invoices->whereDate('bill_date', '<=', \Carbon\Carbon::now()->endOfYear()->format('Y-m-d'));
        }

        //[date] - last year
        if (request('filter_report_date_range') == 'last_year') {
            //start date
            $invoices->whereDate('bill_date', '>=', \Carbon\Carbon::now()->subYear()->startOfYear()->format('Y-m-d'));
            //end date
            $invoices->whereDate('bill_date', '<=', \Carbon\Carbon::now()->subYear()->endOfYear()->format('Y-m-d'));
        }

        //invoice status
        if (is_array(request('filter_bill_status')) && !empty(array_filter(request('filter_bill_status')))) {
            $invoices->whereIn('bill_status', request('filter_bill_status'));
        }

        //group
        $invoices->groupBy('invoices.bill_clientid');

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('invoices', request('orderby'))) {
                $invoices->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'category':
                $invoices->orderBy('category_name', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $invoices->orderBy('clients.client_company_name', 'asc');
        }

        //page limit
        if (request()->filled('page_limit') && is_numeric(request('page_limit'))) {
            $rows = $invoices->paginate(request('page_limit'));
        } else {
            $rows = $invoices->paginate(1000000000000);
        }

        //we are returning sums
        if (isset($data['sum'])) {
            switch ($data['sum']) {
            case 'sum_invoice_count':
                return $rows->sum('invoice_count');
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
        $rows->withPath('/report/invoices/client');

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
     * @return object invoices collection
     */
    public function getMonths($id = '', $data = []) {

        //create the months
        $invoices = DB::table(DB::raw('(SELECT 1 AS month
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

        //join months to invoices
        $invoices->leftJoin('invoices', function ($join) {
            $join->on(DB::raw('MONTH(invoices.bill_date)'), '=', 'months.month');

            //APPLY ALL FILTERS HERE

            //filter year
            if (request()->filled('filter_year') && request('filter_year') != 'all') {
                $join->whereYear('invoices.bill_date', '=', request('filter_year'));
            }

            //exclude draft
            $join->whereNotIn('bill_status', ['draft']);

            //filter status
            if (request()->filled('filter_bill_status')) {
                $join->whereIn('bill_status', (request('filter_bill_status')));
            }
        });

        // all fields
        $invoices->selectRaw('*');

        //default where
        $invoices->whereRaw("1 = 1");

        //frienly month names
        $invoices->selectRaw('CASE months.month
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
                                   END as invoice_month');
        //sum the values
        $invoices->selectRaw('COALESCE(SUM(invoices.bill_amount_before_tax), 0) as sum_bill_amount_before_tax');
        $invoices->selectRaw('COALESCE(SUM(invoices.bill_tax_total_amount), 0) as sum_bill_tax_total_amount');
        $invoices->selectRaw('COALESCE(SUM(invoices.bill_discount_amount), 0) as sum_bill_discount_amount');
        $invoices->selectRaw('COALESCE(SUM(invoices.bill_adjustment_amount), 0) as sum_bill_adjustment_amount');
        $invoices->selectRaw('COALESCE(SUM(invoices.bill_final_amount), 0) as sum_bill_final_amount');
        $invoices->selectRaw('COUNT(invoices.bill_invoiceid) AS invoice_count');

        $invoices->groupBy('months.month');

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('invoices', request('orderby'))) {
                $invoices->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'count':
                $invoices->orderBy('invoice_count', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $invoices->orderBy('months.month', 'asc');
        }

        //get all for the year
        $rows = $invoices->get();

        //we are returning sums
        if (isset($data['totals'])) {
            switch ($data['totals']) {
            case 'count_invoices':
                return $rows->sum('invoice_count');
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
     * get a range of years to use in the dropdown filter. Will be based on the oldest invoice and a 3 year buffer
     * @return array year
     */
    public function getYearsRange() {

        // check if there are any invoices in the database
        if (Invoice::count() > 0) {

            // get the oldest invoice date from the invoices table
            $oldest_invoice_date = Invoice::oldest('bill_date')->value('bill_date');

            // Determine the current year
            $current_year = now()->year;

            // add 2 years from the oldest invoice year to create a buffer
            $oldest_year = date('Y', strtotime($oldest_invoice_date));
            $buffered_year = $oldest_year - 3;

            // get the range of years
            $years = range($buffered_year, $current_year);

            // Reverse the array
            $years = array_reverse($years);

        } else {

            // if there are no invoices, set default values
            $current_year = now()->year;
            $years = range($current_year, $current_year);
        }

        return $years;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object invoices collection
     */
    public function getCategory($id = '', $data = []) {

        //get all categories
        $invoices = DB::table('categories');

        //join invoices and add all teh conditions on invoices in this join
        $invoices->leftJoin('invoices', function ($join) {

            $join->on('categories.category_id', '=', 'invoices.bill_categoryid');

            //skip draft invoices
            $join->whereNotIn('invoices.bill_status', ['draft']);

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

            //invoice status
            if (is_array(request('filter_bill_status')) && !empty(array_filter(request('filter_bill_status')))) {
                $join->whereIn('bill_status', request('filter_bill_status'));
            }

        });

        // get all fields
        $invoices->selectRaw('*');

        //only get the invoices category
        $invoices->where('categories.category_type', 'invoice');

        //sum the values
        $invoices->selectRaw('COALESCE(SUM(invoices.bill_amount_before_tax), 0) as sum_bill_amount_before_tax');
        $invoices->selectRaw('COALESCE(SUM(invoices.bill_tax_total_amount), 0) as sum_bill_tax_total_amount');
        $invoices->selectRaw('COALESCE(SUM(invoices.bill_discount_amount), 0) as sum_bill_discount_amount');
        $invoices->selectRaw('COALESCE(SUM(invoices.bill_adjustment_amount), 0) as sum_bill_adjustment_amount');
        $invoices->selectRaw('COALESCE(SUM(invoices.bill_final_amount), 0) as sum_bill_final_amount');
        $invoices->selectRaw('COUNT(invoices.bill_invoiceid) AS invoice_count');

        //group
        $invoices->groupBy('categories.category_id');
        $invoices->groupBy('categories.category_name');

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('invoices', request('orderby'))) {
                $invoices->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'invoice_count':
                $invoices->orderBy('invoice_count', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $invoices->orderBy('categories.category_name', 'asc');
        }

        //page limit
        if (request()->filled('page_limit') && is_numeric(request('page_limit'))) {
            $rows = $invoices->paginate(request('page_limit'));
        } else {
            $rows = $invoices->paginate(1000000000000);
        }

        //we are returning sums
        if (isset($data['sum'])) {
            switch ($data['sum']) {
            case 'sum_invoice_count':
                return $rows->sum('invoice_count');
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