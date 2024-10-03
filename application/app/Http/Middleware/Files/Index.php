<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [index] precheck processes for files
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Files;
use App\Permissions\LeadPermissions;
use App\Permissions\ProjectPermissions;
use Closure;
use Log;

class Index {

    /**
     * The project permisson repository instance.
     */
    protected $projectpermissons;

    /**
     * The lead permisson repository instance.
     */
    protected $leadpermissons;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(ProjectPermissions $projectpermissons, LeadPermissions $leadpermissons) {

        $this->projectpermissons = $projectpermissons;
        $this->leadpermissons = $leadpermissons;

    }

    /**
     * This middleware does the following:
     *     1. ensures that the files controller is not being accessed directly
     *       - i.e. it must only be accessed from a related resource (e.g. project, lead, etc)
     *     2. the permission for the files controller are derived from the permissions that the user has on the linked resource
     *       - e.g. if the user has permission to view a 'project' then they will have permission to view its files
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //only allow external/embedded requests
        if (request('source') != 'ext' && !request()->ajax()) {
            Log::error("the request was not ajax as expected", ['process' => '[permissions][files][index]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            abort(404);
        }

        //project files permission
        if (request('fileresource_type') == 'project') {

            //do we have a folder specified
            if (config('system.settings2_file_folders_status') == 'enabled') {
                if (is_numeric(request('fileresource_id'))) {
                    if (!request()->filled('filter_folderid')) {
                        //get default folder id and set it
                        if ($folder = \App\Models\FileFolder::Where('filefolder_projectid', request('fileresource_id'))->where('filefolder_default', 'yes')->first()) {
                            request()->merge([
                                'filter_folderid' => $folder->filefolder_id,
                            ]);
                        }
                    }
                }
            } else {
                //unset any folder id that may be set in the url
                request()->merge([
                    'filter_folderid' => null,
                ]);
            }

            //client - limit only visible files
            if (auth()->user()->is_client) {
                //sanity client
                request()->merge([
                    'filter_file_visibility_client' => 'yes',
                    'filter_file_clientid' => auth()->user()->clientid,
                ]);
            }

            //various frontend and visibility settings
            $this->fronteEnd();

            //permission
            if (is_numeric(request('fileresource_id'))) {
                if ($this->projectpermissons->check('view', request('fileresource_id')) || $this->projectpermissons->check('super-user', request('fileresource_id'))) {
                    return $next($request);
                }
            }

            //permission - viewing clients project files, from client page
            if (auth()->user()->is_team) {
                if (is_numeric(request('filter_file_clientid'))) {
                    if (auth()->user()->role->role_clients >= 1) {
                        return $next($request);
                    }
                }
            }
        }

        //client files (not client project files)
        if (request('fileresource_type') == 'client' && request()->filled('fileresource_id')) {

            //various frontend and visibility settings
            $this->fronteEnd();

            //only this client
            request()->merge([
                'filter_file_clientid' => request('fileresource_id'),
            ]);
            if (auth()->user()->is_team) {
                if (auth()->user()->role->role_clients >= 1) {
                    return $next($request);
                }
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][files][index]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    private function fronteEnd() {

        /**
         * shorten resource type and id (for easy appending in blade templates)
         * [usage]
         *   replace the usual url('foo') with urlResource('foo'), in blade templated
         * */
        if (request('fileresource_type') != '' || is_numeric(request('fileresource_id'))) {
            request()->merge([
                'resource_query' => 'ref=list&fileresource_type=' . request('fileresource_type') . '&fileresource_id=' . request('fileresource_id') . '&filter_folderid=' . request('filter_folderid'),
            ]);
        } else {
            request()->merge([
                'resource_query' => 'ref=list',
            ]);
        }

        //defaults
        config([
            'visibility.list_page_actions_search' => true,
            'visibility.action_buttons_download' => (config('system.settings2_file_bulk_download') == 'enabled') ? true : false,
            'visibility.files_col_visibility' => true,
            'visibility.files_col_checkboxes' => true,
        ]);

        //[project]
        if (request('fileresource_type') == 'project') {
            //everyone
            if ($this->projectpermissons->check('files-upload', request('fileresource_id')) || $this->projectpermissons->check('super-user', request('fileresource_id'))) {
                config(['visibility.list_page_actions_add_button' => true]);
                if (config('system.settings2_file_folders_status') == 'enabled') {
                    config(['visibility.action_buttons_move' => true]);
                }
            }

            //delete files
            if ($this->projectpermissons->check('files-bulk-delete', request('fileresource_id'))){
                config([
                    'visibility.action_buttons_bulk_delete' => true,
                ]);
            }

            //client
            if (auth()->user()->is_client) {
                config([
                    'visibility.files_col_visibility' => false,
                    'visibility.action_buttons_edit' => false,
                ]);
            }

            //set image as the cover
            if (auth()->user()->is_team) {
                if ($this->projectpermissons->check('edit', request('fileresource_id'))) {
                    if (config('system.settings_projects_cover_images') == 'enabled') {
                        config([
                            'visibility.set_image_as_project_cover' => true,
                        ]);
                    }
                }
            }

            if ($project = \App\Models\Project::Where('project_id', request('fileresource_id'))->first()) {
                //hide client option for spaces
                if ($project->project_type == 'space') {
                    config([
                        'visibility.files_col_visibility' => false,
                    ]);
                }
            }
        }

        //client files (not client project files)
        if (request('fileresource_type') == 'client' && request()->filled('fileresource_id')) {
            if (auth()->user()->role->role_clients >= 2) {
                config([
                    'visibility.list_page_actions_add_button' => true,
                    'visibility.files_col_visibility' => false,
                    'visibility.action_buttons_edit' => false,
                    'visibility.action_buttons_bulk_delete' => true,
                ]);
            }
        }

        //editing folders
        if (config('system.settings2_file_folders_status') == 'enabled' && request('fileresource_type') == 'project') {
            if (request()->filled('fileresource_id')) {
                config([
                    'visibility.manage_file_folders' => $this->projectpermissons->check('manage-folders', request('fileresource_id')),
                ]);
            }
        }
    }
}