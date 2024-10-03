<?php

/** ----------------------------------------------------------------------------------------------------------------------------------------
 * @browser-executed-method
 *
 *   - these methods will run as part of the CRM updating routine.
 *   - use for simple updates, which can be run when a page loaded in the browser
 *   - these methods are purely optional
 *   - method name must be the same as the sql update file name e.g. for (1.11.sql), the method must be named as: updating_1_11()
 *   - these methods must be run only by logged in user who is the main admin. Run checks as shown in the example: update_0_00()
 *
 * @cronjob-executed-methods
 *
 *   - these methods are used for tasks that take longer to execute and so must be run via a cronjob
 *   - the methods can be named anything that is suitable. example cronjob_update_1.11()
 *   - the method must be registered in the database table 'updating'
 *          [example] - updating_name = 'cronjob_update_1.11()'
 *                    - updating_update_version = '1.11'
 *                    - updating_request_type = 'cronjob'
 *                    - updating_completed = 'no'
 *                    - updating_completed_date = 'Grow CRM update for version 1.11'
 *
 *  - the cronjob will look for updates that are pending and if the method name exists, it will execute and marm as 'completed'
 *
 * @author
 *  Nextloop
 *-------------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * [EXAMPLE - BROWSER UPDATE]
 *
 * @date - August 2022
 *
 * @version - 1.11
 *
 * @details
 *  - routines for Grow CRM update version 1.11
 *  - this must be run by admin user (id:1)
 *
 */
function update_0_00() {

    //log
    Log::info("updating function (foo feature) has started", ['process' => '[updating-functions]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => 1]);

    //only logged in user
    if (!auth()->check()) {
        return;
    }

    //only th eadmin
    if (auth()->id() != 1) {
        return;
    }

    //execut code here

    //log
    Log::info("updating function (foo feature) has completed", ['process' => '[updating-functions]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => 1]);

}

/**
 * [EXAMPLE - CRONJOB UPDATE]
 *
 * @date - August 2022
 *
 * @version - 1.11
 *
 * @details
 *  - routines for Grow CRM update version 1.11
 *  - does not require admin user to execute
 *  - must be marked as 'completed' once its done
 *
 */
function cronjob_update_0_00_part_1() {

    //log
    Log::info("updating function (foo feature) has started", ['process' => '[updating-functions]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => 1]);

    //execut code here

    //log
    Log::info("updating function (foo feature) has completed", ['process' => '[updating-functions]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => 1]);

}