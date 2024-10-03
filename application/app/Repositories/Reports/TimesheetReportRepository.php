<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories\Reports;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class TimesheetReportRepository {

    /**
     * The repository instance.
     */
    protected $project;
    protected $category;
    protected $client;

    /**
     * Inject dependecies
     */
    public function __construct(Project $project, Client $client, User $user) {
        $this->project = $project;
        $this->client = $client;
        $this->user = $user;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object projects collection
     */
    public function getTeam($id = '', $data = []) {

        //filter - project dates
        $start_date = $this->filterDates('start');
        $end_date = $this->filterDates('end');

        $timesheets = $this->user->newQuery();

        $timesheets->selectRaw('*');

        //default where
        $timesheets->whereRaw("1 = 1");

        $timesheets->Where('type', 'team');

        //sum_hours
        $timesheets->selectRaw("COALESCE((SELECT SUM(timer_time)
                                      FROM timers
                                      WHERE timer_status = 'stopped'
                                      AND timer_creatorid = users.id
                                      AND timer_stopped >= $start_date
                                      AND timer_stopped <= $end_date), 0)
                                      AS sum_hours");

        //sum_not_invoiced
        $timesheets->selectRaw("COALESCE((SELECT SUM(timer_time)
                                      FROM timers
                                      WHERE timer_status = 'stopped'
                                      AND timer_billing_status = 'not_invoiced'
                                      AND timer_creatorid = users.id
                                      AND timer_stopped >= $start_date
                                      AND timer_stopped <= $end_date), 0)
                                      AS sum_not_invoiced");

        //sum_invoiced
        $timesheets->selectRaw("COALESCE((SELECT SUM(timer_time)
                                      FROM timers
                                      WHERE timer_status = 'stopped'
                                      AND timer_billing_status = 'invoiced'
                                      AND timer_creatorid = users.id
                                      AND timer_stopped >= $start_date
                                      AND timer_stopped <= $end_date), 0)
                                      AS sum_invoiced");

        //sum_hours
        $timesheets->selectRaw("(SELECT role_name FROM roles
                                        WHERE role_id = users.role_id)
                                        AS role_name");

        //page limit
        if (request()->filled('page_limit') && is_numeric(request('page_limit'))) {
            $rows = $timesheets->paginate(request('page_limit'));
        } else {
            $rows = $timesheets->paginate(1000000000000);
        }

        //we are returning sums
        if (isset($data['totals'])) {
            switch ($data['totals']) {
            case 'sum_hours':
                return $rows->sum('sum_hours');
            case 'sum_not_invoiced':
                return $rows->sum('sum_not_invoiced');
            case 'sum_invoiced':
                return $rows->sum('sum_invoiced');
            default:
                return 0;
            }
        }

        //[pagination-links] - set the base link
        $rows->withPath('/report/timesheets/team');

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

        $timesheets = $this->client->newQuery();

        $timesheets->selectRaw('*');

        //default where
        $timesheets->whereRaw("1 = 1");

        //sum_hours
        $timesheets->selectRaw("COALESCE((SELECT SUM(timer_time)
                                      FROM timers
                                      WHERE timer_status = 'stopped'
                                      AND timer_clientid = clients.client_id
                                      AND timer_stopped >= $start_date
                                      AND timer_stopped <= $end_date), 0)
                                      AS sum_hours");

        //sum_not_invoiced
        $timesheets->selectRaw("COALESCE((SELECT SUM(timer_time)
                                      FROM timers
                                      WHERE timer_status = 'stopped'
                                      AND timer_billing_status = 'not_invoiced'
                                      AND timer_clientid = clients.client_id
                                      AND timer_stopped >= $start_date
                                      AND timer_stopped <= $end_date), 0)
                                      AS sum_not_invoiced");

        //sum_invoiced
        $timesheets->selectRaw("COALESCE((SELECT SUM(timer_time)
                                      FROM timers
                                      WHERE timer_status = 'stopped'
                                      AND timer_billing_status = 'invoiced'
                                      AND timer_clientid = clients.client_id
                                      AND timer_stopped >= $start_date
                                      AND timer_stopped <= $end_date), 0)
                                      AS sum_invoiced");
        //page limit
        if (request()->filled('page_limit') && is_numeric(request('page_limit'))) {
            $rows = $timesheets->paginate(request('page_limit'));
        } else {
            $rows = $timesheets->paginate(1000000000000);
        }

        //we are returning sums
        if (isset($data['totals'])) {
            switch ($data['totals']) {
            case 'sum_hours':
                return $rows->sum('sum_hours');
            case 'sum_not_invoiced':
                return $rows->sum('sum_not_invoiced');
            case 'sum_invoiced':
                return $rows->sum('sum_invoiced');
            default:
                return 0;
            }
        }

        //[pagination-links] - set the base link
        $rows->withPath('/report/timesheets/client');

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
    public function getProject($id = '', $data = []) {

        //filter - project dates
        $start_date = $this->filterDates('start');
        $end_date = $this->filterDates('end');

        $timesheets = $this->project->newQuery();

        $timesheets->selectRaw('*');

        //default where
        $timesheets->whereRaw("1 = 1");

        $timesheets->where('project_type','project');

        //sum_hours
        $timesheets->selectRaw("COALESCE((SELECT SUM(timer_time)
                                      FROM timers
                                      WHERE timer_status = 'stopped'
                                      AND timer_projectid = projects.project_id
                                      AND timer_stopped >= $start_date
                                      AND timer_stopped <= $end_date), 0)
                                      AS sum_hours");

        //sum_not_invoiced
        $timesheets->selectRaw("COALESCE((SELECT SUM(timer_time)
                                      FROM timers
                                      WHERE timer_status = 'stopped'
                                      AND timer_billing_status = 'not_invoiced'
                                      AND timer_projectid = projects.project_id
                                      AND timer_stopped >= $start_date
                                      AND timer_stopped <= $end_date), 0)
                                      AS sum_not_invoiced");

        //sum_invoiced
        $timesheets->selectRaw("COALESCE((SELECT SUM(timer_time)
                                      FROM timers
                                      WHERE timer_status = 'stopped'
                                      AND timer_billing_status = 'invoiced'
                                      AND timer_projectid = projects.project_id
                                      AND timer_stopped >= $start_date
                                      AND timer_stopped <= $end_date), 0)
                                      AS sum_invoiced");
         
         //$timesheets->Where('sum_hours', '>', 0);

        //page limit
        if (request()->filled('page_limit') && is_numeric(request('page_limit'))) {
            $rows = $timesheets->paginate(request('page_limit'));
        } else {
            $rows = $timesheets->paginate(1000000000000);
        }

        //we are returning sums
        if (isset($data['totals'])) {
            switch ($data['totals']) {
            case 'sum_hours':
                return $rows->sum('sum_hours');
            case 'sum_not_invoiced':
                return $rows->sum('sum_not_invoiced');
            case 'sum_invoiced':
                return $rows->sum('sum_invoiced');
            default:
                return 0;
            }
        }

        //[pagination-links] - set the base link
        $rows->withPath('/report/timesheets/project');

        //add some params
        $rows->appends([
            'action' => 'load',
            'type' => 'pagination',
        ]);

        //return
        return $rows;

    }
    function filterDates($type = '') {

        //exteme defaults
        $start_date = '0000-01-01';
        $end_date = '9999-01-01';

        $start_date = \Carbon\Carbon::createFromFormat('Y-m-d', $start_date)->timestamp;
        $end_date = \Carbon\Carbon::createFromFormat('Y-m-d', $end_date)->timestamp;

        //[date] - range
        if (request('filter_report_date_range') == 'custom_range') {
            //start date
            if (request()->filled('filter_report_date_start')) {
                $start_date = request('filter_report_date_start');
                $start_date = \Carbon\Carbon::createFromFormat('Y-m-d', $start_date)->timestamp;
            }
            //end date
            if (request()->filled('filter_report_date_end')) {
                $end_date = request('filter_report_date_end');
                $end_date = \Carbon\Carbon::createFromFormat('Y-m-d', $end_date)->timestamp;
            }
        }

        //[date] - this month
        if (request('filter_report_date_range') == 'this_month') {
            //start date
            $start_date = \Carbon\Carbon::now()->startOfMonth()->timestamp;
            //end date
            $end_date = \Carbon\Carbon::now()->endOfMonth()->timestamp;
        }

        //[date] - last month
        if (request('filter_report_date_range') == 'last_month') {
            //start date
            $start_date = \Carbon\Carbon::now()->subMonth()->startOfMonth()->timestamp;
            //end date
            $end_date = \Carbon\Carbon::now()->subMonth()->endOfMonth()->timestamp;
        }

        //[date] - this year
        if (request('filter_report_date_range') == 'this_year') {
            //start date
            $start_date = \Carbon\Carbon::now()->startOfYear()->timestamp;
            //end date
            $end_date = \Carbon\Carbon::now()->endOfYear()->timestamp;
        }

        //[date] - last year
        if (request('filter_report_date_range') == 'last_year') {
            //start date
            $start_date = \Carbon\Carbon::now()->subYear()->startOfYear()->timestamp;
            //end date
            $end_date = \Carbon\Carbon::now()->subYear()->endOfYear()->timestamp;
        }

        return ($type == 'start') ? $start_date : $end_date;
    }
}