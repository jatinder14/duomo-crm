<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template settings
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Settings;
use App\Http\Controllers\Controller;
use App\Http\Responses\Settings\Files\CreateFolderResponse;
use App\Http\Responses\Settings\Files\DefaultFoldersResponse;
use App\Http\Responses\Settings\Files\DeletefolderResponse;
use App\Http\Responses\Settings\Files\EditFolderResponse;
use App\Http\Responses\Settings\Files\FoldersResponse;
use App\Http\Responses\Settings\Files\GeneralResponse;
use App\Http\Responses\Settings\Files\StoreFolderResponse;
use App\Http\Responses\Settings\Files\UpdateFolderResponse;
use App\Repositories\FileFolderRepository;
use App\Repositories\SettingsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Files extends Controller {

    /**
     * The settings repository instance.
     */
    protected $settingsrepo;

    public function __construct(SettingsRepository $settingsrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //settings general
        $this->middleware('settingsMiddlewareIndex');

        $this->settingsrepo = $settingsrepo;

    }

    /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function showGeneral() {

        $settings = \App\Models\Settings2::find(1);

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('general'),
            'settings' => $settings,
        ];

        //show the view
        return new GeneralResponse($payload);
    }

    /**
     * Update general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function updateGeneral() {

        $settings = \App\Models\Settings2::find(1);

        //validate zip is enabled on the server
        if (request('settings2_file_bulk_download') == 'enabled') {
            if (!class_exists('ZipArchive')) {
                abort(409, __('lang.required_php_extension_mission') . ' - (PHP ZipArchive)');
            }
        }

        //update settings
        $settings->settings2_file_bulk_download = request('settings2_file_bulk_download');
        $settings->save();

        return response()->json(array(
            'notification' => [
                'type' => 'success',
                'value' => __('lang.request_has_been_completed'),
            ],
            'skip_dom_reset' => true,
        ));
    }

    /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function folders() {

        $settings = \App\Models\Settings2::find(1);

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('folders'),
            'settings' => $settings,
        ];

        //show the view
        return new FoldersResponse($payload);
    }

    /**
     * show the form to update a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function updatefolders() {

        //get the item
        $settings = \App\Models\Settings2::find(1);

        //update record
        $settings->settings2_file_folders_status = request('settings2_file_folders_status');
        $settings->settings2_file_folders_manage_assigned = (request('settings2_file_folders_manage_assigned') == 'on') ? 'yes' : 'no';
        $settings->settings2_file_folders_manage_project_manager = (request('settings2_file_folders_manage_project_manager') == 'on') ? 'yes' : 'no';
        $settings->settings2_file_folders_manage_client = (request('settings2_file_folders_manage_client') == 'on') ? 'yes' : 'no';
        $settings->save();

        //extra
        if (request('settings2_file_folders_status') == 'disabled') {
            $settings->settings2_file_folders_manage_assigned = 'no';
            $settings->settings2_file_folders_manage_project_manager = 'no';
            $settings->settings2_file_folders_manage_client = 'no';
            $settings->save();
        }

        return response()->json(array(
            'notification' => [
                'type' => 'success',
                'value' => __('lang.request_has_been_completed'),
            ],
            'skip_dom_reset' => true,
        ));
    }

    /**
     * Show the resource
     * @return blade view | ajax view
     */
    public function defaultFolders(FileFolderRepository $folderrepo) {

        //url resource ([optional] if we are not using middleware)
        request()->merge([
            'filter_filefolder_system' => "yes",
        ]);

        //get the item
        $folders = $folderrepo->search();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('default-folders'),
            'folders' => $folders,
        ];

        //return the reposnse
        return new DefaultFoldersResponse($payload);

    }

    /**
     * show the form to create a new resource
     *
     * @return \Illuminate\Http\Response
     */
    public function createFolder() {

        //reponse payload
        $payload = [];

        //return the reposnse
        return new CreateFolderResponse($payload);

    }

    /**
     * store the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function storeFolder(FileFolderRepository $folderrepo) {

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
                        if (\App\Models\FileFolder::Where('filefolder_name', $value)->Where('filefolder_system', 'yes')->exists()) {
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
        $folder->filefolder_system = 'yes';
        $folder->filefolder_default = 'no';
        $folder->save();

        //get friendly row
        request()->merge([
            'filter_filefolder_system' => 'yes',
        ]);
        $folders = $folderrepo->search();

        //reponse payload
        $payload = [
            'folders' => $folders,
        ];

        //return the reposnse
        return new StoreFolderResponse($payload);

    }

    /**
     * show the form to edit a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function editFolder($id) {

        //validate
        if (!$folder = \App\Models\FileFolder::Where('filefolder_id', $id)->Where('filefolder_system', 'yes')->first()) {
            abort(404);
        }

        //reponse payload
        $payload = [
            'folder' => $folder,
        ];

        //return the reposnse
        return new EditFolderResponse($payload);
    }

    /**
     * uodate the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function updateFolder(FileFolderRepository $folderrepo, $id) {

        //validate
        if (!$folder = \App\Models\FileFolder::Where('filefolder_id', $id)->Where('filefolder_system', 'yes')->first()) {
            abort(404);
        }

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
                        if (\App\Models\FileFolder::Where('filefolder_name', $value)->Where('filefolder_system', 'yes')->exists()) {
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
        $folder->filefolder_name = request('filefolder_name');
        $folder->save();

        //if this the main default folder - apply the change to all default
        if ($folder->filefolder_default == 'yes') {
            \App\Models\FileFolder::where('filefolder_default', 'yes')
                ->update([
                    'filefolder_name' => request('filefolder_name'),
                ]);
        }

        //get friendly row
        request()->merge([
            'filter_filefolder_system' => 'yes',
        ]);
        $folders = $folderrepo->search();

        //reponse payload
        $payload = [
            'folders' => $folders,
        ];

        //return the reposnse
        return new UpdateFolderResponse($payload);

    }

    /**
     * delete the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteFolder($id) {

        //validate
        if (!$folder = \App\Models\FileFolder::Where('filefolder_id', $id)->Where('filefolder_system', 'yes')->first()) {
            abort(404);
        }

        //validate
        if ($folder->filefolder_default == 'yes') {
            abort(409, __('lang.system_default_folder_cannot_be_deleted'));
        }

        //delete record
        $folder->delete();

        //reponse payload
        $payload = [
            'id' => $id,
        ];

        //return the reposnse
        return new DeletefolderResponse($payload);

    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        $page = [
            'crumbs' => [
                __('lang.settings'),
                __('lang.files'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page' => 'settings',
            'meta_title' => ' - ' . __('lang.settings'),
            'heading' => __('lang.settings'),
            'settingsmenu_general' => 'active',
        ];

        //general
        if ($section == 'folders') {
            $page['crumbs'] = [
                __('lang.settings'),
                __('lang.files'),
                __('lang.general_settings'),
            ];
        }

        //folders
        if ($section == 'folders') {
            $page['crumbs'] = [
                __('lang.settings'),
                __('lang.files'),
                __('lang.folders'),
            ];
        }

        //default falders
        if ($section == 'default-folders') {
            $page['crumbs'] = [
                __('lang.settings'),
                __('lang.files'),
                __('lang.default_folders'),
            ];
        }

        return $page;
    }

}
