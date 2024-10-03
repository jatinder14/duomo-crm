<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\Search\IndexResponse;
use App\Http\Responses\Search\StartResponse;
use App\Repositories\SearchRepository;

class Search extends Controller {

    protected $searchrepo;
    protected $count;
    protected $current_category;

    public function __construct(SearchRepository $searchrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //route middleware
        $this->middleware('searchMiddlewareIndex')->only([
            'index',
        ]);

        $this->searchrepo = $searchrepo;
        $this->count = 0;

    }
    /**
     * Display a listing of foos
     * @return blade view | ajax view
     */
    public function index() {

        //empty search query
        if (!request()->filled('search_query')) {
            $payload = [];
            //show the view
            return new StartResponse($payload);
        }

        //get results
        $clients = $this->clients();
        $projects = $this->projects();
        $contacts = $this->contacts();
        $tasks = $this->tasks();
        $leads = $this->leads();
        $files = $this->files();
        $attachments = $this->attachments();
        $tickets = $this->tickets();
        $contracts = $this->contracts();
        $proposals = $this->proposals();

        //current collection
        switch (request('search_type')) {
        case 'clients':
            $results = $clients['results'];
            $template = $clients['template'];
            $this->count = $clients['count'];
            break;
        case 'contacts':
            $results = $contacts['results'];
            $template = $contacts['template'];
            $this->count = $contacts['count'];
            break;
        case 'projects':
            $results = $projects['results'];
            $template = $projects['template'];
            $this->count = $projects['count'];
            break;
        case 'tasks':
            $results = $tasks['results'];
            $template = $tasks['template'];
            $this->count = $tasks['count'];
            break;
        case 'leads':
            $results = $leads['results'];
            $template = $leads['template'];
            $this->count = $leads['count'];
            break;
        case 'files':
            $results = $files['results'];
            $template = $files['template'];
            $this->count = $files['count'];
            break;
        case 'attachments':
            $results = $attachments['results'];
            $template = $attachments['template'];
            $this->count = $attachments['count'];
            break;
        case 'tickets':
            $results = $tickets['results'];
            $template = $tickets['template'];
            $this->count = $tickets['count'];
            break;
        case 'contracts':
            $results = $contracts['results'];
            $template = $contracts['template'];
            $this->count = $contracts['count'];
            break;
        case 'proposals':
            $results = $proposals['results'];
            $template = $proposals['template'];
            $this->count = $proposals['count'];
            break;
        default:
            $results = [];
            $template = '';
        }

        //search results
        $payload = [

            //the query
            'search_query' => request('search_query'),

            //results
            'results' => $results,

            //template
            'template' => $template,

            //each category
            'clients' => $clients,
            'projects' => $projects,
            'contacts' => $contacts,
            'contracts' => $contracts,
            'tasks' => $tasks,
            'leads' => $leads,
            'files' => $files,
            'attachments' => $attachments,
            'tickets' => $tickets,
            'proposals' => $proposals,

            //at the end
            'count' => $this->count,
            'current_category' => $this->current_category,
            'search_type' => request('search_type'),
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * search clients
     *
     * @return \Illuminate\Http\Response
     */
    public function clients() {

        //basic defaults
        $data = [
            'state' => false,
            'search_type' => 'category',
            'count' => 0,
            'results' => [],
            'template' => 'pages/search/results/clients',
        ];

        //false state
        if (!config('search.clients')) {
            return $data;
        }

        if (!in_array(request('search_type'), ['all', 'clients'])) {
            //count anyway
            $data['count'] = $this->searchrepo->clients('count');
            //return data
            return $data;
        }

        //set the current search
        $this->current_category = request('search_type');

        //get clients
        $data['results'] = $this->searchrepo->clients();
        $data['count'] = $this->searchrepo->clients('count');
        $data['search_type'] = (request('search_type') == 'clients') ? 'category' : 'all';
        $data['state'] = true;

        //update results count
        $this->count += $data['count'];

        return $data;
    }

    /**
     * search projects
     *
     * @return \Illuminate\Http\Response
     */
    public function projects() {

        //basic defaults
        $data = [
            'state' => false,
            'search_type' => 'category',
            'count' => 0,
            'results' => [],
            'template' => 'pages/search/results/projects',
        ];

        //false state
        if (!config('search.projects')) {
            return $data;
        }

        if (!in_array(request('search_type'), ['all', 'projects'])) {
            //count anyway
            $data['count'] = $this->searchrepo->projects('count');
            //return data
            return $data;
        }

        //set the current search
        $this->current_category = request('search_type');

        //get projects
        $data['results'] = $this->searchrepo->projects();
        $data['count'] = $this->searchrepo->projects('count');
        $data['search_type'] = (request('search_type') == 'projects') ? 'category' : 'all';
        $data['state'] = true;

        //update results count
        $this->count += $data['count'];

        return $data;
    }


        /**
     * search contracts
     *
     * @return \Illuminate\Http\Response
     */
    public function contracts() {

        //basic defaults
        $data = [
            'state' => false,
            'search_type' => 'category',
            'count' => 0,
            'results' => [],
            'template' => 'pages/search/results/contracts',
        ];

        //false state
        if (!config('search.contracts')) {
            return $data;
        }

        if (!in_array(request('search_type'), ['all', 'contracts'])) {
            //count anyway
            $data['count'] = $this->searchrepo->contracts('count');
            //return data
            return $data;
        }

        //set the current search
        $this->current_category = request('search_type');

        //get contracts
        $data['results'] = $this->searchrepo->contracts();
        $data['count'] = $this->searchrepo->contracts('count');
        $data['search_type'] = (request('search_type') == 'contracts') ? 'category' : 'all';
        $data['state'] = true;

        //update results count
        $this->count += $data['count'];

        return $data;
    }


    
        /**
     * search proposals
     *
     * @return \Illuminate\Http\Response
     */
    public function proposals() {

        //basic defaults
        $data = [
            'state' => false,
            'search_type' => 'category',
            'count' => 0,
            'results' => [],
            'template' => 'pages/search/results/proposals',
        ];

        //false state
        if (!config('search.proposals')) {
            return $data;
        }

        if (!in_array(request('search_type'), ['all', 'proposals'])) {
            //count anyway
            $data['count'] = $this->searchrepo->proposals('count');
            //return data
            return $data;
        }

        //set the current search
        $this->current_category = request('search_type');

        //get proposals
        $data['results'] = $this->searchrepo->proposals();
        $data['count'] = $this->searchrepo->proposals('count');
        $data['search_type'] = (request('search_type') == 'proposals') ? 'category' : 'all';
        $data['state'] = true;

        //update results count
        $this->count += $data['count'];

        return $data;
    }


    
        /**
     * search tickets
     *
     * @return \Illuminate\Http\Response
     */
    public function tickets() {

        //basic defaults
        $data = [
            'state' => false,
            'search_type' => 'category',
            'count' => 0,
            'results' => [],
            'template' => 'pages/search/results/tickets',
        ];

        //false state
        if (!config('search.tickets')) {
            return $data;
        }

        if (!in_array(request('search_type'), ['all', 'tickets'])) {
            //count anyway
            $data['count'] = $this->searchrepo->tickets('count');
            //return data
            return $data;
        }

        //set the current search
        $this->current_category = request('search_type');

        //get tickets
        $data['results'] = $this->searchrepo->tickets();
        $data['count'] = $this->searchrepo->tickets('count');
        $data['search_type'] = (request('search_type') == 'tickets') ? 'category' : 'all';
        $data['state'] = true;

        //update results count
        $this->count += $data['count'];

        return $data;
    }


    
        /**
     * search tasks
     *
     * @return \Illuminate\Http\Response
     */
    public function tasks() {

        //basic defaults
        $data = [
            'state' => false,
            'search_type' => 'category',
            'count' => 0,
            'results' => [],
            'template' => 'pages/search/results/tasks',
        ];

        //false state
        if (!config('search.tasks')) {
            return $data;
        }

        if (!in_array(request('search_type'), ['all', 'tasks'])) {
            //count anyway
            $data['count'] = $this->searchrepo->tasks('count');
            //return data
            return $data;
        }

        //set the current search
        $this->current_category = request('search_type');

        //get tasks
        $data['results'] = $this->searchrepo->tasks();
        $data['count'] = $this->searchrepo->tasks('count');
        $data['search_type'] = (request('search_type') == 'tasks') ? 'category' : 'all';
        $data['state'] = true;

        //update results count
        $this->count += $data['count'];

        return $data;
    }


    
        /**
     * search leads
     *
     * @return \Illuminate\Http\Response
     */
    public function leads() {

        //basic defaults
        $data = [
            'state' => false,
            'search_type' => 'category',
            'count' => 0,
            'results' => [],
            'template' => 'pages/search/results/leads',
        ];

        //false state
        if (!config('search.leads')) {
            return $data;
        }

        if (!in_array(request('search_type'), ['all', 'leads'])) {
            //count anyway
            $data['count'] = $this->searchrepo->leads('count');
            //return data
            return $data;
        }

        //set the current search
        $this->current_category = request('search_type');

        //get leads
        $data['results'] = $this->searchrepo->leads();
        $data['count'] = $this->searchrepo->leads('count');
        $data['search_type'] = (request('search_type') == 'leads') ? 'category' : 'all';
        $data['state'] = true;

        //update results count
        $this->count += $data['count'];

        return $data;
    }


    
        /**
     * search files
     *
     * @return \Illuminate\Http\Response
     */
    public function files() {

        //basic defaults
        $data = [
            'state' => false,
            'search_type' => 'category',
            'count' => 0,
            'results' => [],
            'template' => 'pages/search/results/files',
        ];

        //false state
        if (!config('search.files')) {
            return $data;
        }

        if (!in_array(request('search_type'), ['all', 'files'])) {
            //count anyway
            $data['count'] = $this->searchrepo->files('count');
            //return data
            return $data;
        }

        //set the current search
        $this->current_category = request('search_type');

        //get files
        $data['results'] = $this->searchrepo->files();
        $data['count'] = $this->searchrepo->files('count');
        $data['search_type'] = (request('search_type') == 'files') ? 'category' : 'all';
        $data['state'] = true;

        //update results count
        $this->count += $data['count'];

        return $data;
    }


    
        /**
     * search attachments
     *
     * @return \Illuminate\Http\Response
     */
    public function attachments() {

        //basic defaults
        $data = [
            'state' => false,
            'search_type' => 'category',
            'count' => 0,
            'results' => [],
            'template' => 'pages/search/results/attachments',
        ];

        //false state
        if (!config('search.attachments')) {
            return $data;
        }

        if (!in_array(request('search_type'), ['all', 'attachments'])) {
            //count anyway
            $data['count'] = $this->searchrepo->attachments('count');
            //return data
            return $data;
        }

        //set the current search
        $this->current_category = request('search_type');

        //get attachments
        $data['results'] = $this->searchrepo->attachments();
        $data['count'] = $this->searchrepo->attachments('count');
        $data['search_type'] = (request('search_type') == 'attachments') ? 'category' : 'all';
        $data['state'] = true;

        //update results count
        $this->count += $data['count'];

        return $data;
    }


    
        /**
     * search contacts
     *
     * @return \Illuminate\Http\Response
     */
    public function contacts() {

        //basic defaults
        $data = [
            'state' => false,
            'search_type' => 'category',
            'count' => 0,
            'results' => [],
            'template' => 'pages/search/results/contacts',
        ];

        //false state
        if (!config('search.contacts')) {
            return $data;
        }

        if (!in_array(request('search_type'), ['all', 'contacts'])) {
            //count anyway
            $data['count'] = $this->searchrepo->contacts('count');
            //return data
            return $data;
        }

        //set the current search
        $this->current_category = request('search_type');

        //get contacts
        $data['results'] = $this->searchrepo->contacts();
        $data['count'] = $this->searchrepo->contacts('count');
        $data['search_type'] = (request('search_type') == 'contacts') ? 'category' : 'all';
        $data['state'] = true;

        //update results count
        $this->count += $data['count'];

        return $data;
    }
}