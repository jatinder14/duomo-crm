<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for cloning estimates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

/** --------------------------------------------------------------------------
 * [Clone Estimate Repository]
 * Clone an estimate. The new estimate is set to 'draft status' by default
 * It can be published as needed
 * @source Nextloop
 *--------------------------------------------------------------------------*/
namespace App\Repositories;

use App\Repositories\EstimateGeneratorRepository;
use App\Repositories\EstimateRepository;
use Illuminate\Support\Str;
use Log;
use Illuminate\Support\Facades\File;

class CloneEstimateRepository {

    /**
     * The estimate repo instance
     */
    protected $estimaterepo;

    /**
     * The estimate generator instance
     */
    protected $estimategenerator;

    /**
     * Inject dependecies
     */
    public function __construct(EstimateRepository $estimaterepo, EstimateGeneratorRepository $estimategenerator) {
        $this->estimaterepo = $estimaterepo;
        $this->estimategenerator = $estimategenerator;
    }

    /**
     * Clone an estimate
     * @param array data array
     *              - estimate_id
     *              - client_id
     *              - project_id
     *              - estimate_date
     *              - return (id|object)
     * @return mixed int|object
     */
    public function clone ($data = []) {

        //info
        Log::info("cloning estimate started", ['process' => '[CloneEstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);

        //validate information
        if (!$this->validateData($data)) {
            Log::info("cloning estimate failed", ['process' => '[CloneEstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
            return false;
        }

        //get the estimate via the estimate generator
        if (!$payload = $this->estimategenerator->generate($data['estimate_id'])) {
            Log::error("an estimate with this estimate id, could not be loaded", ['process' => '[CloneEstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'estimate_id' => $data['estimate_id']]);
            return false;
        }

        //get clean estimate object for cloning
        $estimate = \App\Models\Estimate::Where('bill_estimateid', $data['estimate_id'])->first();

        //clone main estimate
        $new_estimate = $estimate->replicate();

        //update new estimate with specified data
        $new_estimate->bill_created = now();
        $new_estimate->bill_updated = now();
        $new_estimate->bill_clientid = $data['client_id'];
        $new_estimate->bill_date = $data['estimate_date'];
        $new_estimate->bill_projectid = $data['project_id'];
        $new_estimate->bill_visibility = 'hidden';
        $new_estimate->bill_status = 'draft';
        $new_estimate->bill_expiry_date = \Carbon\Carbon::parse($data['estimate_date'])->addDays(30)->format('Y-m-d');
        $new_estimate->bill_uniqueid = str_unique();

        //[cleanup] remove recurring and other unwanted data, inherited from parent
        $new_estimate->bill_date_sent_to_customer = null;
        $new_estimate->bill_notes = '';
        $new_estimate->bill_converted_to_invoice = 'no';
        $new_estimate->bill_converted_to_invoice_invoiceid = null;
        $new_estimate->estimate_automation_status = 'disabled';
        $new_estimate->estimate_automation_project_title = '';
        $new_estimate->estimate_automation_project_status = 'in_progress';
        $new_estimate->estimate_automation_create_tasks = 'no';
        $new_estimate->estimate_automation_project_email_client = 'no';
        $new_estimate->estimate_automation_create_invoice = 'no';
        $new_estimate->estimate_automation_invoice_due_date = 7;
        $new_estimate->estimate_automation_invoice_email_client = 'no';
        $new_estimate->estimate_automation_copy_attachments = 'no';
        $new_estimate->estimate_automation_log_created_project_id = null;
        $new_estimate->estimate_automation_log_created_invoice_id = null;
        $new_estimate->bill_system = 'no';
        $new_estimate->bill_viewed_by_client = 'no';
        $new_estimate->bill_publishing_type = 'instant';
        $new_estimate->bill_publishing_scheduled_date = null;
        $new_estimate->bill_publishing_scheduled_status = '';
        $new_estimate->bill_publishing_scheduled_log = '';

        //save
        $new_estimate->save();

        //replicate each line item
        foreach ($payload['lineitems'] as $lineitem_x) {

            //get clean lineitem object for cloning
            if (!$lineitem = \App\Models\Lineitem::Where('lineitem_id', $lineitem_x->lineitem_id)->first()) {
                //skip it
                $continue;
            }

            //clone line
            $new_lineitem = $lineitem->replicate();
            $new_lineitem->lineitemresource_id = $new_estimate->bill_estimateid;
            $new_lineitem->save();

            //replicate [inline taxes]
            if ($estimate->bill_tax_type == 'inline') {
                //get all the taxes for original lineitem
                $taxes = \App\Models\Tax::Where('tax_lineitem_id', $lineitem_x->lineitem_id)->get();
                foreach ($taxes as $tax_x) {
                    //get clean tax item for cloning
                    if ($tax = \App\Models\Tax::Where('tax_id', $tax_x->tax_id)->first()) {
                        $new_tax = $tax->replicate();
                        $new_tax->tax_lineitem_id = $new_lineitem->lineitem_id;
                        $new_tax->taxresource_id = $new_estimate->bill_estimateid;
                        $new_tax->save();
                    }
                }
            }

        }

        //replicate [summary taxes]
        if ($estimate->bill_tax_type == 'summary') {
            foreach ($payload['taxes'] as $tax_x) {
                //get clean tax item for cloning
                if ($tax = \App\Models\Tax::Where('tax_id', $tax_x->tax_id)->first()) {
                    if ($tax->taxresource_type == 'estimate') {
                        $new_tax = $tax->replicate();
                        $new_tax->taxresource_id = $new_estimate->bill_estimateid;
                        $new_tax->save();
                    }
                }
            }
        }

        //clone files
        if ($files = \App\Models\File::Where('fileresource_type', 'estimate')->Where('fileresource_id', $estimate->bill_estimateid)->get()) {
            //loop through each file
            foreach ($files as $file) {

                //unique key
                $uniqueid = Str::random(50);

                //directory
                $directory = Str::random(40);

                //paths
                $source = BASE_DIR . "/storage/files/" . $file->file_directory;
                $destination = BASE_DIR . "/storage/files/$directory";

                //validate
                if (is_dir($source)) {

                    //copy the database record
                    $new_file = $file->replicate();
                    $new_file->file_creatorid = auth()->id();
                    $new_file->file_clientid = $new_estimate->bill_clientid;
                    $new_file->file_uniqueid = $uniqueid;
                    $new_file->file_upload_unique_key = Str::random(50);
                    $new_file->file_directory = $directory;
                    $new_file->fileresource_id = $new_estimate->bill_estimateid;
                    $new_file->save();

                    //copy folder
                    File::copyDirectory($source, $destination);
                }
            }
        }

        //finished, make estimate visible
        $new_estimate->bill_visibility = 'visible';
        $new_estimate->save();

        Log::info("cloning estimate completed", ['process' => '[CloneEstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'new_estimate_id' => $new_estimate->bill_estimateid]);

        //return estimate id | estimate object
        if (isset($data['return']) && $data['return'] == 'id') {
            return $new_estimate->bill_estimateid;
        } else {
            return $new_estimate;
        }
    }

    /**
     * validate required data for cloning an estimate
     * @param array $data information payload
     * @return bool
     */
    private function validateData($data = []) {

        //validation
        if (!isset($data['estimate_id']) || !isset($data['client_id']) || !isset($data['estimate_date'])) {
            Log::error("the supplied data is not valid", ['process' => '[CloneEstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
            return false;
        }

        //estimate id
        if (!is_numeric($data['estimate_id'])) {
            Log::error("the supplied estimate id is invalid", ['process' => '[CloneEstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'estimate_id' => $data['estimate_id']]);
            return false;
        }

        //client id
        if (!is_numeric($data['client_id'])) {
            Log::error("the supplied client id is invalid", ['process' => '[CloneEstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'client_id' => $data['client_id']]);
            return false;
        }

        //check client exists
        if (!$client = \App\Models\Client::Where('client_id', $data['client_id'])->first()) {
            Log::error("the specified client could not be found", ['process' => '[CloneEstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $data['project_id']]);
            return false;
        }

        //check project exists
        if (isset($data['project_id']) && is_numeric($data['project_id'])) {
            if (!$project = \App\Models\Project::Where('project_id', $data['project_id'])->first()) {
                Log::error("the specified project could not be found", ['process' => '[CloneEstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $data['project_id']]);
                return false;
            }
        }

        //check client and project match
        if (isset($data['project_id']) && is_numeric($data['project_id'])) {
            if ($project->project_clientid != $client->client_id) {
                Log::error("the specified client & project do not match", ['process' => '[CloneEstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $data['project_id']]);
                return false;
            }
        }

        return true;
    }

}