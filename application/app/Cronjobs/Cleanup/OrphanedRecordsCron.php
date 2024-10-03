<?php

/** -------------------------------------------------------------------------------------------------------------------
 * @description
 * This cronjob is envoked by by the task scheduler which is in 'application/app/Console/Kernel.php'
 *
 * @details
 * Ensure database records integrity by checking records that have invalid references/links to other tables/resources
 * The cleanup is needed to avoid sql errors, incorrect  report data, etcc
 *
 * @package    Grow CRM
 * @author     NextLoop
 *
 *------------------------------------------------------------------------------------------------------------------------*/

namespace App\Cronjobs\Cleanup;
use App\Repositories\DestroyRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class OrphanedRecordsCron {

    protected $destroyrepo;

    public function __invoke(
        DestroyRepository $destroyrepo
    ) {

        $this->destroyrepo = $destroyrepo;

        //[MT] - (check we have a tenant)
        if (env('MT_TPYE')) {
            if (\Spatie\Multitenancy\Models\Tenant::current() == null) {
                return;
            }
            //boot system settings
            middlwareBootSystem();
            middlewareBootMail();
        }

        //cleanup tasks
        $this->cleanupTasks();

        //cleanup projects
        $this->cleanupProjects();

        //cleanup paymens
        $this->cleanupPayments();

        //cleanup timers
        $this->cleanupTimers();

        //cleanup invoices
        $this->cleanupInvoices();

        //cleanup invoices
        $this->cleanupEstimates();
    }

    /**
     * All tasks should be linked to a project (even 'spaces' or 'templates' tasks)
     * Find tasks that are not linked and delete them. They should have been deleted when the parent was deleted
     *
     */
    public function cleanupTasks() {

        //get all tasks that are not linked to any project
        if ($tasks = \App\Models\Task::WhereNotIn('task_projectid', function ($query) {
            $query->select('project_id')->from('projects')->groupby('project_id');
        })->get()) {

            //loop through and delete the task
            foreach ($tasks as $task) {
                Log::info("Task with id (" . $task->task_id . ") is orphaned [it is not linked to a project]. It wil now be deleted", ['process' => '[OrphanedRecordsCron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => 1]);
                $this->destroyrepo->destroyTask($task->task_id);
            }

        }
    }

    /**
     * Various cleanups on projects
     *
     */
    public function cleanupProjects() {

        /**------------------------------------------------------------------------------------------
         * (1) Find any projects that have an invalid category id (i.e. category nolonger exists)
         *     Set the project to default project cagetory id [1]
        ------------------------------------------------------------------------------------------ */
        \App\Models\Project::whereNotIn('project_categoryid', function ($query) {
            $query->select('category_id')->from('categories')->where('category_type', 'project');
        })
            ->update(['project_categoryid' => 1]);

    }

    /**
     * Various cleanups on payments
     *
     */
    public function cleanupPayments() {

        /**------------------------------------------------------------------------------------------
         * (1) Find and delete any payments that have an invalid invoice_id
         *    (i.e. invoice nolonger exists)
        ------------------------------------------------------------------------------------------ */
        if (Schema::hasColumn('payments', 'payment_type')) {
            \App\Models\Payment::whereNotIn('payment_invoiceid', function ($query) {
                $query->select('bill_invoiceid')->from('invoices');
            })->Where('payment_type', 'invoice')->delete();
        }

        /**------------------------------------------------------------------------------------------
         * (2) Find and delete any payments that have an invalid subscription_id
         *    (i.e. subscription nolonger exists)
        ------------------------------------------------------------------------------------------ */
        if (Schema::hasColumn('payments', 'payment_type')) {
            \App\Models\Payment::whereNotIn('payment_subscriptionid', function ($query) {
                $query->select('subscription_id')->from('subscriptions');
            })->Where('payment_type', 'subscription')->delete();
        }

        /**------------------------------------------------------------------------------------------
         * (3) payments with 0.00 amount
        ------------------------------------------------------------------------------------------ */
        \App\Models\Payment::Where('payment_amount', 0)->delete();

    }

    /**
     * All timers should be linked to a client, project or task
     * Find timers that are not linked and delete them. They should have been deleted when the parent was deleted
     *
     */
    public function cleanupTimers() {

        //all timers with invalid client
        \App\Models\Timer::WhereNotIn('timer_clientid', function ($query) {
            $query->select('client_id')->from('clients');
        })->delete();

        //all timers with invalid project
        \App\Models\Timer::WhereNotIn('timer_projectid', function ($query) {
            $query->select('project_id')->from('projects');
        })->delete();

        //all timers with invalid task
        \App\Models\Timer::WhereNotIn('timer_taskid', function ($query) {
            $query->select('task_id')->from('tasks');
        })->delete();
    }

    /**
     * various invoice clean ups
     *
     */
    public function cleanupInvoices() {

        //(1) Find invoices that are missing a unique id and give them one
        if (Schema::hasColumn('invoices', 'bill_uniqueid')) {
            \App\Models\Invoice::where('bill_uniqueid', '')->OrWhere('bill_uniqueid', null)
                ->update([
                    'bill_uniqueid' => str_unique(),
                ]);
        }

    }

    /**
     * various estimates clean ups
     *
     */
    public function cleanupEstimates() {

        //(1) Find invoices that are missing a unique id and give them one
        if (Schema::hasColumn('estimates', 'bill_uniqueid')) {
            \App\Models\Estimate::where('bill_uniqueid', '')->OrWhere('bill_uniqueid', null)
                ->update([
                    'bill_uniqueid' => str_unique(),
                ]);
        }

    }

}