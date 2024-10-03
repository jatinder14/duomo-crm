<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories\Reports;

use App\Models\Category;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;

class ClientReportRepository {

    /**
     * The repository instance.
     */
    protected $project;
    protected $category;
    protected $client;

    /**
     * Inject dependecies
     */
    public function __construct(Project $project, Category $category, Client $client) {
        $this->project = $project;
        $this->category = $category;
        $this->client = $client;

    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object projects collection
     */
    public function getOverview($id = '', $data = []) {

        //filter - project dates
        $start_date = $this->filterDates('start');
        $end_date = $this->filterDates('end');

        $clients = $this->client->newQuery();

        $clients->selectRaw('*');

        //default where
        $clients->whereRaw("1 = 1");

        $clients->where('client_id', '>', 0);

        //count_projects
        $clients->selectRaw("COALESCE((SELECT COUNT(project_id)
                               FROM projects
                               WHERE project_clientid = clients.client_id
                               AND project_date_start >= '$start_date'
                               AND project_date_start <= '$end_date'
                               AND project_type = 'project'), 0) AS count_projects");

        //count_projects_pending
        $clients->selectRaw("COALESCE((SELECT COUNT(project_id)
                               FROM projects
                               WHERE project_clientid = clients.client_id
                               AND project_date_start >= '$start_date'
                               AND project_date_start <= '$end_date'
                               AND project_type = 'project'
                               AND project_status NOT IN('completed')), 0) AS count_projects_pending");

