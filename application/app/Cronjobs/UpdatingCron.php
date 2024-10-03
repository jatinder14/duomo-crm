<?php

/** -------------------------------------------------------------------------------------------------
 * TEMPLATE
 * This cronjob is envoked by by the task scheduler which is in 'application/app/Console/Kernel.php'
 * @actions
 *   - Check database for an 'cron'updates that need to be run
 *   - These updates will match a function in the helper functions (e.g. application/updating/updating_1.php)
 *   - The function will named as example: (crom_update_1_11)
 * @package    Grow CRM
 * @author     NextLoop
 *---------------------------------------------------------------------------------------------------*/

namespace App\Cronjobs;
use Illuminate\Support\Facades\Log;

class UpdatingCron {

    public function __invoke() {

        //increase execution time
        set_time_limit(300);

        //[MT] - tenants only
        if (env('MT_TPYE')) {
            if (\Spatie\Multitenancy\Models\Tenant::current() == null) {
                return;
            }
            //boot system settings
            middlwareBootSystem();
            middlewareBootMail();
        }

        //log that its run
        Log::info("updating cronob has started", ['process' => '[updating-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //and more
        $this->runUpdates();

    }

    /**
     * Check if there are any updates that need to be run
     *  @return null
     */
    private function runUpdates() {

        //do we have updates
        if (!$update = \App\Models\Updating::Where('updating_status', 'new')->Where('updating_type', 'cronjob')->OrderBy('updating_id', 'asc')->first()) {
            return;
        }

        //mark as processing
        $update->updating_status = 'processing';
        $update->updating_started_date = now();
        $update->save();

        //check if a function was specified (as required)
        if ($update->updating_function_name == '') {
            //error message
            $error = 'a function was not specified for this update';
            //mark as failed
            $update->updating_status = 'failed';
            $update->save();
            //log this error
            Log::error($error, ['process' => '[updating-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return;
        }

        //check if the specified function exists
        if (!function_exists($update->updating_function_name)) {
            //error message
            $error = 'the specified function for this update does not exist';
            //mark as failed
            $update->updating_status = 'failed';
            $update->updating_system_log = $error;
            $update->save();
            //log this error
            Log::error($error, ['process' => '[updating-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return;
        }

        //execute the function
        try {
            call_user_func($update->updating_function_name);
        } catch (Exception $e) {
            //errors
            $error = 'error processing the specified function';
            $error_message = $e->getMessage();
            //mark as failed
            $update->updating_status = 'failed';
            $update->updating_system_log = $error;
            $update->save();
            Log::error($error, ['process' => '[updating-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'error' => $error_message]);
            return;
        }

        //mark as started
        $update->updating_status = 'completed';
        $update->updating_completed_date = now();
        $update->save();
    }
}