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
use Illuminate\Support\Facades\Schema;

class ProjectReportRepository {

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

        $projects = $this->project->newQuery();

        $projects->leftJoin('clients', 'clients.client_id', '=', 'projects.project_clientid');
        $projects->leftJoin('categories', 'categories.category_id', '=', 'projects.project_categoryid');

        // all client fields
        $projects->selectRaw('*');

        //default where
        $projects->whereRaw("1 = 1");

        //sum_payments
        $projects->selectRaw('(SELECT COALESCE(SUM(payment_amount), 0)
                                      FROM payments
                                      WHERE payment_projectid = projects.project_id
                                      GROUP BY payment_projectid)
                                      AS x_sum_payments');
        $projects->selectRaw('(SELECT COALESCE(x_sum_payments, 0.00))
                                      AS sum_payments');

        //sum_invoices
        $projects->selectRaw("(SELECT COALESCE(SUM(bill_final_amount), 0)
                                      FROM invoices
                                      WHERE bill_projectid = projects.project_id
                                      AND bill_status NOT IN ('draft')
                                      GROUP BY bill_projectid)
                                      AS x_sum_invoices");
        $projects->selectRaw('(SELECT COALESCE(x_sum_invoices, 0.00))
                                      AS sum_invoices');

        //sum_expenses
        $projects->selectRaw("(SELECT COALESCE(SUM(expense_amount), 0)
                                      FROM expenses
                                       WHERE expense_projectid = projects.project_id
                                      GROUP BY expense_projectid)
                                      AS x_sum_expenses");
        $projects->selectRaw('(SELECT COALESCE(x_sum_expenses, 0.00))
                                      AS sum_expenses');

        //sum_hours
        $projects->selectRaw("COALESCE((SELECT SUM(timer_time)
                                      FROM timers WHERE timer_projectid = projects.project_id
                                      AND timer_status = 'stopped'
                                      GROUP BY timer_projectid), 0)
                                      AS sum_hours");

        //count_tasks_due
        $projects->selectRaw("COALESCE((SELECT COUNT(task_id)
                               FROM tasks
                               WHERE task_projectid = projects.project_id
                               AND task_status NOT IN (2)
                               GROUP BY task_projectid), 0) AS count_tasks_due");
        //count_tasks_completed
        $projects->selectRaw("COALESCE((SELECT COUNT(task_id)
                               FROM tasks
                               WHERE task_projectid = projects.project_id
                               AND task_status = 2
                               GROUP BY task_projectid), 0) AS count_tasks_completed");

