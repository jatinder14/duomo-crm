<?php

namespace App\Http\Middleware\Estimates;
use Closure;

class ShowPublic {

    /**
     * This middleware does the following
     *   1. validates that the estimate exists
     *   2. checks users permissions to [view] the estimate
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //validation of url
        if (!$estimate = \App\Models\Estimate::Where('bill_uniqueid', $request->route('estimate'))->first()) {
            abort(404);
        }

        //frontend
        $this->fronteEnd($estimate);

        return $next($request);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd($estimate = '') {

        //footer buttons - header - (generally)
        config([
            'visibility.bill_mode' => 'viewing',
            'visibility.public_bill_viewing' => true,
        ]);

    }

}