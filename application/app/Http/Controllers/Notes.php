<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for notes
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\Notes\CreateResponse;
use App\Http\Responses\Notes\DestroyResponse;
use App\Http\Responses\Notes\EditResponse;
use App\Http\Responses\Notes\IndexResponse;
use App\Http\Responses\Notes\ShowResponse;
use App\Http\Responses\Notes\StoreResponse;
use App\Http\Responses\Notes\UpdateResponse;
use App\Permissions\NotePermissions;
use App\Repositories\AttachmentRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\NoteRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use App\Rules\NoTags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class Notes extends Controller {

    /**
     * repository instance.
     */
    protected $noterepo;
    protected $tagrepo;
    protected $userrepo;
    protected $notepermissions;
    protected $attachmentrepo;

    public function __construct(
        NoteRepository $noterepo,
        TagRepository $tagrepo,
        UserRepository $userrepo,
        AttachmentRepository $attachmentrepo,
        NotePermissions $notepermissions) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('notesMiddlewareIndex')->only([
            'index',
            'store',
        ]);

        $this->middleware('notesMiddlewareCreate')->only([
            'create',
            'store',
        ]);

        $this->middleware('notesMiddlewareShow')->only([
            'show',
        ]);

        $this->middleware('notesMiddlewareEdit')->only([
            'edit',
            'update',
        ]);

        $this->middleware('notesMiddlewareDestroy')->only([
            'destroy',
        ]);

        $this->noterepo = $noterepo;

        $this->tagrepo = $tagrepo;

        $this->userrepo = $userrepo;

        $this->notepermissions = $notepermissions;

        $this->attachmentrepo = $attachmentrepo;

    }

    /**
     * Display a listing of notes
     * @param object CategoryRepository instance of the repository
     * @return blade view | ajax view
     */
    public function index(CategoryRepository $categoryrepo) {

        //default to user notes if type is not set
        if (request('noteresource_type')) {
            $page = $this->pageSettings('mynotes');
        } else {
            $page = $this->pageSettings('notes');
        }

        //get notes
        $notes = $this->noterepo->search();

        //apply some permissions
        if ($notes) {
            foreach ($notes as $note) {
                $this->applyPermissions($note);
            }
        }

        //reponse payload
        $payload = [
            'page' => $page,
            'notes' => $notes,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new  note
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function create(CategoryRepository $categoryrepo) {

        //get tags
        $tags = $this->tagrepo->getByType('notes');

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('create'),
            'tags' => $tags,
        ];

        //show the form
        return new CreateResponse($payload);
    }

    /**
     * Store a newly created note in storage.
     * @return \Illuminate\Http\Response
     */
    public function store() {

        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'note_title' => [
                'required',
                new NoTags,
            ],
            'note_description' => 'required',
            'tags' => [
                'bail',
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    foreach ($value as $key => $data) {
                        if (hasHTML($data)) {
                            return $fail(__('lang.tags_no_html'));
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

        //create the note
        if (!$note_id = $this->noterepo->create()) {
            abort(409);
        }

        //add tags
        $this->tagrepo->add('note', $note_id);

        //[save attachments] loop through and save each attachment
        if (request()->filled('attachments')) {
            foreach (request('attachments') as $uniqueid => $file_name) {
                $data = [
                    'attachment_clientid' => null,
                    'attachmentresource_type' => 'note',
                    'attachmentresource_id' => $note_id,
                    'attachment_directory' => $uniqueid,
                    'attachment_uniqiueid' => $uniqueid,
                    'attachment_filename' => $file_name,
                ];
                //process and save to db
                $this->attachmentrepo->process($data);
            }
        }

        //get the note object (friendly for rendering in blade template)
        $notes = $this->noterepo->search($note_id);

        //permissions
        $this->applyPermissions($notes->first());

        //counting rows
        $rows = $this->noterepo->search();
        $count = $rows->total();

        //reponse payload
        $payload = [
            'notes' => $notes,
            'count' => $count,
        ];

        //process reponse
        return new StoreResponse($payload);

    }

    /**
     * display a note via ajax modal
     * @param int $id note id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        //get the note
        $note = $this->noterepo->search($id);

        //get attachment
        $attachments = \App\Models\Attachment::Where('attachmentresource_id', $id)
            ->Where('attachmentresource_type', 'note')
            ->get();

        //note not found
        if (!$note = $note->first()) {
            abort(409, __('lang.note_not_found'));
        }

        //reponse payload
        $payload = [
            'note' => $note,
            'attachments' => $attachments,
        ];

        //process reponse
        return new ShowResponse($payload);
    }

    /**
     * Show the form for editing the specified  note
     * @param int $id note id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        //get the note
        $note = $this->noterepo->search($id);

        //get attachment
        $attachments = \App\Models\Attachment::Where('attachmentresource_id', $id)
            ->Where('attachmentresource_type', 'note')
            ->get();

        //get tags
        $tags = $this->tagrepo->getByResource('note', $id);

        //note not found
        if (!$note = $note->first()) {
            abort(409, __('lang.note_not_found'));
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
            'note' => $note,
            'tags' => $tags,
            'attachments' => $attachments,
        ];

        //response
        return new EditResponse($payload);
    }

    /**
     * Update the specified note in storage.
     * @param int $id note id
     * @return \Illuminate\Http\Response
     */
    public function update($id) {

        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'note_title' => [
                'required',
                new NoTags,
            ],
            'note_description' => 'required',
            'tags' => [
                'bail',
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    foreach ($value as $key => $data) {
                        if (hasHTML($data)) {
                            return $fail(__('lang.tags_no_html'));
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

        //update
        if (!$this->noterepo->update($id)) {
            abort(409);
        }

        //[save attachments] loop through and save each attachment
        if (request()->filled('attachments')) {
            foreach (request('attachments') as $uniqueid => $file_name) {
                $data = [
                    'attachment_clientid' => null,
                    'attachmentresource_type' => 'note',
                    'attachmentresource_id' => $id,
                    'attachment_directory' => $uniqueid,
                    'attachment_uniqiueid' => $uniqueid,
                    'attachment_filename' => $file_name,
                ];
                //process and save to db
                $this->attachmentrepo->process($data);
            }
        }

        //delete & update tags
        $this->tagrepo->delete('note', $id);
        $this->tagrepo->add('note', $id);

        //get note
        $notes = $this->noterepo->search($id);

        $this->applyPermissions($notes->first());

        //reponse payload
        $payload = [
            'notes' => $notes,
        ];

        //generate a response
        return new UpdateResponse($payload);
    }

    /**
     * Remove the specified note from storage.
     * @param int $id note id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        $note = \App\Models\Note::Where('note_id', $id)->first();

        //remove the item
        $note->delete();

        //reponse payload
        $payload = [
            'note_id' => $id,
        ];

        //generate a response
        return new DestroyResponse($payload);
    }

    /**
     * download an expense attachment
     * @param int $id expense id
     * @return \Illuminate\Http\Response
     */
    public function downloadAttachment() {

        //check if file exists in the database
        $attachment = \App\Models\Attachment::Where('attachment_uniqiueid', request()->route('uniqueid'))->first();

        //confirm thumb exists
        if ($attachment->attachment_filename != '') {
            $file_path = "files/$attachment->attachment_directory/$attachment->attachment_filename";
            if (Storage::exists($file_path)) {
                return Storage::download($file_path);
            }
        }
        abort(404);
    }

    /**
     * download an expense attachment
     * @param int $id expense id
     * @return \Illuminate\Http\Response
     */
    public function deleteAttachment() {

        //check if file exists in the database
        $attachment = \App\Models\Attachment::Where('attachment_uniqiueid', request()->route('uniqueid'))->first();

        //confirm thumb exists
        if ($attachment->attachment_directory != '') {
            if (Storage::exists("files/$attachment->attachment_directory")) {
                Storage::deleteDirectory("files/$attachment->attachment_directory");
            }
        }

        $attachment->delete();

        //hide and remove row
        $jsondata['dom_visibility'][] = array(
            'selector' => '#note_attachment_' . $attachment->attachment_id,
            'action' => 'slideup-slow-remove',
        );

        //response
        return response()->json($jsondata);
    }

    /**
     * pass the file through the ProjectPermissions class and apply user permissions.
     * @param object note instance of the note model object
     * @return object
     */
    private function applyPermissions($note = '') {

        //sanity - make sure this is a valid file object
        if ($note instanceof \App\Models\Note) {
            //delete permissions
            $note->permission_edit_delete_note = $this->notepermissions->check('edit-delete', $note);
        }
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
                __('lang.notes'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'notes',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_notes' => 'active',
            'sidepanel_id' => 'sidepanel-filter-notes',
            'dynamic_search_url' => url('notes/search?action=search&noteresource_id=' . request('noteresource_id') . '&noteresource_type=' . request('noteresource_type')),
            'add_button_classes' => 'add-edit-note-button',
            'load_more_button_route' => 'notes',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => __('lang.add_note'),
            'add_modal_create_url' => url('notes/create?noteresource_id=' . request('noteresource_id') . '&noteresource_type=' . request('noteresource_type')),
            'add_modal_action_url' => url('notes?noteresource_id=' . request('noteresource_id') . '&noteresource_type=' . request('noteresource_type')),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //notes list page
        if ($section == 'notes') {
            $page += [
                'meta_title' => __('lang.notes'),
                'heading' => __('lang.notes-'),
            ];
            if (request('source') == 'ext') {
                $page += [
                    'list_page_actions_size' => 'col-lg-12',
                ];
            }
            return $page;
        }

        //notes list my notes
        if ($section == 'mynotes') {
            $page += [
                'meta_title' => __('lang.ny_notes'),
                'heading' => __('lang.ny_notes'),
            ];
            if (request('source') == 'ext') {
                $page += [
                    'list_page_actions_size' => 'col-lg-12',
                ];
            }
            return $page;
        }

        //note page
        if ($section == 'note') {
            //adjust
            $page['page'] = 'note';
            //add
            $page += [
                'crumbs' => [
                    __('lang.notes'),
                ],
                'meta_title' => __('lang.notes'),
                'note_id' => request()->segment(2),
                'section' => 'overview',
            ];
            //ajax loading and tabs
            $page += $this->setActiveTab(request()->segment(3));
            return $page;
        }

        //create new resource
        if ($section == 'create') {
            $page += [
                'section' => 'create',
            ];
            return $page;
        }

        //edit new resource
        if ($section == 'edit') {
            $page += [
                'section' => 'edit',
            ];
            return $page;
        }

        //return
        return $page;
    }

}