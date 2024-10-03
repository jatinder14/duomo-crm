<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [destroy] precheck processes for files
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Files;
use App\Permissions\FilePermissions;
use Closure;
use Log;

class Destroy {

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

        //for a single item request - merge into an $ids[x] array and set as if checkox is selected (on)
        if (is_numeric($request->route('file'))) {
            $ids[$request->route('file')] = 'on';
            request()->merge([
                'ids' => $ids,
            ]);
        }

        //loop through each file and check permissions
        if (is_array(request('ids'))) {

            //validate each item in the list exists
            foreach (request('ids') as $id => $value) {
                //only checked items
                if ($value == 'on') {
                    //validate
                    if (is_numeric($id)) {
                        if (!$file = \App\Models\File::Where('file_id', $id)->first()) {
                            abort(409, __('lang.one_of_the_selected_items_nolonger_exists'));
                        }
                    } else {
                        if (!$file = \App\Models\File::Where('file_uniqueid', $id)->first()) {
                            abort(409, __('lang.one_of_the_selected_items_nolonger_exists'));
                        }
                    }
                }
            }

            //check permissions
            if ($file->fileresource_id > 0) {
                //project files and other regular files
                if ($this->filepermissons->check('delete', $file)) {
                    return $next($request);
                }
            } else {
                //projet template files
                if (auth()->user()->role->role_templates_projects >= 2) {
                    return $next($request);
                }
            }

        } else {
            //no items were passed with this request
            Log::error("no items were sent with this request", ['process' => '[permissions][invoices][change-category]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'invoice id' => $bill_invoiceid ?? '']);
            abort(409);
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][files][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }
}
