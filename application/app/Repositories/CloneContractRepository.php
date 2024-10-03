<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for cloning contracts
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

/** --------------------------------------------------------------------------
 * [Clone Invoice Repository]
 * Clone an contract. The new contract is set to 'draft status' by default
 * It can be published as needed
 * @source Nextloop
 *--------------------------------------------------------------------------*/
namespace App\Repositories;

use DB;
use Exception;
use Log;

class CloneContractRepository {

    /**
     * Inject dependecies
     */
    public function __construct() {
    }

    /**
     * Clone an contract
     * @return mixed int|contract
     */
    public function clone ($data = []) {

        //info
        Log::info("cloning contract started", ['process' => '[clone-contract]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);

        //validate information
        if (!$payload = $this->validateData($data)) {
            Log::error("cloning contract failed", ['process' => '[clone-contract]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
            return false;
        }

        //get clean the object for cloning
        $contract = \App\Models\Contract::Where('doc_id', $data['doc_id'])->first();

        //get client
        $client = \App\Models\Client::Where('client_id', $data['doc_client_id'])->first();

        //clone main contract
        $new_contract = $contract->replicate();

        //set new data
        $new_contract->doc_creatorid = auth()->id();
        $new_contract->doc_created = now();
        $new_contract->doc_title = $data['doc_title'];
        $new_contract->doc_date_start = $data['doc_date_start'];
        $new_contract->doc_date_end = $data['doc_date_end'];
        $new_contract->doc_value = $data['doc_value'];
        $new_contract->doc_categoryid = $data['doc_categoryid'];
        $new_contract->doc_client_id = $data['doc_client_id'];
        $new_contract->doc_project_id = $data['doc_project_id'];
        $new_contract->doc_provider_signed_userid = null;
        $new_contract->doc_provider_signed_date = null;
        $new_contract->doc_provider_signed_first_name = null;
        $new_contract->doc_provider_signed_last_name = null;
        $new_contract->doc_provider_signed_signature_directory = null;
        $new_contract->doc_provider_signed_signature_filename = null;
        $new_contract->doc_provider_signed_ip_address = null;
        $new_contract->doc_provider_signed_status = null;
        $new_contract->doc_signed_userid = null;
        $new_contract->doc_signed_date = null;
        $new_contract->doc_signed_first_name = null;
        $new_contract->doc_signed_last_name = null;
        $new_contract->doc_signed_signature_directory = null;
        $new_contract->doc_signed_signature_filename = null;
        $new_contract->doc_signed_status = null;
        $new_contract->doc_signed_ip_address = null;
        $new_contract->doc_fallback_client_first_name = $payload['doc_fallback_client_first_name'];
        $new_contract->doc_fallback_client_last_name = $payload['doc_fallback_client_last_name'];
        $new_contract->doc_fallback_client_email = $payload['doc_fallback_client_email'];
        $new_contract->doc_status = 'draft';
        $new_contract->docresource_type = 'client';
        $new_contract->docresource_id = $data['doc_client_id'];
        $new_contract->save();

        //clone estimate
        if ($estimate = \App\Models\Estimate::Where('bill_estimate_type', 'document')->Where('bill_contractid', $data['doc_id'])->first()) {
            $new_estimate_id = -time();
            $new_estimate = $estimate->replicate();
            $new_estimate->save();

            //get the used auto increment value
            $auto_increment = $new_estimate->bill_estimateid;

            //update estimate
            $new_estimate->bill_date = now();
            $new_estimate->bill_creatorid = auth()->id();
            $new_estimate->bill_status = 'draft';
            $new_estimate->bill_estimateid = $new_estimate_id;
            $new_estimate->bill_contractid = $new_contract->doc_id;
            $new_estimate->bill_estimate_type = 'document';
            $new_estimate->bill_uniqueid = str_unique();
            $new_estimate->save();

            //revert the auto increment value
            try {
                DB::statement('ALTER TABLE estimates AUTO_INCREMENT=' . $auto_increment);
            } catch (Exception $e) {
                //igmore
            }

            //replicate each line item
            if ($lineitems = \App\Models\Lineitem::Where('lineitemresource_type', 'estimate')->Where('lineitemresource_id', $estimate->bill_estimateid)->orderBy('lineitem_position', 'asc')->get()) {
                foreach ($lineitems as $lineitem) {
                    //clone line
                    $new_lineitem = $lineitem->replicate();
                    $new_lineitem->lineitemresource_id = $new_estimate_id;
                    $new_lineitem->lineitemresource_type = 'estimate';
                    $new_lineitem->lineitem_created = now();
                    $new_lineitem->save();
                }
            }

        } else {
            Log::error("cloning contract failed - unable to clone the linked estimate", ['process' => '[clone-contract]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
            $new_contract->delete();
            return false;
        }

        Log::info("cloning contract completed. new contract id (" . $new_contract->doc_id . ")", ['process' => '[clone-contract]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);

        //make changes
        return $new_contract;
    }

    /**
     * show the form to edit a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function validateData($data) {

        $payload = [];

        //keys that muct be present in the data array
        $required_keys = [
            'doc_id',
            'doc_title',
            'doc_date_start',
            'doc_categoryid',
            'doc_project_id',
            'doc_value',
            'doc_client_id',
        ];

        //check if all keys exist
        if (array_diff($required_keys, array_keys($data))) {
            Log::error("The supplied data is not valid", ['process' => '[clone-contract]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
            return false;
        }

        //validate client
        if (\App\Models\Client::Where('client_id', $data['doc_client_id'])->doesntExist()) {
            Log::error("the supplied client id (" . $data['doc_client_id'] . ") could not be found", ['process' => '[clone-contract]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //validate lead
        if (is_numeric($data['doc_project_id'])) {
            if (\App\Models\Project::Where('project_id', $data['doc_project_id'])->doesntExist()) {
                Log::error("the supplied project id (" . $data['doc_project_id'] . ") could not be found", ['process' => '[clone-contract]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                return false;
            }
        }

        //validate client primary user
        if ($user = \App\Models\User::Where('clientid', $data['doc_client_id'])->Where('account_owner', 'yes')->first()) {
            $payload['doc_fallback_client_first_name'] = $user->first_name;
            $payload['doc_fallback_client_last_name'] = $user->last_name;
            $payload['doc_fallback_client_email'] = $user->email;
        } else {
            Log::error("the supplied primary user for the client (" . $data['doc_client_id'] . ") could not be found", ['process' => '[clone-contract]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        return $payload;

    }

}