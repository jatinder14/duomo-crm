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

class Copy {

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

        //for a single item request - merge into an $ids[x] array and set as if checkox is selected (on)
        if (request()->filled('id')) {
            $ids[request('id')] = 'on';
            request()->merge([
                'ids' => $ids,
            ]);
        }

        //basic validation
        if (!is_array(request('ids'))) {
            abort(404);
        }

        return $next($request);
    }
}
