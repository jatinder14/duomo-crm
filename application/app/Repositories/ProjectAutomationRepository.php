<?php

/** --------------------------------------------------------------------------------
 * Process project automation
 *
 * @fooo    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;
use App\Repositories\EmailerRepository;
use App\Repositories\EstimateRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventTrackingRepository;
use App\Repositories\InvoiceGeneratorRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\ProjectManagerRepository;
use App\Repositories\TaskRepository;
use App\Repositories\TimerRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;

class ProjectAutomationRepository {

    private $settings;
    private $assignedrepo;
    private $eventrepo;
    private $trackingrepo;
    private $emailerrepo;
    private $userrepo;
    private $taskrepo;
    private $estimaterepo;
    private $invoicerepo;
    private $invoicegenerator;
    private $timerrepo;

    public function __construct(
        ProjectManagerRepository $assignedrepo,
        EventRepository $eventrepo,
        EmailerRepository $emailerrepo,
        TaskRepository $taskrepo,
        UserRepository $userrepo,
        EstimateRepository $estimaterepo,
        InvoiceGeneratorRepository $invoicegenerator,
        InvoiceRepository $invoicerepo,
        TimerRepository $timerrepo,
        EventTrackingRepository $trackingrepo) {

        //system settings
        $this->settings = \App\Models\Settings::Where('settings_id', 1)->first();

        $this->assignedrepo = $assignedrepo;
        $this->eventrepo = $eventrepo;
        $this->trackingrepo = $trackingrepo;
        $this->emailerrepo = $emailerrepo;
        $this->userrepo = $userrepo;
        $this->taskrepo = $taskrepo;
        $this->estimaterepo = $estimaterepo;
        $this->invoicerepo = $invoicerepo;
        $this->invoicegenerator = $invoicegenerator;
        $this->trackingrepo = $trackingrepo;

    }

    /**
     * process the automation
     *
     * @param  obj  $roject project model
     * @return \Illuminate\Http\Response
     */
    public function process($project) {

        Log::info("project automation started", ['process' => '[project-automation]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $project->project_id]);

        //check if the estimate has automation enabled
        if ($project->project_automation_status != 'enabled') {
            Log::info("project automation is disabled - will now exit", ['process' => '[project-automation]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $project->project_id]);
            return;
        }

        //(1) convert estimates to invoices
        if ($project->project_automation_convert_estimates_to_invoices == 'yes') {
            //create
            $this->convertEstimatesToInvoices($project);
        }

        //(2) invoice unbilled hours
        if ($project->project_automation_invoice_unbilled_hours == 'yes') {
            //create
            $invoice = $this->invoiceUnbilledHours($project);
        }

        Log::info("project automation completed", ['process' => '[project-automation]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $project->project_id]);

    }

    /**
     * convert estimates to invoices
     *
     * @param  obj  $project project model
     * @return \Illuminate\Http\Response
     */
    public function convertEstimatesToInvoices($project) {

        Log::info("looking for estimates to convert to invoices", ['process' => '[project-automation]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $project->project_id]);

        //get all estimates for this project
        $estimates = \App\Models\Estimate::Where('bill_projectid', $project->project_id)->get();

        //convert the estimate to an invoice
        foreach ($estimates as $estimate) {

            Log::info("an estimate has been found", ['process' => '[project-automation]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'estimate_id' => $estimate->bill_estimateid]);

            //check if automation has not already run for this estimate
            if (is_numeric($estimate->estimate_automation_log_created_invoice_id)) {
                if (\App\Models\Invoice::Where('bill_invoiceid', $estimate->estimate_automation_log_created_invoice_id)->exists()) {
                    Log::info("an invoice has previously been created for this estimate. will skip it", ['process' => '[project-automation]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'estimate_id' => $estimate->bill_estimateid, 'invoice_id' => $estimate->estimate_automation_log_created_invoice_id]);
                    continue;
                }
            }

            //check if estimate is not in draft mode
            if (config('system.settings2_projects_automation_skip_draft_estimates') == 'yes') {
                if ($estimate->bill_status == 'draft') {
                    Log::info("the estimate is in (draft) status. will skip it", ['process' => '[project-automation]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'estimate_id' => $estimate->bill_estimateid, 'invoice_id' => $estimate->estimate_automation_log_created_invoice_id]);
                    continue;
                }
            }

            //check if estimate is not in declined mode
            if (config('system.settings2_projects_automation_skip_declined_estimates') == 'yes') {
                if ($estimate->bill_status == 'declined') {
                    Log::info("the estimate is in (declined) status. will skip it", ['process' => '[project-automation]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'estimate_id' => $estimate->bill_estimateid, 'invoice_id' => $estimate->estimate_automation_log_created_invoice_id]);
                    continue;
                }
            }

            $invoice = $this->estimaterepo->convertEstimateToInvoice($estimate->bill_estimateid);
            $invoice->bill_date = now();
            $invoice->bill_due_date = \Carbon\Carbon::now()->addDays($project->project_automation_invoice_due_date)->format('Y-m-d');
            $invoice->bill_creatorid = 0;
            $invoice->bill_status = 'due';
            $invoice->bill_creatorid = 0;
            $invoice->bill_uniqueid = str_unique();
            $invoice->save();

            //refresh invoice
            $this->invoicerepo->refreshInvoice($invoice);

            //mark the estimate as automated
            \App\Models\Estimate::where('bill_estimateid', $estimate->bill_estimateid)
                ->update([
                    'estimate_automation_log_created_invoice_id' => $invoice->bill_invoiceid,
                ]);

            Log::info("converted an estimate to an invoice", ['process' => '[project-automation]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $project->project_id, 'estimate_id' => $estimate->bill_estimateid, 'invoice_id' => $invoice->bill_invoiceid]);

            //email to client
            if ($project->project_automation_invoice_email_client == 'yes') {
                $invoice->bill_date_sent_to_customer = now();
                $invoice->save();
                $this->emailInvoice($invoice);
            }
        }

    }

    /**
     * create an invoice from all unbilled timers for this project
     * //[TODO] this is not complete - work in progress
     *
     * @param  obj  $project project model
     * @return \Illuminate\Http\Response
     */
    public function invoiceUnbilledHours($project) {

        Log::info("looking for unbilled hours to invocie", ['process' => '[project-automation]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $project->project_id]);

        //only stopped timers
        request()->merge([
            'filter_timer_status' => 'stopped',
            'filter_grouping' => 'tasks_unbilled',
            'filter_timer_projectid' => $project->project_id,
        ]);

        //get time sheets
        $timesheets = $this->timerrepo->search(); //TODO - group timesheets by task

        //create a new invoice
        $invoice = new \App\Models\Invoice();
        $invoice->bill_clientid = $project->project_clientid;
        $invoice->bill_projectid = $project->project_id;
        $invoice->bill_creatorid = 0;
        $invoice->bill_date = now();
        $invoice->bill_due_date = \Carbon\Carbon::now()->addDays($project->project_automation_invoice_due_date)->format('Y-m-d');
        $invoice->bill_terms = $settings->settings_invoices_default_terms_conditions;
        $invoice->bill_uniqueid = str_unique();
        $invoice->save();

        //add lineitems
        foreach ($timesheets as $timesheet) {

            //defaults
            $hours_total = 0;
            $minutes_total = 0;

            //get hours and minutes
            $hours = runtimeSecondsWholeHours($timesheet->time);
            $minutes = runtimeSecondsWholeMinutes($timesheet->time);

            //total for hours
            if ($hours > 0 && $billing_rate > 0) {
                $hours_total = ($task->hours * $billing_rate);
            }

            //total for minutes
            if ($task->minutes > 0 && $billing_rate > 0) {
                $minutes_total = ($task->minutes * $billing_rate / 60);
            }

            //create a new invoice
            $lineitem = new \App\Models\Lineitem();
            $lineitem->lineitem_description = $timesheet->foo;
            $lineitem->lineitem_quantity = $timesheet->foo;
            $lineitem->lineitem_rate = $timesheet->foo;
            $lineitem->lineitem_unit = $timesheet->foo;
            $lineitem->lineitem_total = $timesheet->foo;
            $lineitem->lineitemresource_linked_type = $timesheet->foo;
            $lineitem->lineitemresource_linked_id = $timesheet->foo;
            $lineitem->lineitem_type = $timesheet->foo;
            $lineitem->lineitem_position = $timesheet->foo;
            $lineitem->lineitemresource_type = $timesheet->foo;
            $lineitem->lineitemresource_id = $timesheet->foo;
            $lineitem->lineitem_time_timers_list = $timesheet->foo;
            $lineitem->lineitem_time_hours = runtimeSecondsWholeHours($task->time);
            $lineitem->lineitem_time_minutes = runtimeSecondsWholeMinutes($task->time);
            $lineitem->save();
        }

    }

    /**
     * email invoice to the client
     * @param int $id invoice id
     * @return \Illuminate\Http\Response
     */
    public function emailInvoice($invoice) {

        //generate the invoice
        if (!$payload = $this->invoicegenerator->generate($invoice->bill_invoiceid)) {
            return;
        }

        //invoice
        $invoice = $payload['bill'];

        /** ----------------------------------------------
         * send email [queued]
         * ----------------------------------------------*/
        $users = $this->userrepo->getClientUsers($invoice->bill_clientid, 'owner', 'collection');
        //other data
        $data = [];
        foreach ($users as $user) {
            $mail = new \App\Mail\PublishInvoice($user, $data, $invoice);
            $mail->build();
        }

        Log::info("invoice has been emailed to the client", ['process' => '[project-automation]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'invoice_id' => $invoice->bill_invoiceid]);
    }

}