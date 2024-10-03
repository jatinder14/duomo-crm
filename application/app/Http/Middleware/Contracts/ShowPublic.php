<?php

namespace App\Http\Middleware\Contracts;
use Closure;

class ShowPublic {

    /**
     * This middleware does the following
     *   1. validates that the contract exists
     *   2. checks users permissions to [view] the contract
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //validation of url
        if (!$contract = \App\Models\Contract::Where('doc_unique_id', $request->route('contract'))->first()) {
            abort(404);
        }

        return $next($request);
    }


}