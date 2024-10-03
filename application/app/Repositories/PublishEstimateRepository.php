<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Fooo;
use App\Repositories\EstimateGeneratorRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventTrackingRepository;
use App\Repositories\UserRepository;

class PublishEstimateRepository {

    /**
     * The fooo repository instance.
     */
    protected $estimategenerator;
    protected $eventrepo;
    protected $trackingrepo;

    /**
     * Inject dependecies
     */
    public function __construct(
        EstimateGeneratorRepository $estimategenerator,
        EventRepository $eventrepo,
        EventTrackingRepository $trackingrepo,
        UserRepository $userrepo
    ) {
        $this->estimategenerator = $estimategenerator;
        $this->eventrepo = $eventrepo;
        $this->trackingrepo = $trackingrepo;
        $this->userrepo = $userrepo;
    }

    /**
     * publish an estimate as follows
     *  - set estimate as publish, update status
     *  - record timeline event
     *  - send an email to teh customer
     * @param int $id estimate id
     * @return obj estimate
     */
    public function publishEstimate($id = '') {

        //validate estimate id
        if (!is_numeric($id)) {
            return [
                'status' => false,
                'message' => __('lang.error_loading_item'),
            ];
        }

        //generate the estimate
        if (!$payload = $this->estimategenerator->generate($id)) {
            return [
                'status' => false,
                'message' => __('lang.error_loading_item'),
            ];
        }

        //estimate
        $estimate = $payload['bill'];

        //validate current status
        if ($estimate->bill_status != 'draft') {
            return [
                'status' => false,
                'message' => __('lang.estimate_already_piblished'),
            ];
        }

        /** ----------------------------------------------
         * record event [comment]
         * ----------------------------------------------*/
        $data = [
            'event_creatorid' => (auth()->check()) ? auth()->id() : $estimate->bill_creatorid,
            'event_item' => 'estimate',
            'event_item_id' => $estimate->bill_estimateid,
            'event_item_lang' => 'event_created_estimate',
            'event_item_content' => __('lang.estimate') . ' - ' . $estimate->formatted_bill_estimateid,
            'event_item_content2' => '',
            'event_parent_type' => 'estimate',
            'event_parent_id' => $estimate->bill_estimateid,
            'event_parent_title' => $estimate->project_title,
            'event_clientid' => $estimate->bill_clientid,
            'event_show_item' => 'yes',
            'event_show_in_timeline' => 'yes',
            'eventresource_type' => (is_numeric($estimate->bill_projectid)) ? 'project' : 'client',
            'eventresource_id' => (is_numeric($estimate->bill_projectid)) ? $estimate->bill_projectid : $estimate->bill_clientid,
            'event_notification_category' => 'notifications_billing_activity',

        ];
        //record event
        if ($event_id = $this->eventrepo->create($data)) {
            //get users (main client)
            $users = $this->userrepo->getClientUsers($estimate->bill_clientid, 'owner', 'ids');
            //record notification
            $emailusers = $this->trackingrepo->recordEvent($data, $users, $event_id);
        }

        /** ----------------------------------------------
         * send email [queued]
         * ----------------------------------------------*/
        if (isset($emailusers) && is_array($emailusers)) {
            //send to users
            if ($users = \App\Models\User::WhereIn('id', $emailusers)->get()) {
                foreach ($users as $user) {
                    $mail = new \App\Mail\PublishEstimate($user, [], $estimate);
                    $mail->build();
                }
            }
        }

        //get invoice again
        $estimate = \App\Models\Estimate::Where('bill_estimateid', $estimate->bill_estimateid)->first();
        $estimate->bill_status = 'new';
        $estimate->save();

        //return the estimate
        return [
            'status' => true,
            'estimate' => $estimate,
        ];

    }
}