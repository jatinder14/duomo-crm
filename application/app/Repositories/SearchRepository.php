<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Attachment;
use App\Models\Client;
use App\Models\Contract;
use App\Models\File;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class SearchRepository {

    /**
     * The repository instance.
     */
    protected $client;
    protected $user;
    protected $project;
    protected $contract;
    protected $proposal;
    protected $task;
    protected $lead;
    protected $file;
    protected $attachment;
    protected $ticket;

    /**
     * Inject dependecies
     */
    public function __construct(
        Client $client,
        User $user,
        Project $project,
        Contract $contract,
        Proposal $proposal,
        Ticket $ticket,
        File $file,
        Attachment $attachment,
        Task $task,
        Lead $lead) {

        $this->client = $client;
        $this->user = $user;
        $this->project = $project;
        $this->contract = $contract;
        $this->proposal = $proposal;
        $this->file = $file;
        $this->ticket = $ticket;
        $this->attachment = $attachment;
        $this->task = $task;
        $this->lead = $lead;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object fooos collection
     */
    public function clients($type = '') {

        $clients = $this->client->newQuery();

        // all client fields
        $clients->selectRaw('*');

        //default where
        $clients->whereRaw("1 = 1");

        //search: various client columns and relationships (where first, then wherehas)
        $clients->where(function ($query) {
            $query->orWhere('client_company_name', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhereHas('tags', function ($q) {
                $q->where('tag_title', request('search_query'));
            });
        });

        //sorting
        $clients->orderBy('client_company_name', 'asc');

        //eager load
        $clients->with([
            'tags',
        ]);

        //cloun
        if ($type == 'count') {
            return $clients->count();
        }

        // Get the results and return them.
        return $clients->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object fooos collection
     */
    public function projects($type = '') {

        $projects = $this->project->newQuery();

        // all project fields
        $projects->selectRaw('*');

        //default where
        $projects->whereRaw("1 = 1");
        $projects->where('project_type', 'project');

        //search: various project columns and relationships (where first, then wherehas)
        $projects->where(function ($query) {
            $query->orWhere('project_title', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhere('project_description', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhereHas('tags', function ($q) {
                $q->where('tag_title', request('search_query'));
            });
        });

        //[permnissions] - skip for admin
        if (auth()->user()->role->role_id != 1) {
            if (auth()->user()->role->role_projects_scope == 'own') {
                //project I am assigned or manage
                $projects->where(function ($query) {
                    $query->whereHas('assigned', function ($q) {
                        $q->whereIn('projectsassigned_userid', [auth()->id()]);
                    });
                    $query->orWhereHas('managers', function ($q) {
                        $q->whereIn('projectsmanager_userid', [auth()->id()]);
                    });
                });
            }
        }

        //sorting
        $projects->orderBy('project_title', 'asc');

        //eager load
        $projects->with([
            'tags',
            'assigned',
            'managers',
        ]);

        //cloun
        if ($type == 'count') {
            return $projects->count();
        }

        // Get the results and return them.
        return $projects->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object contacts collection
     */
    public function contacts($type = '') {

        $contacts = $this->user->newQuery();
        $contacts->leftJoin('clients', 'clients.client_id', '=', 'users.clientid');

        // all contact fields
        $contacts->selectRaw('*');

        //default where
        $contacts->whereRaw("1 = 1");

        $contacts->where('type', 'client');

        //search: various contact columns and relationships (where first, then wherehas)
        $contacts->where(function ($query) {
            $query->orWhere('first_name', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhere('last_name', 'LIKE', '%' . request('search_query') . '%');
        });

        //sorting
        $contacts->orderBy('first_name', 'asc');

        //cloun
        if ($type == 'count') {
            return $contacts->count();
        }

        // Get the results and return them.
        return $contacts->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object contracts collection
     */
    public function contracts($type = '') {

        $contracts = $this->contract->newQuery();
        $contracts->leftJoin('projects', 'projects.project_id', '=', 'contracts.doc_project_id');

        // all contract fields
        $contracts->selectRaw('*');

        //default where
        $contracts->whereRaw("1 = 1");

        //search: various contract columns and relationships (where first, then wherehas)
        $contracts->where(function ($query) {
            $query->orWhere('doc_title', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhere('doc_body', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhereHas('tags', function ($q) {
                $q->where('tag_title', request('search_query'));
            });
        });

        //sorting
        $contracts->orderBy('doc_title', 'asc');

        //eager load
        $contracts->with([
            'tags',
        ]);

        //cloun
        if ($type == 'count') {
            return $contracts->count();
        }

        // Get the results and return them.
        return $contracts->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object tasks collection
     */
    public function tasks($type = '') {

        $tasks = $this->task->newQuery();
        $tasks->leftJoin('projects', 'projects.project_id', '=', 'tasks.task_projectid');

        // all task fields
        $tasks->selectRaw('*');

        //default where
        $tasks->whereRaw("1 = 1");

        //search: various task columns and relationships (where first, then wherehas)
        $tasks->where(function ($query) {
            $query->orWhere('task_title', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhere('task_description', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhereHas('tags', function ($q) {
                $q->where('tag_title', request('search_query'));
            });
        });

        //[permnissions] - skip for admin
        if (auth()->user()->role->role_id != 1) {
            if (auth()->user()->role->role_tasks_scope == 'own') {
                //tasks I am assigned or for projects that I manage
                $tasks->where(function ($query) {
                    $query->whereHas('assigned', function ($q) {
                        $q->whereIn('tasksassigned_userid', [auth()->id()]);
                    });
                    $query->orWhereHas('projectmanagers', function ($q) {
                        $q->whereIn('projectsmanager_userid', [auth()->id()]);
                    });
                });
            }
        }

        //sorting
        $tasks->orderBy('task_title', 'asc');

        //eager load
        $tasks->with([
            'tags',
            'assigned',
            'projectmanagers',
        ]);

        //cloun
        if ($type == 'count') {
            return $tasks->count();
        }

        // Get the results and return them.
        return $tasks->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object leads collection
     */
    public function leads($type = '') {

        $leads = $this->lead->newQuery();

        // all lead fields
        $leads->selectRaw('*');

        //default where
        $leads->whereRaw("1 = 1");

        //search: various lead columns and relationships (where first, then wherehas)
        $leads->where(function ($query) {
            $query->orWhere('lead_title', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhere('lead_description', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhereHas('tags', function ($q) {
                $q->where('tag_title', request('search_query'));
            });
        });

        //[permnissions] - skip for admin
        if (auth()->user()->role->role_id != 1) {
            if (auth()->user()->role->role_leads_scope == 'own') {
                //tasks I am assigned or for projects that I manage
                $leads->whereHas('assigned', function ($query) {
                    $query->whereIn('leadsassigned_userid', [auth()->id()]);
                });
            }
        }

        //sorting
        $leads->orderBy('lead_title', 'asc');

        //eager load
        $leads->with([
            'tags',
            'assigned',
        ]);

        //cloun
        if ($type == 'count') {
            return $leads->count();
        }

        // Get the results and return them.
        return $leads->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object files collection
     */
    public function files($type = '') {

        $files = $this->file->newQuery();
        $files->leftJoin('projects', 'projects.project_id', '=', 'files.fileresource_id');
        $files->leftJoin('clients', 'clients.client_id', '=', 'files.fileresource_id');

        // all file fields
        $files->selectRaw('*');

        //default where
        $files->whereRaw("1 = 1");

        //search: various file columns and relationships (where first, then wherehas)
        $files->where(function ($query) {
            $query->orWhere('file_filename', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhereHas('tags', function ($q) {
                $q->where('tag_title', request('search_query'));
            });
        });

        //[permnissions] - skip for admin
        if (auth()->user()->role->role_id != 1) {
            //from from projects I am assigned or manage
            $files->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('fileresource_type', 'project');
                    $q->whereIn('fileresource_id', request('my_projects'));
                });
                //clients files - if I have permission
                if (auth()->user()->role->role_clients >= 1) {
                    $query->orWhere('fileresource_type', 'client');
                }
            });
        }

        //sorting
        $files->orderBy('file_filename', 'asc');

        //eager load
        $files->with([
            'tags',
        ]);

        //cloun
        if ($type == 'count') {
            return $files->count();
        }

        // Get the results and return them.
        return $files->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object attachments collection
     */
    public function attachments($type = '') {

        $attachments = $this->attachment->newQuery();
        $attachments->leftJoin('tasks', 'attachments.attachmentresource_id', '=', 'tasks.task_id');
        $attachments->leftJoin('leads', 'attachments.attachmentresource_id', '=', 'leads.lead_id');

        // all attachment fields
        $attachments->selectRaw('*');

        //default where
        $attachments->whereRaw("1 = 1");

        //search: various attachment columns and relationships (where first, then wherehas)
        $attachments->where(function ($query) {
            $query->orWhere('attachment_filename', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhereHas('tags', function ($q) {
                $q->where('tag_title', request('search_query'));
            });
        });

        //[permnissions] - skip for admin
        if (auth()->user()->role->role_id != 1) {
            //attachments on tasks or leads that I am assigned or manage
            $attachments->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('attachmentresource_type', 'task');
                    $q->whereIn('attachmentresource_id', request('my_tasks'));
                });
                $query->orWhere(function ($q) {
                    $q->where('attachmentresource_type', 'lead');
                    $q->whereIn('attachmentresource_id', request('my_leads'));
                });
            });
        }

        //sorting
        $attachments->orderBy('attachment_filename', 'asc');

        //eager load
        $attachments->with([
            'tags',
        ]);

        //cloun
        if ($type == 'count') {
            return $attachments->count();
        }

        // Get the results and return them.
        return $attachments->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object tickets collection
     */
    public function tickets($type = '') {

        $tickets = $this->ticket->newQuery();
        $tickets->leftJoin('clients', 'clients.client_id', '=', 'tickets.ticket_clientid');

        // all ticket fields
        $tickets->selectRaw('*');

        //default where
        $tickets->whereRaw("1 = 1");

        //search: various ticket columns and relationships (where first, then wherehas)
        $tickets->where(function ($query) {
            $query->orWhere('ticket_subject', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhere('ticket_message', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhereHas('tags', function ($q) {
                $q->where('tag_title', request('search_query'));
            });
        });

        //sorting
        $tickets->orderBy('ticket_subject', 'asc');

        //eager load
        $tickets->with([
            'tags',
        ]);

        //cloun
        if ($type == 'count') {
            return $tickets->count();
        }

        // Get the results and return them.
        return $tickets->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object proposals collection
     */
    public function proposals($type = '') {

        $proposals = $this->proposal->newQuery();

        // all proposal fields
        $proposals->selectRaw('*');

        //default where
        $proposals->whereRaw("1 = 1");

        //search: various proposal columns and relationships (where first, then wherehas)
        $proposals->where(function ($query) {
            $query->orWhere('doc_title', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhere('doc_body', 'LIKE', '%' . request('search_query') . '%');
            $query->orWhereHas('tags', function ($q) {
                $q->where('tag_title', request('search_query'));
            });
        });

        //sorting
        $proposals->orderBy('doc_title', 'asc');

        //eager load
        $proposals->with([
            'tags',
        ]);

        //cloun
        if ($type == 'count') {
            return $proposals->count();
        }

        // Get the results and return them.
        return $proposals->paginate(config('system.settings_system_pagination_limits'));
    }

}