<?php

/** --------------------------------------------------------------------------------
 * [NOTES Aug 2022]
 *   - The provider must run before all other servive providers in (config/app.php)
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Providers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Log;

class UpdateServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {

        //do not run this if setup has not completed
        if (env('SETUP_STATUS') != 'COMPLETED') {
            //skip this provider
            return;
        }

        if (request()->ajax()) {
            return;
        }

        //get a list of all the sql files in the updates folder
        $path = BASE_DIR . "/updates";
        $files = File::files($path);
        $updated = false;
        foreach ($files as $file) {

            //file details
            $filename = $file->getFilename();
            $extension = $file->getExtension();
            $filepath = $file->getPathname();

            //runtime function name (e.g. updating_1_13)
            $function_name = str_replace('.sql', '', $filename);
            $function_name = str_replace('.', '_', "updating_" . $function_name);

            //run the routine if this is an sql file
            if ($extension == 'sql') {
                if (\App\Models\Update::Where('update_mysql_filename', $filename)->doesntExist()) {

                    Log::info("the mysql file ($filename) has not previously been executed. Will now execute it", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename]);

                    //execute the SQL
                    try {
                        //db
                        DB::unprepared(file_get_contents($filepath));

                        //save record
                        $record = new \App\Models\Update();
                        $record->update_mysql_filename = $filename;
                        $record->save();

                        Log::info("the mysql file ($filename) executed ok - will now delete it", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename]);
                    } catch (Exception $e) {
                        Log::error("the mysql file ($filename) could not be deleted", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename]);
                    }

                    //delete the file
                    try {
                        unlink($path . "/$filename");
                    } catch (Exception $e) {
                        Log::error("the mysql file ($filename) could not be deleted", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename]);
                    }

                    /** -------------------------------------------------------------------------
                     * Run any updating function, if it exists
                     * as found in the file - application/updating/updating_1.php ...etc
                     * -------------------------------------------------------------------------*/
                    Log::info("checking if a runtime function: [$function_name()] exists", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename]);
                    if (function_exists($function_name)) {

                        Log::info("runtime function: [$function_name()] was found. It will now be executed", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename]);

                        try {
                            call_user_func($function_name);
                            Log::info("the runtime function: [$function_name()] was executed", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename]);
                        } catch (Exception $e) {
                            Log::critical("updating runtime function: [$function_name()] could not be executed. Error: " . $e->getMessage(), ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename]);
                        }
                    }

                    //finish
                    Log::info("updating of mysql file ($filename) has been completed", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename]);
                } else {
                    try {
                        unlink($path . "/$filename");
                        Log::info("found a non mysql file ($filename) inside the updates folder. Will try to delete it", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename]);
                    } catch (Exception $e) {
                        Log::error("the file ($filename) could not be deleted", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename]);
                    }
                }
                //we have done an update
                $updated = true;
            }
        }

        //finish - clear cache
        if ($updated) {
            \Artisan::call('cache:clear');
            \Artisan::call('route:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
