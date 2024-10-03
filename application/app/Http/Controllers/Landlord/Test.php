<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Log;

// use Illuminate\Validation\Rule;
// use Validator;

class Test extends Controller {

    public function __construct() {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

    }

    /**
     * @return blade view | ajax view
     * - position 11 | 21 | 31 | 41 | 51 | 61 | 71
     * - datatype text date paragraph checkbox dropdown number decimal
     * - limit 10
     * - task client project
     *
     */
    public function index() {

        //get the customer from landlord db
        $customers = \Spatie\Multitenancy\Models\Tenant::get();

        foreach ($customers as $customer) {

            \Spatie\Multitenancy\Models\Tenant::forgetCurrent();

            try {
                //swicth to this tenants DB
                $customer->makeCurrent();

                $default_folder = \App\Models\FileFolder::On('tenant')->Where('filefolder_id', 1)->first();

                //get all the customers projects
                $projects = \App\Models\Project::On('tenant')->get();

                foreach ($projects as $project) {

                    //create a new default folder
                    $folder = new \App\Models\FileFolder();
                    $folder->setConnection('tenant');
                    $folder->filefolder_name = $default_folder->filefolder_name;
                    $folder->filefolder_creatorid = 0;
                    $folder->filefolder_projectid = $project->project_id;
                    $folder->filefolder_default = 'yes';
                    $folder->filefolder_system = 'no';
                    $folder->save();

                    //update all project files
                    \App\Models\File::On('tenant')->where('fileresource_type', 'project')
                        ->where('fileresource_id', $project->project_id)
                        ->update([
                            'file_folderid' => $folder->filefolder_id,
                        ]);
                }

            } catch (Exception $e) {
                abort(409, $e->getMessage());
            }

        }

    }

}