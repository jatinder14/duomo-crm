<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for summary reports
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories\Reports;

use App\Models\Project;

class SummaryRepository {

    /**
     * The project repository instance.
     */
    protected $project;

    /**
     * Inject dependecies
     */
    public function __construct(Project $project) {
        $this->project = $project;
    }


    /**
     * stats for projects
     * @param $data array
     * @return \Illuminate\Http\Response
     */
    public function projectsChart($data = []) {

    }

}