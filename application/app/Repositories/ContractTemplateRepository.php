<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\ContractTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Log;


class ContractTemplateRepository{



    /**
     * The template repository instance.
     */
    protected $template;

    /**
     * Inject dependecies
     */
    public function __construct(ContractTemplate $template) {
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

        $templates->leftJoin('users', 'users.id', '=', 'contract_templates.contract_template_creatorid');


        //filters: id
        if (is_numeric($id)) {
            $templates->where('contract_template_id', $id);
        }

        $templates->orderBy('contract_template_id', 'desc');


        // Get the results and return them.
        return $templates->paginate(config('system.settings_system_pagination_limits'));
    }
}