<?php

/** -------------------------------------------------------------------------------------------------
 * TEMPLATE
 * This cronjob is envoked by by the task scheduler which is in 'application/app/Console/Kernel.php'
 * @package    Grow CRM
 * @author     NextLoop
 *---------------------------------------------------------------------------------------------------*/

namespace App\Cronjobs;
use App\Repositories\PublishEstimateRepository;
use App\Repositories\PublishInvoiceRepository;
use Log;

class ScheduledCron {

    public function __invoke(
        PublishInvoiceRepository $publishinvoicerepo,
        PublishEstimateRepository $publishestimaterepo
    ) {

        //[MT] - tenants only
        if (env('MT_TPYE')) {
            if (\Spatie\Multitenancy\Models\Tenant::current() == null) {
                return;
            }
            //boot system settings
            middlwareBootSystem();
            middlewareBootMail();
        }

        //publish scheduled invoices
        $this->publishScheduledInvoices($publishinvoicerepo);

        //publish scheduled estimate
        $this->publishScheduledEstimates($publishestimaterepo);

        //reset last cron run data
        \App\Models\Settings::where('settings_id', 1)
            ->update([
                'settings_cronjob_has_run' => 'yes',
                'settings_cronjob_last_run' => now(),
            ]);

    }

    /**
     * Publish all invoices scheduled for today
     *  @return null
     */
    public function publishScheduledInvoices($publishinvoicerepo) {

        $today = \Carbon\Carbon::now()->format('Y-m-d');

        //get pending invoices (10 at a time)
        if (!$invoices = \App\Models\Invoice::Where('bill_publishing_scheduled_date', '<=', $today)
            ->where('bill_publishing_type', 'scheduled')
            ->where('bill_publishing_scheduled_status', 'pending')
            ->take(10)->get()) {

            //none found
            return;
        }

        Log::info("Found (" . count($invoices) . ") invoices that are scheduled for publishing", ['process' => '[scheduled-cron][invoices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //publish each invoice
        foreach ($invoices as $invoice) {

            if ($publishinvoicerepo->publishInvoice($invoice->bill_invoiceid)) {

                //mark as poublished
                $invoice->bill_publishing_scheduled_status = 'published';
                $invoice->save();

                Log::info("Invoice (" . $invoice->bill_invoiceid . ") was published", ['process' => '[scheduled-cron][invoices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            } else {
                Log::error("Invoice (" . $invoice->bill_invoiceid . ") could not be published", ['process' => '[scheduled-cron][invoices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            }

        }
    }

    /**
     * Publish all estimates scheduled for today
     *  @return null
     */
    public function publishScheduledEstimates($publishestimaterepo) {

        $today = \Carbon\Carbon::now()->format('Y-m-d');

        //get pending estimates (10 at a time)
        if (!$estimates = \App\Models\Estimate::Where('bill_publishing_scheduled_date', '<=', $today)
            ->where('bill_publishing_type', 'scheduled')
            ->where('bill_publishing_scheduled_status', 'pending')
            ->take(10)->get()) {

            //none found
            return;
        }

        Log::info("Found (" . count($estimates) . ") estimates that are scheduled for publishing", ['process' => '[scheduled-cron][estimates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //publish each estimate
        foreach ($estimates as $estimate) {

            if ($publishestimaterepo->publishEstimate($estimate->bill_estimateid)) {

                //mark as poublished
                $estimate->bill_publishing_scheduled_status = 'published';
                $estimate->save();

                Log::info("Estimate (" . $estimate->bill_estimateid . ") was published", ['process' => '[scheduled-cron][estimates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            } else {
                Log::error("Estimate (" . $estimate->bill_estimateid . ") could not be published", ['process' => '[scheduled-cron][estimates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            }

        }
    }

}