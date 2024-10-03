<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [download] precheck processes for files
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Files;
use App\Permissions\FilePermissions;
use Closure;
use Log;

class Move {

    /**
     * The file permisson repository instance.
     */
    protected $filepermissons;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(FilePermissions $filepermissons) {

        $this->filepermissons = $filepermissons;
    }

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] files
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $errors = 0;

        //basic validation
        if (is_array(request('ids'))) {

            foreach (request('ids') as $unique_id => $value) {
                if ($value == 'on') {
                    if ($file = \App\Models\File::Where('file_uniqueid', $unique_id)->first()) {
                        if (!$this->filepermissons->check('move', $file)) {
                            Log::error("user does not have permission to move a file in the files list", ['process' => '[files][move]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'file_uniqueid' => $unique_id]);
                            $errors++;
                        }
                    }else{
                        Log::error("a file in the list could not be found", ['process' => '[files][move]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'file_uniqueid' => $unique_id]);
                        $errors++;
                    }
                }
            }

            //check
            if ($errors == 0) {
                return $next($request);
            }

        } else {
            abort(404);
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][files][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }
}
