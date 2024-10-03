<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the projects
 * controller
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Home;
use Illuminate\Contracts\Support\Responsable;

class HomeResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for team members
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        $payload = $this->payload;

        //get users homepage
        $role_homepage = auth()->user()->role->role_homepage;

        switch ($role_homepage) {
        case 'dashboard':
            //show dashboard
            return view('pages/home/home', compact('page', 'payload'));
        default:
            return redirect("/$role_homepage");
        }

    }

}