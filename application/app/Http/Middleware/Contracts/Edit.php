<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [edit] precheck processes for product contracts
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Contracts;
use Closure;
use Log;
use App\Repositories\ContractRepository;

class Edit {

    /**
     * The repository instance.
     */
    protected $contractrepo;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(ContractRepository $contractrepo) {

        //project permissions repo
        $this->contractrepo = $contractrepo;

    }

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

        //contract id
        $doc_id = $request->route('contract');

        //does the contract exist
        if ($doc_id == '' || !$contract = \App\Models\Contract::Where('doc_id', $doc_id)->first()) {
            Log::error("contract could not be found", ['process' => '[permissions][contracts][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'contract id' => $doc_id ?? '']);
            abort(404);
        }

        //frontend
        $this->fronteEnd($contract);

        //permission: does user have permission edit contracts
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_contracts >= 2) {
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][contracts][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd($contract) {

        //permissions
        if (auth()->user()->role->role_contracts >= 2) {
            config([
                'visibility.document_options_buttons' => true,
                'visibility.document_edit_estimate_button' => true,
                'visibility.document_edit_variables_button' => true,
            ]);
        }
        //deleting
        if (auth()->user()->role->role_contracts >= 3) {
            config([
                'visibility.delete_document_button' => true,
            ]);
        }

        //signatures - top line
        config(['visibility.signatures_show_bottom_line' => true]);


        //signatures visibility
        $this->contractrepo->visibilitySignatures($contract, 'edit');

    }
}