        //timestamp dates (for sorting)
        $projects->selectRaw('(SELECT UNIX_TIMESTAMP(project_date_due))
                                 AS timestamp_project_date_due');

        //only projects
        $projects->where("project_type", "project");

        //not draft
        $projects->whereNotIN("project_status", ['draft']);

        //limit to specific category
        if (is_numeric($id)) {
            $projects->where("category_creatorid", $id);
        }

        //[date] - range
        if (request('filter_report_date_range') == 'custom_range') {
            //start date
            if (request()->filled('filter_report_date_start')) {
                $projects->whereDate('project_created', '>=', request('filter_report_date_start'));
            }
            //end date
            if (request()->filled('filter_report_date_end')) {
                $projects->whereDate('project_created', '<=', request('filter_report_date_end'));
            }
        }

        //[date] - this month
        if (request('filter_report_date_range') == 'this_month') {
            //start date
            $projects->whereDate('project_created', '>=', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'));
            //end date
            $projects->whereDate('project_created', '<=', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'));
        }

        //[date] - last month
        if (request('filter_report_date_range') == 'last_month') {
            //start date
            $projects->whereDate('project_created', '>=', \Carbon\Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'));
            //end date
            $projects->whereDate('project_created', '<=', \Carbon\Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d'));
        }

        //[date] - this year
        if (request('filter_report_date_range') == 'this_year') {
            //start date
            $projects->whereDate('project_created', '>=', \Carbon\Carbon::now()->startOfYear()->format('Y-m-d'));
            //end date
            $projects->whereDate('project_created', '<=', \Carbon\Carbon::now()->endOfYear()->format('Y-m-d'));
        }

        //[date] - last year
        if (request('filter_report_date_range') == 'last_year') {
            //start date
            $projects->whereDate('project_created', '>=', \Carbon\Carbon::now()->subYear()->startOfYear()->format('Y-m-d'));
            //end date
            $projects->whereDate('project_created', '<=', \Carbon\Carbon::now()->subYear()->endOfYear()->format('Y-m-d'));
        }

        //project status
        if (is_array(request('filter_project_status')) && !empty(array_filter(request('filter_project_status')))) {
            $projects->whereIn('project_status', request('filter_project_status'));
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('projects', request('orderby'))) {
                $projects->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'category':
                $projects->orderBy('category_name', request('sortorder'));
                break;
            case 'count_tasks_due':
                $projects->orderBy('count_tasks_due', request('sortorder'));
                break;
            case 'count_tasks_completed':
                $projects->orderBy('count_tasks_completed', request('sortorder'));
                break;
            case 'sum_hours':
                $projects->orderBy('sum_hours', request('sortorder'));
                break;
            case 'sum_expenses':
                $projects->orderBy('sum_expenses', request('sortorder'));
                break;
            case 'sum_invoices':
                $projects->orderBy('sum_invoices', request('sortorder'));
                break;
            case 'sum_payments':
                $projects->orderBy('sum_payments', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $projects->orderBy('project_id', 'asc');
        }

        //page limit
        if (request()->filled('page_limit') && is_numeric(request('page_limit'))) {
            $rows = $projects->paginate(request('page_limit'));
        } else {
            $rows = $projects->paginate(1000000000000);
        }

        //we are returning sums
        if (isset($data['totals'])) {
            switch ($data['totals']) {
            case 'count_tasks_due':
                return $rows->sum('count_tasks_due');
            case 'count_tasks_completed':
                return $rows->sum('count_tasks_completed');
            case 'sum_hours':
                return $rows->sum('sum_hours');
            case 'sum_expenses':
                return $rows->sum('sum_expenses');
            case 'sum_invoices':
                return $rows->sum('sum_invoices');
            case 'sum_payments':
                return $rows->sum('sum_payments');
            default:
                return 0;
            }
        }

        //[pagination-links] - set the base link
        $rows->withPath('/report/projects/overview');

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
    public function getClient($id = '', $data = []) {

        //filter - project dates
        $start_date = $this->filterDates('start');
        $end_date = $this->filterDates('end');

        $clients = $this->client->newQuery();
        $clients->leftJoin('projects', 'projects.project_clientid', '=', 'clients.client_id');

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

        //count_projects_on_hold
        $clients->selectRaw("COALESCE((SELECT COUNT(project_id)
                               FROM projects
                               WHERE project_clientid = clients.client_id
                               AND project_date_start >= '$start_date'
                               AND project_date_start <= '$end_date'
                               AND project_type = 'project'
                               AND project_status = 'on_hold'), 0) AS count_projects_on_hold");

        //count_projects_not_started
        $clients->selectRaw("COALESCE((SELECT COUNT(project_id)
                               FROM projects
                               WHERE project_clientid = clients.client_id
                               AND project_date_start >= '$start_date'
                               AND project_date_start <= '$end_date'
                               AND project_type = 'project'
                               AND project_status = 'not_started'), 0) AS count_projects_not_started");

        //count_projects_cancelled
        $clients->selectRaw("COALESCE((SELECT COUNT(project_id)
                               FROM projects
                               WHERE project_clientid = clients.client_id
                               AND project_date_start >= '$start_date'
                               AND project_date_start <= '$end_date'
                               AND project_type = 'project'
                               AND project_status = 'cancelled'), 0) AS count_projects_cancelled");

        //count_projects_completed
        $clients->selectRaw("COALESCE((SELECT COUNT(project_id)
                               FROM projects
                               WHERE project_clientid = clients.client_id
                               AND project_date_start >= '$start_date'
                               AND project_date_start <= '$end_date'
                               AND project_type = 'project'
                               AND project_status = 'completed'), 0) AS count_projects_completed");

        //count_tasks_due
        $clients->selectRaw("COALESCE((SELECT COUNT(task_id)
                               FROM tasks
                               WHERE task_projectid IN
                               (SELECT project_id
                                       FROM projects
                                       WHERE project_clientid = clients.client_id
                                       AND project_date_start >= '$start_date'
                                       AND project_date_start <= '$end_date')
                               AND task_status NOT IN (2)), 0) AS count_tasks_due");

        //count_tasks_completed
        $clients->selectRaw("COALESCE((SELECT COUNT(task_id)
                               FROM tasks
                               WHERE task_projectid IN
                               (SELECT project_id
                                       FROM projects
                                       WHERE project_clientid = clients.client_id
                                       AND project_date_start >= '$start_date'
                                       AND project_date_start <= '$end_date')
                               AND task_status = 2), 0) AS count_tasks_completed");

        //sum_payments
        $clients->selectRaw("(SELECT COALESCE(SUM(payment_amount), 0)
                                      FROM payments
                                      WHERE payment_projectid IN
                                      (SELECT project_id
                                              FROM projects
                                              WHERE project_clientid = clients.client_id
                                              AND project_date_start >= '$start_date'
                                              AND project_date_start <= '$end_date')
                                      AND payment_projectid IS NOT NULL)
                                      AS x_sum_payments");
        $clients->selectRaw('(SELECT COALESCE(x_sum_payments, 0.00))
                                      AS sum_payments');

        //sum_invoices
        $clients->selectRaw("(SELECT COALESCE(SUM(bill_final_amount), 0)
                                      FROM invoices
                                      WHERE bill_projectid IN
                                      (SELECT project_id
                                              FROM projects
                                              WHERE project_clientid = clients.client_id
                                              AND project_date_start >= '$start_date'
                                              AND project_date_start <= '$end_date')
                                      AND bill_status NOT IN ('draft')
                                      AND bill_projectid IS NOT NULL)
                                      AS x_sum_invoices");
        $clients->selectRaw('(SELECT COALESCE(x_sum_invoices, 0.00))
                                      AS sum_invoices');

        //sum_expenses
        $clients->selectRaw("(SELECT COALESCE(SUM(expense_amount), 0)
                                      FROM expenses
                                      WHERE expense_projectid IN
                                      (SELECT project_id
                                              FROM projects
                                              WHERE project_clientid = clients.client_id
                                              AND project_date_start >= '$start_date'
                                              AND project_date_start <= '$end_date')
                                      AND expense_projectid IS NOT NULL)
                                      AS x_sum_expenses");
        $clients->selectRaw('(SELECT COALESCE(x_sum_expenses, 0.00))
                                      AS sum_expenses');

        //sum_hours
        $clients->selectRaw("COALESCE((SELECT SUM(timer_time)
                                      FROM timers
                                      WHERE timer_projectid IN
                                      (SELECT project_id
                                              FROM projects
                                              WHERE project_clientid = clients.client_id
                                              AND project_date_start >= '$start_date'
                                              AND project_date_start <= '$end_date')
                                      AND timer_status = 'stopped'), 0)
                                      AS sum_hours");

        $clients->groupBy('clients.client_id');

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
            case 'count_projects_not_started':
                return $rows->sum('count_projects_not_started');
            case 'count_projects_on_hold':
                return $rows->sum('count_projects_on_hold');
            case 'count_projects_cancelled':
                return $rows->sum('count_projects_cancelled');
            case 'count_projects_completed':
                return $rows->sum('count_projects_completed');
            case 'count_projects_pending':
                return $rows->sum('count_projects_pending');
            case 'count_tasks_due':
                return $rows->sum('count_tasks_due');
            case 'count_tasks_completed':
                return $rows->sum('count_tasks_completed');
            case 'sum_hours':
                return $rows->sum('sum_hours');
            case 'sum_expenses':
                return $rows->sum('sum_expenses');
            case 'sum_invoices':
                return $rows->sum('sum_invoices');
            case 'sum_payments':
                return $rows->sum('sum_payments');
            default:
                return 0;
            }
        }

        //[pagination-links] - set the base link
        $rows->withPath('/report/projects/client');

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