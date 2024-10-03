<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [index] precheck processes for product contracts
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Contracts;

use App\Models\Contract;
use Closure;

class Signature {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] contracts
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $this->fronteEnd($contract);

    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd($contract) {

        //public or private (should the form show form include first and last
        if (auth()->check()) {

            //signature names
            config([
                'signining.first_name' => auth()->user()->first_name,
                'signining.last_name' => auth()->user()->last_name,
            ]);



        }

    }

}
