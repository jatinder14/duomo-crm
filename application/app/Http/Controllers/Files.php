<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for files
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\Files\BulkDownloadResponse;
use App\Http\Responses\Files\CopyFilesResponse;
use App\Http\Responses\Files\CreateFolderResponse;
use App\Http\Responses\Files\CreateResponse;
use App\Http\Responses\Files\DeleteFolderResponse;
use App\Http\Responses\Files\DestroyResponse;
use App\Http\Responses\Files\EditFoldersResponse;
use App\Http\Responses\Files\IndexResponse;
use App\Http\Responses\Files\MoveFilesResponse;
use App\Http\Responses\Files\ShowFoldersResponse;
use App\Http\Responses\Files\ShowMoveFilesResponse;
use App\Http\Responses\Files\StoreResponse;
use App\Http\Responses\Files\UpdateResponse;
use App\Http\Responses\Files\EditTagsResponse;
use App\Permissions\FilePermissions;
use App\Permissions\ProjectPermissions;
use App\Repositories\DestroyRepository;
use App\Repositories\EmailerRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventTrackingRepository;
use App\Repositories\FileRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Files extends Controller {

    /**
     * The file repository instance.
     */
    protected $filerepo;

    /**
     * The tags repository instance.
     */
    protected $tagrepo;

    /**
     * The user repository instance.
     */
    protected $userrepo;

    /**
     * The file permission instance.
     */
    protected $filepermissions;

    /**
     * The event repository instance.
     */
    protected $eventrepo;

    /**
     * The event tracking repository instance.
     */
    protected $trackingrepo;

    /**
     * The project permission instance.
     */
    protected $projectpermissions;

    /**
     * The resources that are associated with files (e.g. project | lead | client)
     */
    protected $approved_resources;

    /**
     * The emailer repository
     */
    protected $emailerrepo;

    public function __construct(
        FileRepository $filerepo,
        TagRepository $tagrepo,
        ProjectPermissions $projectpermissions,
        UserRepository $userrepo,
        EventRepository $eventrepo,
        EventTrackingRepository $trackingrepo,
        EmailerRepository $emailerrepo,
        FilePermissions $filepermissions
    ) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //route middleware
        $this->middleware('filesMiddlewareIndex')->only([
            'index',
            'store',
            'renameFile',
            'updateTags',
        ]);

        $this->middleware('filesMiddlewareCreate')->only([
            'create',
            'store',
        ]);

        $this->middleware('filesMiddlewareDownload')->only([
            'download',
        ]);

        $this->middleware('filesMiddlewareDestroy')->only([
            'destroy',
        ]);

        $this->middleware('filesMiddlewareEdit')->only(
            [
                'edit',
                'renameFile',
                'updateTags',
            ]);

        $this->middleware('filesMiddlewareMove')->only([
            'moveFiles',
        ]);

        $this->middleware('filesMiddlewareBulkDownload')->only([
            'bulkDownload',
        ]);

        $this->middleware('manageFoldersMiddleware')->only([
            'createFolder',
            'editFolders',
            'updateFolders',
            'deleteFolder',
            'storeFolder',
        ]);

        $this->middleware('filesMiddlewareCopy')->only([
            'copyAction',
        ]);

        $this->filerepo = $filerepo;
        $this->tagrepo = $tagrepo;
        $this->userrepo = $userrepo;
        $this->filepermissions = $filepermissions;
        $this->projectpermissions = $projectpermissions;
        $this->eventrepo = $eventrepo;
        $this->trackingrepo = $trackingrepo;
        $this->emailerrepo = $emailerrepo;

        //allowable resource_types
        $this->approved_resources = [
            'project',
            'lead',
            'client',
        ];
    }

    /**
     * Display a listing of files
     * @return \Illuminate\Http\Response
     */
    public function index() {

        //default
        $folders = [];

        $files = $this->filerepo->search();

        //apply some permissions
        if ($files) {
            foreach ($files as $file) {
                $this->applyPermissions($file);
            }
        }

        //get all tags (type: lead) - for filter panel
        $tags = $this->tagrepo->getByType('file');

        //mark events as read
        if (request()->filled('fileresource_type') && request()->filled('fileresource_id')) {
            \App\Models\EventTracking::where('resource_id', request('fileresource_id'))
                ->where('resource_type', request('fileresource_type'))
                ->where('eventtracking_userid', auth()->id())
                ->update(['eventtracking_status' => 'read']);
        }

        //get all the folders
        if (request('fileresource_type') == 'project' && request()->filled('fileresource_id')) {
            $folders = \App\Models\FileFolder::Where('filefolder_projectid', request('fileresource_id'))
                ->orderBy('filefolder_default', 'desc')
                ->orderBy('filefolder_name', 'asc')->get();
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('files'),
            'files' => $files,
            'tags' => $tags,
            'folders' => $folders,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Additional settings for external requests
     */
    public function externalRequest() {

        //check we have a file id and type
        if (!is_numeric(request('project_id'))) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }
    }

    /**
     * Show the form for creating a new file.
     * @return \Illuminate\Http\Response
     */
    public function create() {

        //validate the incoming request
        if (!is_numeric(request('resource_id')) || !in_array(request('resource_type'), $this->approved_resources)) {
            //hide the add button
            request()->merge([
                'visibility_list_page_actions_add_button' => false,
            ]);
        }

        //get tags
        $tags = $this->tagrepo->getByType('invoice');

        //payload
        $payload = [
            'tags' => $tags,
        ];

        //show the view
        return new CreateResponse($payload);
    }

    /**
     * Store a newly created file in storage.
     * @return \Illuminate\Http\Response
     */
    public function store() {

        //defaults
        $file_clientid = null;

        //validation
        if (!is_numeric(request('fileresource_id')) || !in_array(request('fileresource_type'), $this->approved_resources)) {
            //error
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //counting rows
        $rows = $this->filerepo->search();
        $count = $rows->total();

        //[save attachments] loop through and save each attachment
        $unique_key = Str::random(50);
        if (request()->filled('attachments')) {
            foreach (request('attachments') as $uniqueid => $file_name) {
                $data = [
                    'file_clientid' => request('file_clientid'),
                    'fileresource_type' => request('fileresource_type'),
                    'fileresource_id' => request('fileresource_id'),
                    'file_directory' => $uniqueid,
                    'file_uniqueid' => $uniqueid,
                    'file_upload_unique_key' => $unique_key,
                    'file_filename' => $file_name,
                ];
                //process and save to db
                $file_id = $this->filerepo->process($data);
                //get file
                $files = $this->filerepo->search($file_id, ['apply_filters' => false]);
                $file = $files->first();

                //add folder id
                if (request()->filled('filter_folderid')) {
                    $file->file_folderid = request('filter_folderid');
                    $file->save();
                }

                //record event (project file)
                if (request('fileresource_type') == 'project' && request('fileresource_id') > 0) {
                    if ($project = \App\Models\Project::Where('project_id', request('fileresource_id'))->first()) {

                        //project folders are disabled. Put file in default folder
                        if (config('system.settings2_file_folders_status') == 'disabled') {
                            if ($default_folder = \App\Models\FileFolder::Where('filefolder_projectid', request('fileresource_id'))
                                ->Where('filefolder_default', 'yes')->first()) {
                                $file->file_folderid = $default_folder->filefolder_id;
                                $file->save();
                            }
                        }

                        /** ----------------------------------------------
                         * record event [status]
                         * ----------------------------------------------*/
                        $data = [
                            'event_creatorid' => auth()->id(),
                            'event_item' => 'file',
                            'event_item_id' => $file_id,
                            'event_item_lang' => 'event_uploaded_a_file',
                            'event_item_content' => $file_name,
                            'event_item_content2' => "files/download?file_id=$uniqueid",
                            'event_parent_type' => 'project',
                            'event_parent_id' => $project->project_id,
                            'event_parent_title' => $project->project_title,
                            'event_show_item' => 'yes',
                            'event_show_in_timeline' => $file->file_visibility_client,
                            'event_clientid' => $project->project_clientid,
                            'eventresource_type' => 'project',
                            'eventresource_id' => $project->project_id,
                            'event_notification_category' => 'notifications_projects_activity',
                            'event_client_visibility' => $file->file_visibility_client,
                        ];
                        //record event
                        if ($event_id = $this->eventrepo->create($data)) {
                            //get users
                            $users = $this->projectpermissions->check('users', $project);
                            //record notification
                            $emailusers = $this->trackingrepo->recordEvent($data, $users, $event_id);
                        }

                        /** ----------------------------------------------
                         * send email [status]
                         * ----------------------------------------------*/
                        if (isset($emailusers) && is_array($emailusers)) {
                            //send to users
                            if ($users = \App\Models\User::WhereIn('id', $emailusers)->get()) {
                                foreach ($users as $user) {
                                    $mail = new \App\Mail\ProjectFileUploaded($user, $file, $project);
                                    $mail->build();
                                }
                            }
                        }

                    }
                }
            }
        }

        //add tags
        $this->tagrepo->add('file', $file_id);

        //get files (only those uploaded now)
        request()->merge([
            'filter_file_upload_unique_key' => $unique_key,
        ]);
        $files = $this->filerepo->search();

        foreach ($files as $file) {
            $this->applyPermissions($file);
        }

        $payload = [
            'page' => $this->pageSettings('files'),
            'files' => $files,
            'count' => $count,
        ];

        //show the view
        return new StoreResponse($payload);
    }

    /**
     * show the form to edit a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        $file = \App\Models\File::Where('file_id', $id)->first();

        $extension = '.' . $file->file_extension;
        $payload['filename'] = str_replace($extension, '', $file->file_filename);

        //page
        $html = view('pages/files/components/actions/rename', compact('payload'))->render();
        $jsondata['dom_html'][] = [
            'selector' => '#actionsModalBody',
            'action' => 'replace',
            'value' => $html,
        ];

        $jsondata['dom_visibility'][] = [
            'selector' => '#actionsModalFooter', 'action' => 'show',
        ];

        //render
        return response()->json($jsondata);

    }

    /**
     * show the form to create a new resource
     *
     * @return \Illuminate\Http\Response
     */
    public function renameFile($id) {

        //get the item
        $file = \App\Models\File::Where('file_id', $id)->first();

        if (!request()->filled('file_filename')) {
            abort(409, __('lang.file_name') . ' - ' . __('lang.is_required'));
        }

        //new filename
        $new_filename = request('file_filename') . '.' . $file->file_extension;

        //rename file
        try {
            $old_file = BASE_DIR . '/storage/files/' . $file->file_directory . '/' . $file->file_filename;
            $new_file = BASE_DIR . '/storage/files/' . $file->file_directory . '/' . $new_filename;
            rename($old_file, $new_file);
        } catch (Exception $e) {
            $message = $e->getMessage();
            $error_code = $e->getCode();
            if ($error_code = 123) {
                abort(409, __('lang.invalid_file_name'));
            }
            abort(409, $message);
        }

        //update db
        $file->file_filename = $new_filename;
        $file->save();

        //get friendly row
        $files = $this->filerepo->search($id);

        //apply some permissions
        if ($files) {
            foreach ($files as $file) {
                $this->applyPermissions($file);
            }
        }

        //payload
        $payload = [
            'files' => $files,
            'file' => $files->first(),
        ];

        //return view
        return new UpdateResponse($payload);

    }

    /**
     * show file image thumbs in tables
     * @return \Illuminate\Http\Response
     */
    public function showImage() {

        $image = '';

        //default image
        $default_image = 'system/images/image-placeholder.jpg';
        if (Storage::exists($default_image)) {
            $image = $default_image;
        }

        //check if file exists in the database

        //get the file from database
        if (request()->filled('file_id')) {
            if ($file = \App\Models\File::Where('file_uniqueid', request('file_id'))->first()) {
                //confirm thumb exists
                if ($file->file_thumbname != '') {
                    $image_path = "files/$file->file_directory/$file->file_thumbname";
                    if (Storage::exists($image_path)) {
                        $image = $image_path;
                    }
                }
            }
        }

        //browser image response
        if ($image != '') {
            $thumb = Storage::get($image);
            $mime = Storage::mimeType($image);
            header('Content-Type: image/gif');
            echo $thumb;
        }
    }

    /**
     * download a file
     * @return \Illuminate\Http\Response
     */
    public function download() {

        //get the file
        $file = \App\Models\File::Where('file_uniqueid', request('file_id'))->first();

        //mark events as read
        \App\Models\EventTracking::where('eventtracking_source_id', $file->file_id)
            ->where('eventtracking_source', 'file')
            ->where('eventtracking_userid', auth()->id())
            ->update(['eventtracking_status' => 'read']);

        //confirm file physaiclly exists
        if ($file->file_filename != '') {
            $file_path = "files/$file->file_directory/$file->file_filename";
            if (Storage::exists($file_path)) {
                return Storage::download($file_path);
            }
        }

        //error item not found
        abort(404, __('lang.file_not_found'));
    }

    /**
     * download a file
     * @return \Illuminate\Http\Response
     */
    public function downloadAttachment() {

        //get the file
        $attachment = \App\Models\Attachment::Where('attachment_uniqiueid', request('attachment_id'))->first();

        //confirm file physaiclly exists
        if ($attachment->attachment_filename != '') {
            $attachment_path = "files/$attachment->attachment_directory/$attachment->attachment_filename";
            if (Storage::exists($attachment_path)) {
                return Storage::download($attachment_path);
            }
        }

        //error item not found
        abort(404, __('lang.file_not_found'));
    }

    /**
     * Update the specified file in storage.
     * @param int $id file id
     * @return \Illuminate\Http\Response
     */
    public function update($id) {

        //update file
        if (!$this->filerepo->update($id)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //update file timeline visibility
        if ($event = \App\Models\Event::Where('event_item', 'file')->Where('event_item_id', $id)->first()) {
            $event->event_show_in_timeline = (request('visible_to_client') == 'on') ? 'yes' : 'no';
            $event->save();
        }

        //success notification - no real need to show a confirmation for this
        //return response()->json(success());
    }

    /**
     * Remove the specified file from storage.
     * @param object DestroyRepository instance of the repository
     * @param int $id file id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRepository $destroyrepo) {

        //delete each record in the array
        $allrows = array();
        foreach (request('ids') as $id => $value) {
            //only checked items
            if ($value == 'on') {

                //get numeric file id
                if (!is_numeric($id)) {
                    if ($file = \App\Models\File::Where('file_uniqueid', $id)->first()) {
                        $id = $file->file_id;
                    }
                }

                //delete file
                if (is_numeric($id)) {
                    $destroyrepo->destroyFile($id);
                    //add to array
                    $allrows[] = $id;
                }
            }
        }
        //reponse payload
        $payload = [
            'allrows' => $allrows,
        ];

        //generate a response
        return new DestroyResponse($payload);
    }

    /**
     * pass the file through the ProjectPermissions class and apply user permissions.
     * @param object $file file model
     * @return object
     */
    private function applyPermissions($file = '') {

        //sanity - make sure this is a valid file object
        if ($file instanceof \App\Models\File) {
            //project files
            if ($file->fileresource_id > 0) {
                $file->permission_delete_file = $this->filepermissions->check('delete', $file);
                $file->permission_edit_file = $this->filepermissions->check('edit', $file);
            }
            //template files
            if ($file->fileresource_id < 0) {
                $file->permission_delete_file = (auth()->user()->role->role_templates_projects >= 2) ? true : false;
                $file->permission_edit_file = (auth()->user()->role->role_templates_projects >= 2) ? true : false;
            }
        }
    }

    /**
     * Show the resource
     * @return blade view | ajax view
     */
    public function createFolder() {

        //return the reposnse
        return new CreateFolderResponse();

    }

    /**
     * Show the resource
     * @return blade view | ajax view
     */
    public function showFolders() {

        $folders = \App\Models\FileFolder::Where('filefolder_projectid', request('fileresource_id'))->orderBy('filefolder_name', 'asc')->get();

        $payload = [
            'folders' => $folders,
        ];

        //return the reposnse
        return new ShowFoldersResponse($payload);

    }

    /**
     * store the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function storeFolder() {

        //custom error messages
        $messages = [
            'filefolder_name.required' => __('lang.folder_name') . ' - ' . __('lang.is_required'),
        ];

        //validate
        $validator = Validator::make(request()->all(), [
            'filefolder_name' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (request()->filled('filefolder_name')) {
                        if (\App\Models\FileFolder::Where('filefolder_name', $value)->Where('filefolder_projectid', request('fileresource_id'))->exists()) {
                            return $fail(__('lang.folder_name') . ' - ' . __('lang.already_exists'));
                        }
                    }
                },
            ],
        ], $messages);

        //errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }
            abort(409, $messages);
        }

        //store record
        $folder = new \App\Models\FileFolder();
        $folder->filefolder_creatorid = auth()->id();
        $folder->filefolder_name = request('filefolder_name');
        $folder->filefolder_projectid = request('fileresource_id');
        $folder->filefolder_system = 'no';
        $folder->filefolder_default = 'no';
        $folder->save();

        //get all folders
        $folders = \App\Models\FileFolder::Where('filefolder_projectid', request('fileresource_id'))->orderBy('filefolder_name', 'asc')->get();

        $payload = [
            'folders' => $folders,
        ];

        //return the reposnse
        return new ShowFoldersResponse($payload);

    }

    /**
     * Show the resource
     * @return blade view | ajax view
     */
    public function editFolders() {

        $folders = \App\Models\FileFolder::Where('filefolder_projectid', request('fileresource_id'))->orderBy('filefolder_name', 'asc')->get();

        $payload = [
            'folders' => $folders,
        ];

        //return the reposnse
        return new EditFoldersResponse($payload);

    }

    /**
     * Update the resource
     * @return blade view | ajax view
     */
    public function updateFolders() {

        if (is_array(request('filefolder_name'))) {
            //validate
            foreach (request('filefolder_name') as $id => $value) {
                if ($value == '') {
                    abort(409, __('lang.fill_in_all_fields'));
                }
            }
            //update
            foreach (request('filefolder_name') as $id => $value) {
                \App\Models\FileFolder::where('filefolder_id', $id)
                    ->update(['filefolder_name' => $value]);
            }
        }

        $folders = \App\Models\FileFolder::Where('filefolder_projectid', request('fileresource_id'))->orderBy('filefolder_name', 'asc')->get();

        $payload = [
            'folders' => $folders,
        ];

        //return the reposnse
        return new ShowFoldersResponse($payload);

    }

    /**
     * Update the resource
     * @return blade view | ajax view
     */
    public function deleteFolder($id) {

        //validate
        if (!$folder = \App\Models\FileFolder::Where('filefolder_projectid', request('fileresource_id'))->Where('filefolder_id', $id)->first()) {
            abort(404);
        }

        //do not delete default
        if ($folder->filefolder_default == 'yes') {
            abort(409, __('lang.system_default_folder_cannot_be_deleted'));
        }

        $folder->delete();

        //delete all files in the folder
        if (request('confirm_hidden_fields') == 'on') {
            \App\Models\File::Where('file_folderid', $id)->delete();
        } else {
            //move files to the default folder
            if ($default_folder = \App\Models\FileFolder::Where('filefolder_projectid', request('fileresource_id'))->where('filefolder_default', 'yes')->first()) {
                \App\Models\File::where('file_folderid', $id)
                    ->update(['file_folderid' => $default_folder->filefolder_id]);
            }
        }

        $payload = [
            'id' => $id,
        ];

        //return the reposnse
        return new DeleteFolderResponse($payload);

    }

    /**
     * Show the resource
     * @return blade view | ajax view
     */
    public function ShowMoveFiles() {

        //validate
        if ($folders = \App\Models\FileFolder::Where('filefolder_projectid', request('fileresource_id'))
            ->WhereNotIn('filefolder_id', [request('filter_folderid')])
            ->get()) {
        }

        //reponse payload
        $payload = [
            'folders' => $folders,
        ];

        //return the reposnse
        return new ShowMoveFilesResponse($payload);
    }

    /**
     * update the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function moveFiles() {

        $files = [];

        //move the files
        if (is_array(request('ids'))) {
            foreach (request('ids') as $unique_id => $value) {
                if ($value == 'on') {
                    if ($file = \App\Models\File::Where('file_uniqueid', $unique_id)->first()) {
                        $file->file_folderid = request('moving_target_folder_id');
                        $file->save();
                        $files[] = $file->file_id;
                    }
                }
            }
        }

        //payload
        $payload = [
            'files' => $files,
        ];

        //return view
        return new MoveFilesResponse($payload);

    }

    /**
     * bulk download files
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkDownload() {

        //validation
        if (!is_array(request('ids'))) {
            abort(409, __('lang.no_files_selected'));
        }

        //make temp folder to store the
        $temp_directory = Str::random(40);
        Storage::makeDirectory("temp/$temp_directory");

        //create zip archive
        $zip = new \ZipArchive();
        $zip_file = BASE_DIR . "/storage/temp/$temp_directory/files.zip";
        if ($zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === false) {
            Log::error("zip archive could not be created", ['process' => '[files][bulk-download]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            abort(409);
        }

        //copy files into temp directory
        foreach (request('ids') as $unique_id => $value) {
            if ($value == 'on') {
                Log::info("foo bar 1");
                if ($file = \App\Models\File::Where('file_uniqueid', $unique_id)->first()) {
                    Log::info("foo bar 2");

                    //file path
                    $file_path = BASE_DIR . "/storage/files/" . $file->file_directory . "/" . $file->file_filename;

                    //if the file exists
                    if (file_exists($file_path)) {
                        Log::info("foo bar 3");

                        //get filename and extension
                        $filename = pathinfo($file_path, PATHINFO_FILENAME);
                        $extension = pathinfo($file_path, PATHINFO_EXTENSION);

                        //check for duplicate file name
                        $target_file_name = $file->file_filename;
                        for ($i = 1; $i < 200; $i++) {
                            Log::info("foo bar 4");

                            if (file_exists(BASE_DIR . "/storage/temp/$temp_directory/$target_file_name")) {
                                Log::info("foo bar 5");
                                $target_file_name = $filename . "($i)." . $extension;
                            } else {
                                break;
                            }
                        }

                        Storage::copy("files/" . $file->file_directory . "/" . $file->file_filename, "temp/$temp_directory/$target_file_name");
                        $zip->addFile(BASE_DIR . "/storage/temp/$temp_directory/$target_file_name", $target_file_name);
                    }
                }
            }
        }

        //payload
        $zip->close();

        //reponse payload
        $payload = [
            'temp_directory' => $temp_directory,
        ];

        //return the reposnse
        return new BulkDownloadResponse($payload);
    }

    /**
     * Show the resource
     * @return blade view | ajax view
     */
    public function copy(ProjectRepository $projectrepo) {

        //get all the users projects
        $projects = $projectrepo->usersProjects(auth()->id());

        //reponse payload
        $payload = [
            'page' => $this->pageSettings(),
            'projects' => $projects,
            'type' => 'show',
        ];

        //return the reposnse
        return new CopyFilesResponse($payload);

    }

    /**
     * Copy files
     * @return blade view | ajax view
     */
    public function copyAction(ProjectRepository $projectrepo) {

        //get all the users projects
        $projects = $projectrepo->usersProjects(auth()->id());

        //check if the tartget project is in users list of projects
        if (!$projects->contains('project_id', request('copy_target_project'))) {
            abort(403);
        }

        //copy selected files
        foreach (request('ids') as $file_uniqueid => $value) {
            if ($value == 'on') {
                if ($project = \App\Models\Project::Where('project_id', request('copy_target_project'))->first()) {
                    if ($file = \App\Models\File::Where('file_uniqueid', $file_uniqueid)->first()) {
                        //unique key
                        $unique_key = Str::random(50);
                        //directory
                        $directory = Str::random(40);
                        //paths
                        $source = BASE_DIR . "/storage/files/" . $file->file_directory;
                        $destination = BASE_DIR . "/storage/files/$directory";
                        //get the defautl files folder
                        $default_folder = $projectrepo->getDefaultFilesFolder($project->project_id);

                        //validate
                        if (is_dir($source)) {
                            //copy the database record
                            $new_file = $file->replicate();
                            $new_file->file_creatorid = auth()->id();
                            $new_file->file_created = now();
                            $new_file->fileresource_type = 'project';
                            $new_file->fileresource_id = request('copy_target_project');
                            $new_file->file_clientid = $project->project_clientid;
                            $new_file->file_uniqueid = $directory;
                            $new_file->file_directory = $directory;
                            $new_file->file_upload_unique_key = $unique_key;
                            $new_file->file_folderid = $default_folder;
                            $new_file->save();
                            //copy folder
                            File::copyDirectory($source, $destination);
                        }
                    }
                }
            }
        }

        //reponse payload
        $payload = [
            'type' => 'save',
        ];

        //return the reposnse
        return new CopyFilesResponse($payload);

    }

    /**
     * edit file tags
     * @param int $id lead id
     * @return \Illuminate\Http\Response
     */
    public function editTags($id) {

        $files = $this->filerepo->search($id);
        $file = $files->first();

        //get tags
        $tags_resource = $this->tagrepo->getByResource('file', $id);
        $tags_system = $this->tagrepo->getByType('file');
        $tags = $tags_resource->merge($tags_system);
        $tags = $tags->unique('tag_title');

        //reponse payload
        $payload = [
            'response' => 'edit',
            'tags' => $tags,
            'current_tags' => $file->tags,
            'file'=> $file
        ];

        //process reponse
        return new EditTagsResponse($payload);
    }


        /**
     * edit file tags
     * @param int $id lead id
     * @return \Illuminate\Http\Response
     */
    public function updateTags($id) {

        $this->tagrepo->delete('file', $id);

        $this->tagrepo->add('file', $id);   
        
        $files = $this->filerepo->search();

        $files = $this->filerepo->search($id);
        $file = $files->first();
        $this->applyPermissions($file);

        //get all tags (type: lead) - for filter panel
        $tags = $this->tagrepo->getByType('file');

        //reponse payload
        $payload = [
            'response' => 'update',
            'files' => $files,
            'tags' => $tags,
            'id' => $id
        ];

        //process reponse
        return new EditTagsResponse($payload);
    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        //common settings
        $page = [
            'crumbs' => [
                __('lang.files'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'files',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_files' => 'active',
            'sidepanel_id' => 'sidepanel-filter-files',
            'dynamic_search_url' => url('files/search?action=search&fileresource_id=' . request('fileresource_id') . '&fileresource_type=' . request('fileresource_type')),
            'add_button_classes' => 'add-edit-file-button',
            'load_more_button_route' => 'files',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => __('lang.add_file'),
            'add_modal_create_url' => url('files/create?fileresource_id=' . request('fileresource_id') . '&fileresource_type=' . request('fileresource_type') . '&filter_folderid=' . request('filter_folderid')),
            'add_modal_action_url' => url('files?fileresource_id=' . request('fileresource_id') . '&fileresource_type=' . request('fileresource_type') . '&filter_folderid=' . request('filter_folderid')),
            'add_modal_action_ajax_class' => 'js-ajax-ux-request',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //files list page
        if ($section == 'files') {
            $page += [
                'meta_title' => __('lang.files'),
                'heading' => __('lang.files'),
                'sidepanel_id' => 'sidepanel-filter-files',
            ];
            if (request('source') == 'ext') {
                $page += [
                    'list_page_actions_size' => 'col-lg-12',
                ];
            }
        }

        //create new resource
        if ($section == 'create') {
            $page += [
                'section' => 'create',
            ];
        }

        //edit new resource
        if ($section == 'edit') {
            $page += [
                'section' => 'edit',
            ];
        }

        //return
        return $page;
    }
}