        //count_projects_completed
        $clients->selectRaw("COALESCE((SELECT COUNT(project_id)
                               FROM projects
                               WHERE project_clientid = clients.client_id
                               AND project_date_start >= '$start_date'
                               AND project_date_start <= '$end_date'
                               AND project_type = 'project'
                               AND project_status = 'completed'), 0) AS count_projects_completed");

        //sum_invoices_due
        $clients->selectRaw("(SELECT COALESCE(SUM(bill_final_amount), 0)
                                      FROM invoices
                                      WHERE bill_clientid = clients.client_id
                                      AND bill_date >= '$start_date'
                                      AND bill_date <= '$end_date'
                                      AND bill_status = 'due')
                                      AS x_sum_invoices_due");
        $clients->selectRaw('(SELECT COALESCE(x_sum_invoices_due, 0.00))
                                      AS sum_invoices_due');

        //sum_invoices_paid
        $clients->selectRaw("(SELECT COALESCE(SUM(bill_final_amount), 0)
                                      FROM invoices
                                      WHERE bill_clientid = clients.client_id
                                      AND bill_date >= '$start_date'
                                      AND bill_date <= '$end_date'
                                      AND bill_status = 'paid')
                                      AS x_sum_invoices_paid");
        $clients->selectRaw('(SELECT COALESCE(x_sum_invoices_paid, 0.00))
                                      AS sum_invoices_paid');

        //sum_invoices_overdue
        $clients->selectRaw("(SELECT COALESCE(SUM(bill_final_amount), 0)
                                      FROM invoices
                                      WHERE bill_clientid = clients.client_id
                                      AND bill_date >= '$start_date'
                                      AND bill_date <= '$end_date'
                                      AND bill_status = 'overdue')
                                      AS x_sum_invoices_overdue");
        $clients->selectRaw('(SELECT COALESCE(x_sum_invoices_overdue, 0.00))
                                      AS sum_invoices_overdue');

        //sum_estimates_accepted
        $clients->selectRaw("(SELECT COALESCE(SUM(bill_final_amount), 0)
                                      FROM estimates
                                      WHERE bill_clientid = clients.client_id
                                      AND bill_date >= '$start_date'
                                      AND bill_date <= '$end_date'
                                      AND bill_status IN ('accepted'))
                                      AS x_sum_estimates_accepted");
        $clients->selectRaw('(SELECT COALESCE(x_sum_estimates_accepted, 0.00))
                                      AS sum_estimates_accepted');

        //sum_estimates_declined
        $clients->selectRaw("(SELECT COALESCE(SUM(bill_final_amount), 0)
                                      FROM estimates
                                      WHERE bill_clientid = clients.client_id
                                      AND bill_date >= '$start_date'
                                      AND bill_date <= '$end_date'
                                      AND bill_status IN ('declined'))
                                      AS x_sum_estimates_declined");
        $clients->selectRaw('(SELECT COALESCE(x_sum_estimates_declined, 0.00))
                                      AS sum_estimates_declined');

        //sum_expenses
        $clients->selectRaw("(SELECT COALESCE(SUM(expense_amount), 0)
                                      FROM expenses
                                      WHERE expense_clientid = clients.client_id
                                      AND expense_date >= '$start_date'
                                      AND expense_date <= '$end_date')
                                      AS x_sum_expenses");
        $clients->selectRaw('(SELECT COALESCE(x_sum_expenses, 0.00))
                                      AS sum_expenses');

        //sum_expenses_invoiced
        $clients->selectRaw("(SELECT COALESCE(SUM(expense_amount), 0)
                                      FROM expenses
                                      WHERE expense_clientid = clients.client_id
                                      AND expense_date >= '$start_date'
                                      AND expense_date <= '$end_date'
                                      AND expense_billable = 'billable'
                                      AND expense_billing_status = 'invoiced')
                                      AS x_sum_expenses_invoiced");
        $clients->selectRaw('(SELECT COALESCE(x_sum_expenses_invoiced, 0.00))
                                      AS sum_expenses_invoiced');

        //sum_expenses_not_invoiced
        $clients->selectRaw("(SELECT COALESCE(SUM(expense_amount), 0)
                                      FROM expenses
                                      WHERE expense_clientid = clients.client_id
                                      AND expense_date >= '$start_date'
                                      AND expense_date <= '$end_date'
                                      AND expense_billable = 'billable'
                                      AND expense_billing_status = 'not_invoiced')
                                      AS x_sum_expenses_not_invoiced");
        $clients->selectRaw('(SELECT COALESCE(x_sum_expenses_not_invoiced, 0.00))
                                      AS sum_expenses_not_invoiced');

        //sum_expenses_not_billable
        $clients->selectRaw("(SELECT COALESCE(SUM(expense_amount), 0)
                                      FROM expenses
                                      WHERE expense_clientid = clients.client_id
                                      AND expense_date >= '$start_date'
                                      AND expense_date <= '$end_date'
                                      AND expense_billable = 'not_billable'
                                      AND expense_billing_status = 'not_invoiced')
                                      AS x_sum_expenses_not_billable");
        $clients->selectRaw('(SELECT COALESCE(x_sum_expenses_not_billable, 0.00))
                                      AS sum_expenses_not_billable');
        //page limit
        if (request()->filled('page_limit') && is_numeric(request('page_limit'))) {
            $rows = $clients->paginate(request('page_limit'));
        } else {
            $rows = $clients->paginate(1000000000000);
        }

        //we are returning sums
        if (isset($data['totals'])) {
            switch ($data['totals']) {
            case 'count_projects':
                return $rows->sum('count_projects');
            case 'count_projects_pending':
                return $rows->sum('count_projects_pending');
            case 'count_projects_completed':
                return $rows->sum('count_projects_completed');
            case 'sum_invoices_due':
                return $rows->sum('sum_invoices_due');
            case 'sum_invoices_overdue':
                return $rows->sum('sum_invoices_overdue');
            case 'sum_invoices_paid':
                return $rows->sum('sum_invoices_paid');
            case 'sum_estimates_accepted':
                return $rows->sum('sum_estimates_accepted');
            case 'sum_estimates_declined':
                return $rows->sum('sum_estimates_declined');
            case 'sum_expenses':
                return $rows->sum('foo');
            case 'sum_expenses_invoiced':
                return $rows->sum('sum_expenses_invoiced');
            case 'sum_expenses_not_invoiced':
                return $rows->sum('sum_expenses_not_invoiced');
            case 'sum_expenses_not_billable':
                return $rows->sum('sum_expenses_not_billable');
            default:
                return 0;
            }
        }

        //[pagination-links] - set the base link
        $rows->withPath('/report/clients/overview');

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
     * @return object projects collection
     */
    public function getCategory($type = '') {

        $projects = [];

        $totals = [
            'count_projects_pending' => 0,
            'count_projects_completed' => 0,
            'count_tasks_due' => 0,
            'count_tasks_completed' => 0,
            'sum_hours' => 0,
            'sum_expenses' => 0,
            'sum_invoices' => 0,
            'sum_payments' => 0,
        ];

        //get all categories
        $categories = \App\Models\Category::Where('category_type', 'project')->orderBy('category_name', 'asc')->get();

        foreach ($categories as $category) {

            //start projects query
            $project_ids = \App\Models\Project::where('project_categoryid', $category->category_id)
                ->where('project_type', 'project')
                ->whereNotIn('project_status', ['draft']);

            //[date] - range
            if (request('filter_report_date_range') == 'custom_range') {
                //start date
                if (request()->filled('filter_report_date_start')) {
                    $project_ids->whereDate('project_date_start', '>=', request('filter_report_date_start'));
                }
                //end date
                if (request()->filled('filter_report_date_end')) {
                    $project_ids->whereDate('project_date_start', '<=', request('filter_report_date_end'));
                }
            }

            //[date] - this month
            if (request('filter_report_date_range') == 'this_month') {
                //start date
                $project_ids->whereDate('project_date_start', '>=', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'));
                //end date
                $project_ids->whereDate('project_date_start', '<=', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'));
            }

            //[date] - last month
            if (request('filter_report_date_range') == 'last_month') {
                //start date
                $project_ids->whereDate('project_date_start', '>=', \Carbon\Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'));
                //end date
                $project_ids->whereDate('project_date_start', '<=', \Carbon\Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d'));
            }

            //[date] - this year
            if (request('filter_report_date_range') == 'this_year') {
                //start date
                $project_ids->whereDate('project_date_start', '>=', \Carbon\Carbon::now()->startOfYear()->format('Y-m-d'));
                //end date
                $project_ids->whereDate('project_date_start', '<=', \Carbon\Carbon::now()->endOfYear()->format('Y-m-d'));
            }

            //[date] - last year
            if (request('filter_report_date_range') == 'last_year') {
                //start date
                $project_ids->whereDate('project_date_start', '>=', \Carbon\Carbon::now()->subYear()->startOfYear()->format('Y-m-d'));
                //end date
                $project_ids->whereDate('project_date_start', '<=', \Carbon\Carbon::now()->subYear()->endOfYear()->format('Y-m-d'));
            }

            //get the projects ids
            $project_ids = $project_ids->pluck('project_id')->toArray();

            //count projects
            $count_projects = count($project_ids);

            //count_projects_completed
            $count_projects_completed = \App\Models\Project::where('project_creatorid', $category->category_id)
                ->where('project_type', 'project')
                ->where('project_status', 'completed')
                ->whereIn('project_id', $project_ids)
                ->count();

            //get project ids
            $count_projects_pending = \App\Models\Project::where('project_creatorid', $category->category_id)
                ->where('project_type', 'project')
                ->whereNotIn('project_status', ['draft', 'completed'])
                ->whereIn('project_id', $project_ids)
                ->count();

            //count_tasks_due
            $count_tasks_due = \App\Models\Task::whereIn('task_projectid', $project_ids)->whereNotIn('task_status', [2])->count();

            //count_tasks_completed
            $count_tasks_completed = \App\Models\Task::whereIn('task_projectid', $project_ids)->where('task_status', 2)->count();

            //sum_hours
            $sum_hours = \App\Models\Timer::whereIn('timer_projectid', $project_ids)->where('timer_status', 'stopped')->sum('timer_time');

            //sum_expenses
            $sum_expenses = \App\Models\Expense::whereIn('expense_projectid', $project_ids)->sum('expense_amount');

            //sum_invoices
            $sum_invoices = \App\Models\Invoice::whereIn('bill_projectid', $project_ids)->whereNotIn('bill_status', ['draft'])->sum('bill_final_amount');

            //sum_payments
            $sum_payments = \App\Models\Payment::whereIn('payment_projectid', $project_ids)->sum('payment_amount');

            //merge into an array
            $projects[] = [
                'category_name' => $category->category_name,
                'count_projects_pending' => $count_projects_pending,
                'count_projects_completed' => $count_projects_completed,
                'count_tasks_due' => $count_tasks_due,
                'count_tasks_completed' => $count_tasks_completed,
                'sum_hours' => $sum_hours,
                'sum_expenses' => $sum_expenses,
                'sum_invoices' => $sum_invoices,
                'sum_payments' => $sum_payments,
            ];

            //runing totals
            $totals['count_projects_pending'] += $count_projects_pending;
            $totals['count_projects_completed'] += $count_projects_completed;
            $totals['count_tasks_due'] += $count_tasks_due;
            $totals['count_tasks_completed'] += $count_tasks_completed;
            $totals['sum_hours'] += $sum_hours;
            $totals['sum_expenses'] += $sum_expenses;
            $totals['sum_invoices'] += $sum_invoices;
            $totals['sum_payments'] += $sum_payments;
        }

        //returns
        if ($type == 'projects') {
            return $projects;
        }

        //totals
        if ($type == 'totals') {
            return $totals;
        }

    }

    function filterDates($type = '') {

        //exteme defaults
        $start_date = '0000-01-01';
        $end_date = '9999-01-01';

        //[date] - range
        if (request('filter_report_date_range') == 'custom_range') {
            //start date
            if (request()->filled('filter_report_date_start')) {
                $start_date = request('filter_report_date_start');
            }
            //end date
            if (request()->filled('filter_report_date_end')) {
                $end_date = request('filter_report_date_end');
            }
        }

        //[date] - this month
        if (request('filter_report_date_range') == 'this_month') {
            //start date
            $start_date = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
            //end date
            $end_date = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        //[date] - last month
        if (request('filter_report_date_range') == 'last_month') {
            //start date
            $start_date = \Carbon\Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
            //end date
            $end_date = \Carbon\Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        }

        //[date] - this year
        if (request('filter_report_date_range') == 'this_year') {
            //start date
            $start_date = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
            //end date
            $end_date = \Carbon\Carbon::now()->endOfYear()->format('Y-m-d');
        }

        //[date] - last year
        if (request('filter_report_date_range') == 'last_year') {
            //start date
            $start_date = \Carbon\Carbon::now()->subYear()->startOfYear()->format('Y-m-d');
            //end date
            $end_date = \Carbon\Carbon::now()->subYear()->endOfYear()->format('Y-m-d');
        }

        return ($type == 'start') ? $start_date : $end_date;
    }
}