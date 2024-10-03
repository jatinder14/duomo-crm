<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for cloning proposals
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

/** --------------------------------------------------------------------------
 * [Clone Invoice Repository]
 * Clone an proposal. The new proposal is set to 'draft status' by default
 * It can be published as needed
 * @source Nextloop
 *--------------------------------------------------------------------------*/
namespace App\Repositories;

use Log;
use Exception;
use DB;

class CloneProposalRepository {

    /**
     * Inject dependecies
     */
    public function __construct() {
    }

    /**
     * Clone an proposal
     * @return mixed int|proposal
     */
    public function clone ($data = []) {

        //info
        Log::info("cloning proposal started", ['process' => '[clone-proposal]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);

        //validate information
        if (!$payload = $this->validateData($data)) {
            Log::error("cloning proposal failed", ['process' => '[clone-proposal]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
            return false;
        }

        //get clean the object for cloning
        $proposal = \App\Models\Proposal::Where('doc_id', $data['doc_id'])->first();

        //clone main invoice
        $new_proposal = $proposal->replicate();

        //set new data
        $new_proposal->doc_creatorid = auth()->id();
        $new_proposal->doc_created = now();
        $new_proposal->doc_title = $data['doc_title'];
        $new_proposal->docresource_type = $data['docresource_type'];
        $new_proposal->docresource_id = $data['docresource_id'];
        $new_proposal->doc_date_start = $data['doc_date_start'];
        $new_proposal->doc_date_end = $data['doc_date_end'];
        $new_proposal->doc_categoryid = $data['doc_categoryid'];
        $new_proposal->doc_date_published = null;
        $new_proposal->doc_date_last_emailed = null;
        $new_proposal->doc_notes = null;
        $new_proposal->doc_viewed = 'no';
        $new_proposal->doc_signed_date = null;
        $new_proposal->doc_signed_first_name = null;
        $new_proposal->doc_signed_last_name = null;
        $new_proposal->doc_signed_signature_directory = null;
        $new_proposal->doc_signed_signature_filename = null;
        $new_proposal->doc_signed_ip_address = null;
        $new_proposal->doc_fallback_client_first_name = $payload['doc_fallback_client_first_name'];
        $new_proposal->doc_fallback_client_last_name = $payload['doc_fallback_client_last_name'];
        $new_proposal->doc_fallback_client_email = $payload['doc_fallback_client_email'];
        $new_proposal->doc_status = 'draft';
        $new_proposal->doc_date_status_change = null;
        $new_proposal->save();

        //clone estimate
        if (!$estimate = \App\Models\Estimate::Where('bill_proposalid', $proposal->doc_id)->first()) {
            Log::error("cloning proposal failed - unable to find matching estimate", ['process' => '[clone-proposal]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
            return false;
        }

        //clone main estimate
        $bill_estimateid = -time();
        $new_estimate = $estimate->replicate();
        $new_estimate->save();

        //get the used auto increment value
        $auto_increment = $new_estimate->bill_estimateid;

        $new_estimate->bill_estimateid = -time();
        $new_estimate->bill_creatorid = auth()->id();
        $new_estimate->bill_date = now();
        $new_estimate->bill_status = 'draft';
        $new_estimate->bill_proposalid = $new_proposal->doc_id;
        $new_estimate->bill_estimate_type = 'document';
        $new_estimate->bill_uniqueid = str_unique();
        $new_estimate->save();

        //revert the auto increment value
        try {
            DB::statement('ALTER TABLE estimates AUTO_INCREMENT=' . $auto_increment);
        } catch (Exception $e) {
            //igmore
        }

        //clone line items
        if ($lineitems = \App\Models\Lineitem::Where('lineitemresource_id', $estimate->bill_estimateid)->Where('lineitemresource_type', 'estimate')->orderBy('lineitem_position', 'asc')->get()) {
            foreach ($lineitems as $lineitem) {
                $new_lineitem = $lineitem->replicate();
                $new_lineitem->lineitem_created = now();
                $new_lineitem->lineitemresource_id = $bill_estimateid;
                $new_lineitem->lineitemresource_type = 'estimate';
                $new_lineitem->save();
            }
        }

        Log::info("cloning proposal completed. new proposal id (" . $new_proposal->doc_id . ")", ['process' => '[clone-proposal]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);

        //make changes
        return $new_proposal;
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
            'docresource_type',
            'docresource_id',
            'doc_categoryid',
        ];

        //check if all keys exist
        if (array_diff($required_keys, array_keys($data))) {
            Log::error("The supplied data is not valid", ['process' => '[clone-proposal]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
            return false;
        }

        //validate client
        if ($data['docresource_type'] == 'client') {
            if (\App\Models\Client::Where('client_id', $data['docresource_id'])->doesntExist()) {
                Log::error("the supplied client id (" . $data['docresource_id'] . ") could not be found", ['process' => '[clone-proposal]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                return false;
            }
        }

        //validate client primary user
        if ($data['docresource_type'] == 'client') {
            if ($user = \App\Models\User::Where('clientid', $data['docresource_id'])->Where('account_owner', 'yes')->first()) {
                $payload['doc_fallback_client_first_name'] = $user->first_name;
                $payload['doc_fallback_client_last_name'] = $user->last_name;
                $payload['doc_fallback_client_email'] = $user->email;
            } else {
                Log::error("the supplied primary user for the client (" . $data['docresource_id'] . ") could not be found", ['process' => '[clone-proposal]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                return false;
            }
        }

        //validate lead
        if ($data['docresource_type'] == 'lead') {
            if ($lead = \App\Models\Lead::Where('lead_id', $data['docresource_id'])->first()) {
                $payload['doc_fallback_client_first_name'] = $lead->lead_firstname;
                $payload['doc_fallback_client_last_name'] = $lead->lead_lastname;
                $payload['doc_fallback_client_email'] = $lead->lead_email;
            } else {
                Log::error("the supplied lead id (" . $data['docresource_id'] . ") could not be found", ['process' => '[clone-proposal]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                return false;
            }
        }

        return $payload;

    }

}