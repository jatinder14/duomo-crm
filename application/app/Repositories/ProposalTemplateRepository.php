<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\ProposalTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Log;


class ProposalTemplateRepository{



    /**
     * The template repository instance.
     */
    protected $template;

    /**
     * Inject dependecies
     */
    public function __construct(ProposalTemplate $template) {
        $this->template = $template;
    }


        /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object templates collection
     */
    public function search($id = '') {

        $templates = $this->template->newQuery();

        $templates->selectRaw('*');

        $templates->leftJoin('users', 'users.id', '=', 'proposal_templates.proposal_template_creatorid');


        //filters: id
        if (is_numeric($id)) {
            $templates->where('proposal_template_id', $id);
        }

        $templates->orderBy('proposal_template_id', 'desc');


        // Get the results and return them.
        return $templates->paginate(config('system.settings_system_pagination_limits'));
    }
}