<?php

namespace App\Http\Middleware\Contracts;
use Closure;
use Log;
use App\Repositories\ContractRepository;

class Show {

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
     *   1. validates that the contract exists
     *   2. checks users permissions to [view] the contract
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
            abort(404);
        }

        //frontend
        $this->fronteEnd($contract);

        //team: does user have permission edit contracts
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_contracts >= 1) {
                return $next($request);
            }
        }

        //client: does user have permission edit invoices
        if (auth()->user()->is_client) {
            //404 for invoices in draft status
            if ($contract->doc_status == 'draft') {
                abort(404);
            }
            //ok
            if ($contract->doc_client_id == auth()->user()->clientid) {
                return $next($request);
            }
        }

        //NB: client db/repository (clientid filter merege) is applied in main controller.php

        //permission denied
        Log::error("permission denied", ['process' => '[contracts][show]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd($contract = '') {

        //permissions
        if (auth()->user()->is_team) {
            //editing
            if (auth()->user()->role->role_contracts >= 2) {
                config([
                    'visibility.document_options_buttons' => true,
                    'visibility.document_edit_button' => true,
                ]);
            }
            //deleting
            if (auth()->user()->role->role_contracts >= 3) {
                config([
                    'visibility.delete_document_button' => true,
                ]);
            }
        }

        //signatures - top line
        config(['visibility.signatures_show_top_line' => true]);


        //signatures visibility
        $this->contractrepo->visibilitySignatures($contract, 'show');

    }

}