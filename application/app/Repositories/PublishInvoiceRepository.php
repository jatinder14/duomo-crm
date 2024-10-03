<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Fooo;
use App\Repositories\EventRepository;
use App\Repositories\EventTrackingRepository;
use App\Repositories\InvoiceGeneratorRepository;
use App\Repositories\UserRepository;

class PublishInvoiceRepository {

    /**
     * The fooo repository instance.
     */
    protected $invoicegenerator;
    protected $eventrepo;
    protected $trackingrepo;

    /**
     * Inject dependecies
     */
    public function __construct(
        InvoiceGeneratorRepository $invoicegenerator,
        EventRepository $eventrepo,
        EventTrackingRepository $trackingrepo,
        UserRepository $userrepo
    ) {
        $this->invoicegenerator = $invoicegenerator;
        $this->eventrepo = $eventrepo;
        $this->trackingrepo = $trackingrepo;
        $this->userrepo = $userrepo;
    }

    /**
     * publish an invoice as follows
     *  - set invoice as publish, update status
     *  - record timeline event
     *  - send an email to teh customer
     * @param int $id invoice id
     * @return obj invoice
     */
    public function publishInvoice($id = '') {

        //validate invoice id
        if (!is_numeric($id)) {
            return [
                'status' => false,
                'message' => __('lang.error_loading_item'),
            ];
        }

        //generate the invoice
        if (!$payload = $this->invoicegenerator->generate($id)) {
            return [
                'status' => false,
                'message' => __('lang.error_loading_item'),
            ];
        }

        //invoice
        $invoice = $payload['bill'];

        //validate current status
        if ($invoice->bill_status != 'draft') {
            return [
                'status' => false,
                'message' => __('lang.invoice_already_piblished'),
            ];
        }

        /** ----------------------------------------------
         * record event [comment]
         * ----------------------------------------------*/
        $resource_id = (is_numeric($invoice->bill_projectid)) ? $invoice->bill_projectid : $invoice->bill_clientid;
        $resource_type = (is_numeric($invoice->bill_projectid)) ? 'project' : 'client';
        $data = [
            'event_creatorid' => (auth()->check()) ? auth()->id() : $invoice->bill_creatorid,
            'event_item' => 'invoice',
            'event_item_id' => $invoice->bill_invoiceid,
            'event_item_lang' => 'event_created_invoice',
            'event_item_content' => __('lang.invoice') . ' - ' . $invoice->formatted_bill_invoiceid,
            'event_item_content2' => '',
            'event_parent_type' => 'invoice',
            'event_parent_id' => $invoice->bill_invoiceid,
            'event_parent_title' => $invoice->project_title,
            'event_clientid' => $invoice->bill_clientid,
            'event_show_item' => 'yes',
            'event_show_in_timeline' => 'yes',
            'eventresource_type' => $resource_type,
            'eventresource_id' => $resource_id,
            'event_notification_category' => 'notifications_billing_activity',

        ];
        //record event
        if ($event_id = $this->eventrepo->create($data)) {
            //get users (main client)
            $users = $this->userrepo->getClientUsers($invoice->bill_clientid, 'owner', 'ids');
            //record notification
            $emailusers = $this->trackingrepo->recordEvent($data, $users, $event_id);
        }

        /** ----------------------------------------------
         * send email [queued]
         * ----------------------------------------------*/
        if (isset($emailusers) && is_array($emailusers)) {
            //other data
            $data = [];
            //send to client users
            if ($users = \App\Models\User::WhereIn('id', $emailusers)->get()) {
                foreach ($users as $user) {
                    $mail = new \App\Mail\PublishInvoice($user, $data, $invoice);
                    $mail->build();
                }
            }
        }

        //get invoice again
        $invoice = \App\Models\Invoice::Where('bill_invoiceid', $invoice->bill_invoiceid)->first();

        //get new invoice status and save it
        $bill_date = \Carbon\Carbon::parse($invoice->bill_date);
        $bill_due_date = \Carbon\Carbon::parse($invoice->bill_due_date);
        if ($bill_due_date->diffInDays(today(), false) <= 0) {
            $invoice->bill_status = 'due';
        } else {
            $invoice->bill_status = 'overdue';
        }
        $invoice->save();

        //return the invoice
        return [
            'status' => true,
            'invoice' => $invoice,
        ];

    }
}