<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\Spaces\ShowDynamicResponse;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;

class Spaces extends Controller {

    /**
     * The space (project) repository instance.
     */
    protected $projectrepo;

    public function __construct(ProjectRepository $projectrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('spacesMiddlewareShow')->only([
            'showDynamic',
        ]);

        $this->projectrepo = $projectrepo;
    }

    /**
     * Display the specified project
     * @param int $id project id
     * @return \Illuminate\Http\Response
     */
    public function showDynamic() {

        //get the space
        $spaces = $this->projectrepo->search(request('space_id'), ['apply_filters' => false]);
        $space = $spaces->first();

        //set page
        $page = $this->pageSettings('space', $space);

        //get the section (or default to files)
        $sections = (request()->segment(3) != '') ? request()->segment(3) : 'files';
        $section = rtrim($sections, 's');

        $page['dynamic_url'] = url($sections . '?source=ext&' . $section . 'resource_type=project&' . $section . 'resource_id=' . $space->project_id);

        //reponse payload
        $payload = [
            'page' => $page,
            'space' => $space,
        ];

        //response
        return new ShowDynamicResponse($payload);
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
                __('lang.spaces'),
            ],
            'meta_title' => __('lang.spaces'),
            'heading' => __('lang.spaces'),
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'project space', //use 'project' to mantian the css for tabbed pages
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_spaces' => 'active',
        ];

        //space
        if ($section = 'space') {

            //general space
            $page['crumbs'] = [
                __('lang.spaces'),
                $data->project_title,
            ];

            //user space
            if ($data->project_reference == 'default-user-space') {
                $page['crumbs'] = [
                    config('system.settings2_spaces_user_space_title'),
                ];
            }

            //team space
            if ($data->project_reference == 'default-team-space') {
                $page['crumbs'] = [
                    config('system.settings2_spaces_team_space_title'),
                ];
            }
        }

        //return
        return $page;
    }
}