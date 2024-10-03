<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @contract    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ContractRepository {

    /**
     * The leads repository instance.
     */
    protected $contract;

    /**
     * Inject dependecies
     */
    public function __construct(Contract $contract) {
        $this->contract = $contract;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object contracts collection
     */
    public function search($id = '', $data = []) {

        $contracts = $this->contract->newQuery();

        //default - always apply filters
        if (!isset($data['apply_filters'])) {
            $data['apply_filters'] = true;
        }

        //joins
        $contracts->leftJoin('users', 'users.id', '=', 'contracts.doc_creatorid');
        $contracts->leftJoin('clients', 'clients.client_id', '=', 'contracts.doc_client_id');
        $contracts->leftJoin('leads', 'leads.lead_id', '=', 'contracts.doc_lead_id');
        $contracts->leftJoin('estimates', 'estimates.bill_contractid', '=', 'contracts.doc_id');
        $contracts->leftJoin('categories', 'categories.category_id', '=', 'contracts.doc_categoryid');

        //join: users reminders - do not do this for cronjobs
        if (auth()->check()) {
            $contracts->leftJoin('reminders', function ($join) {
                $join->on('reminders.reminderresource_id', '=', 'contracts.doc_id')
                    ->where('reminders.reminderresource_type', '=', 'contract')
                    ->where('reminders.reminder_userid', '=', auth()->id());
            });
        }

        // all client fields
        $contracts->selectRaw('*');

        //count contracts (all)
        $contracts->selectRaw("(SELECT COUNT(*)
                                      FROM contracts)
                                      AS count_contracts_all");

        //count contracts (draft)
        $contracts->selectRaw("(SELECT COUNT(*)
                                      FROM contracts
                                      WHERE doc_status = 'draft')
                                      AS count_contracts_draft");

        //count contracts (new)
        $contracts->selectRaw("(SELECT COUNT(*)
                                      FROM contracts
                                      WHERE doc_status = 'new')
                                      AS count_contracts_new");

        //count contracts (accepted)
        $contracts->selectRaw("(SELECT COUNT(*)
                                      FROM contracts
                                      WHERE doc_status = 'accepted')
                                      AS count_contracts_accepted");

        //count contracts (declined)
        $contracts->selectRaw("(SELECT COUNT(*)
                                      FROM contracts
                                      WHERE doc_status = 'declined')
                                      AS count_contracts_declined");

        //count contracts (revised)
        $contracts->selectRaw("(SELECT COUNT(*)
                                      FROM contracts
                                      WHERE doc_status = 'revised')
                                      AS count_contracts_revised");

        //client details - first name
        $contracts->selectRaw("(SELECT first_name
                                      FROM users
                                      WHERE clientid = contracts.doc_client_id AND account_owner = 'yes' LIMIT 1)
                                      AS client_first_name");

        //client details - last name
        $contracts->selectRaw("(SELECT last_name
                                      FROM users
                                      WHERE clientid = contracts.doc_client_id AND account_owner = 'yes' LIMIT 1)
                                      AS client_last_name");

        //sum value: all
        $contracts->selectRaw("(SELECT COALESCE(SUM(bill_final_amount), 0.00)
                                      FROM estimates
                                      WHERE bill_contractid = contracts.doc_id)
                                      AS contract_value");

        //default where
        $contracts->whereRaw("1 = 1");

        //filters: id
        if (request()->filled('filter_doc_id')) {
            $contracts->where('doc_id', request('filter_doc_id'));
        }
        if (is_numeric($id)) {
            $contracts->where('doc_id', $id);
        }

        //stats: - count
        if (isset($data['stats']) && (in_array($data['stats'], [
            'count-draft',
            'count-active',
            'count-expired',
            'count-awaiting_signatures',
        ]))) {
            $contracts->where('doc_status', str_replace('count-', '', $data['stats']));
        }

        //stats: - sum
        if (isset($data['stats']) && (in_array($data['stats'], [
            'sum-draft',
            'sum-active',
            'sum-expired',
            'sum-awaiting_signatures',
        ]))) {
            $contracts->where('doc_status', str_replace('sum-', '', $data['stats']));
        }

        //filter category
        if (is_array(request('filter_contract_categoryid')) && !empty(array_filter(request('filter_contract_categoryid')))) {
            $contracts->whereIn('doc_categoryid', request('filter_contract_categoryid'));
        }

        //apply filters
        if ($data['apply_filters']) {

            //filter doc_client_id
            if (request()->filled('filter_doc_client_id')) {
                $contracts->where('doc_client_id', request('filter_doc_client_id'));
            }

            //filter doc_client_id
            if (request()->filled('contractresource_id')) {
                $contracts->where('doc_client_id', request('contractresource_id'));
            }

            //filter doc_client_id
            if (request()->filled('filter_doc_lead_id')) {
                $contracts->where('doc_lead_id', request('filter_doc_lead_id'));
            }

            //filter contract id
            if (request()->filled('filter_contract_id')) {
                $contracts->where('contract_id', request('filter_contract_id'));
            }

            //filter: doc_date (start)
            if (request()->filled('filter_doc_date')) {
                $contracts->whereDate('doc_date', '>=', request('filter_doc_date'));
            }

            //filter: doc_date (end)
            if (request()->filled('filter_doc_date')) {
                $contracts->whereDate('doc_date', '<=', request('filter_doc_date'));
            }

            //filter: doc_created (start)
            if (request()->filled('filter_doc_created')) {
                $contracts->whereDate('doc_created', '>=', request('filter_doc_created'));
            }

            //filter: doc_created (end)
            if (request()->filled('filter_doc_created')) {
                $contracts->whereDate('doc_created', '<=', request('filter_doc_created'));
            }

            //filter: doc_date_from (start)
            if (request()->filled('filter_doc_date_start_start')) {
                $contracts->whereDate('doc_date_start', '>=', request('filter_doc_date_start_start'));
            }

            //filter: doc_date_from (end)
            if (request()->filled('filter_doc_date_start_end')) {
                $contracts->whereDate('doc_date_start', '<=', request('filter_doc_date_start_end'));
            }

            //filter: doc_date_end (start)
            if (request()->filled('filter_doc_date_end_start')) {
                $contracts->whereDate('doc_date_end', '>=', request('filter_doc_date_end_start'));
            }

            //filter: doc_date_end (end)
            if (request()->filled('filter_doc_date_end_end')) {
                $contracts->whereDate('doc_date_end', '<=', request('filter_doc_date_end_end'));
            }

            //filter: doc_status
            if (is_array(request('filter_doc_status')) && !empty(array_filter(request('filter_doc_status')))) {
                $contracts->whereIn('doc_status', request('filter_doc_status'));
            }

            //filter: tags
            if (is_array(request('filter_tags')) && !empty(array_filter(request('filter_tags')))) {
                $contracts->whereHas('tags', function ($query) {
                    $query->whereIn('tag_title', request('filter_tags'));
                });
            }

        }

        //filter - exlude draft contracts
        if (request('filter_contract_exclude_status') == 'draft') {
            $contracts->whereNotIn('doc_status', ['draft']);
        }

        //search: various client columns and relationships (where first, then wherehas)
        if (request()->filled('search_query') || request()->filled('query')) {
            $contracts->where(function ($query) {
                //clean for estimate id search
                $contract_id = str_replace(config('system.settings_contracts_prefix'), '', request('search_query'));
                $contract_id = preg_replace("/[^0-9.,]/", '', $contract_id);
                $contract_id = ltrim($contract_id, '0');
                $query->Where('doc_id', '=', $contract_id);

                $query->orWhere('doc_status', '=', request('search_query'));
                $query->orWhere('lead_firstname', '=', request('search_query'));
                $query->orWhere('lead_lastname', '=', request('search_query'));
                $query->orWhere('client_company_name', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('doc_title', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('lead_title', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('lead_firstname', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('lead_lastname', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('doc_date_start', '=', date('Y-m-d', strtotime(request('search_query'))));
                $query->orWhere('doc_date_end', '=', date('Y-m-d', strtotime(request('search_query'))));
                if (is_numeric(request('search_query'))) {
                    $query->orWhere('bill_final_amount', '=', request('search_query'));
                }
            });
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('contracts', request('orderby'))) {
                $contracts->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'client':
                $contracts->orderBy('client_company_name', request('sortorder'));
                $contracts->orderBy('lead_firstname', request('sortorder'));
                break;
            case 'value':
                $contracts->orderBy('bill_final_amount', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $contracts->orderBy('doc_id', 'desc');
        }

        //stats - count all
        if (isset($data['stats']) && in_array($data['stats'], [
            'count-draft',
            'count-active',
            'count-awaiting_signatures',
            'count-expired',
        ])) {
            return $contracts->count();
        }

        //stats - sum balances
        if (isset($data['stats']) && in_array($data['stats'], [
            'sum-draft',
            'sum-active',
            'sum-awaiting_signatures',
            'sum-expired',
        ])) {
            return $contracts->get()->sum('contract_value');
        }

        // Get the results and return them.
        return $contracts->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * get the company name linked to a contract
     *
     * @param  doc_id  prposal id
     * @return string
     */
    public function contactDetails($doc_id = '') {

        $details = [
            'company_name' => '---',
            'first_name' => '---',
            'last_name' => '---',
            'email' => '---',
        ];

        if ($contract = \App\Models\Contract::Where('doc_id', $doc_id)->first()) {
            //client contract
            if ($contract->docresource_type == 'client') {
                //company name
                if ($client = \App\Models\Client::Where('client_id', $contract->doc_client_id)->first()) {
                    $details['company_name'] = $client->client_company_name;
                }
                //first name
                if ($user = \App\Models\User::Where('clientid', $contract->doc_client_id)->where('account_owner', 'yes')->first()) {
                    $details['first_name'] = $user->first_name;
                    $details['last_name'] = $user->last_name;
                    $details['email'] = $user->email;
                }
            }
            //lead contract
            if ($contract->docresource_type == 'lead') {
                //company name
                if ($lead = \App\Models\Lead::Where('lead_id', $contract->doc_lead_id)->first()) {
                    //$details['company_name'] = ($lead->lead_company_name != '') ? $lead->lead_company_name : $lead->lead_firstname. ' '. $lead->lead_lastname;
                    $details['company_name'] = $lead->lead_firstname . ' ' . $lead->lead_lastname;
                    $details['first_name'] = $lead->lead_firstname;
                    $details['last_name'] = $lead->lead_lastname;
                    $details['email'] = $lead->lead_email;
                }
            }
        }

        return $details;
    }

    /**
     * refresh contract status
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function refreshContract($id) {

        //get the contract
        if (!$contract = \App\Models\Contract::Where('doc_id', $id)->first()) {
            return;
        }

        //ignore for draft status
        if ($contract->doc_status == 'draft') {
            return;
        }

        //end date
        $end_date = \Carbon\Carbon::parse($contract->doc_date_end);

        //update expired
        if ($contract->doc_date_end != null && $contract->doc_date_end != '') {
            if ($end_date->diffInDays(today(), false) < 0) {
                $contract->doc_status = 'active';
                $contract->save();
            }
        }

        //update to [awaiting_signatures]
        if ($contract->doc_provider_signed_status == 'unsigned' || $contract->doc_signed_status == 'unsigned') {
            $contract->doc_status = 'awaiting_signatures';
            $contract->save();
        }

        //update to [active]
        if ($contract->doc_provider_signed_status == 'signed' && $contract->doc_signed_status == 'signed') {
            $contract->doc_status = 'active';
            $contract->save();
        }

        //update to [expired]
        if ($contract->doc_date_end != null && $contract->doc_date_end != '') {
            if ($end_date->diffInDays(today(), false) > 0) {
                //only update if contract was marked as active
                if ($contract->doc_status == 'active') {
                    $contract->doc_status = 'expired';
                    $contract->save();
                }
            }
        }
    }

    /**
     * set frontend visibility for signatures and signing
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function visibilitySignatures($contract = '', $view = '') {

        //validate
        if (!$contract instanceof \App\Models\Contract) {
            return false;
        }

        /** -------------------------------------------------------------------------
         * Provider signatures
         * -------------------------------------------------------------------------*/
        if ($contract->doc_provider_signed_status == 'signed') {
            //show - delete signature button
            if (in_array($view, ['edit'])) {
                if (auth()->check() && auth()->user()->is_team && auth()->user()->role->role_contracts >= 2) {
                    //client has not yet signed the contract
                    if ($contract->doc_signed_status == 'unsigned') {
                        config(['visibility.doc_provider_delete_signature' => true]);
                    } else {
                        config(['visibility.doc_provider_delete_signature_disabled' => true]);
                    }
                }
            }
            config(['visibility.doc_provider_signed' => true]);
        } else {
            //show - add signature button
            if (in_array($view, ['edit'])) {
                if (auth()->check() && auth()->user()->is_team && auth()->user()->role->role_contracts >= 2) {
                    config(['visibility.doc_provider_add_signature' => true]);
                }
            }
            config(['visibility.doc_provider_unsigned' => true]);
        }

        /** -------------------------------------------------------------------------
         * Client signatures
         * -------------------------------------------------------------------------*/
        if ($contract->doc_signed_status == 'signed') {
            config(['visibility.doc_client_signed' => true]);
        } else {
            //show signature
            if (!auth()->check() || (auth()->check() && auth()->user()->clientid == $contract->doc_client_id)) {
                config(['visibility.doc_client_add_signature' => true]);
            }
            config(['visibility.doc_client_unsigned' => true]);
        }

    }

}