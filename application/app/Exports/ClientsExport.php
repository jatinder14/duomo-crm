<?php

namespace App\Exports;

use App\Repositories\ClientRepository;
use App\Repositories\CustomFieldsRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientsExport implements FromCollection, WithHeadings, WithMapping {

    /**
     * The client repo repository
     */
    protected $clientrepo;

    /**
     * The custom repo repository
     */
    protected $customrepo;

    public function __construct(ClientRepository $clientrepo, CustomFieldsRepository $customrepo) {

        $this->clientrepo = $clientrepo;
        $this->customrepo = $customrepo;

    }

    //get the clients
    public function collection() {
        //search
        request()->merge([
            'search_type' => 'exports',
        ]);
        $clients = $this->clientrepo->search('', ['no_pagination' => true]);
        //return
        return $clients;
    }

    //map the columns that we want
    public function map($clients): array{

        $map = [];

        //standard fields - loop thorugh all post data
        if (is_array(request('standard_field'))) {
            foreach (request('standard_field') as $key => $value) {
                if ($value == 'on') {
                    switch ($key) {
                    case 'client_company_name':
                        $map[] = $clients->client_company_name;
                        break;
                    case 'client_created':
                        $map[] = runtimeDate($clients->client_created);
                        break;
                    case 'category':
                        $map[] = $clients->category_name;
                        break;
                    case 'contact_name':
                        $map[] = $clients->first_name . ' ' . $clients->last_name;
                        break;
                    case 'contact_email':
                        $map[] = $clients->email;
                        break;
                    case 'client_phone':
                        $map[] = $clients->client_phone;
                        break;
                    case 'client_website':
                        $map[] = $clients->client_website;
                        break;
                    case 'client_vat':
                        $map[] = $clients->client_vat;
                        break;
                    case 'client_billing_street':
                        $map[] = $clients->client_billing_street;
                        break;
                    case 'client_billing_city':
                        $map[] = $clients->client_billing_city;
                        break;
                    case 'client_billing_state':
                        $map[] = $clients->client_billing_state;
                        break;
                    case 'client_billing_zip':
                        $map[] = $clients->client_billing_zip;
                        break;
                    case 'client_billing_country':
                        $map[] = $clients->client_billing_country;
                        break;
                    case 'invoices':
                        $map[] = runtimeMoneyFormat($clients->sum_invoices_all);
                        break;
                    case 'payments':
                        $map[] = runtimeMoneyFormat($clients->sum_all_payments);
                        break;
                    case 'client_status':
                        $map[] = runtimeLang($clients->client_status);
                        break;
                    default:
                        $map[] = $clients->{$key};
                        break;
                    }
                }
            }
        }

        //custom fields - loop thorugh all post data
        if (is_array(request('custom_field'))) {
            foreach (request('custom_field') as $key => $value) {
                if ($value == 'on') {
                    if ($field = \App\Models\CustomField::Where('customfields_name', $key)->first()) {
                        switch ($field->customfields_datatype) {
                        case 'date':
                            $map[] = runtimeDate($clients->{$key});
                            break;
                        case 'checkbox':
                            $map[] = ($clients->{$key} == 'on') ? __('lang.checked_custom_fields') : '---';
                            break;
                        default:
                            $map[] = $clients->{$key};
                            break;
                        }
                    } else {
                        $map[] = '';
                    }
                }
            }
        }

        return $map;
    }

    //create heading
    public function headings(): array
    {

        //headings
        $heading = [];

        //lang - standard fields
        $standard_lang = [
            'client_company_name' => __('lang.client_name'),
            'client_created' => __('lang.date_created'),
            'category' => __('lang.category'),
            'contact_name' => __('lang.contact_name'),
            'contact_email' => __('lang.contact_email'),
            'client_phone' => __('lang.telephone'),
            'client_website' => __('lang.website'),
            'client_vat' => __('lang.vat_tax_number'),
            'client_billing_street' => __('lang.street'),
            'client_billing_city' => __('lang.city'),
            'client_billing_state' => __('lang.state'),
            'client_billing_zip' => __('lang.zipcode'),
            'client_billing_country' => __('lang.country'),
            'invoices' => __('lang.invoices'),
            'payments' => __('lang.payments'),
            'client_status' => __('lang.status'),
        ];

        //lang - custom fields (i.e. field titles)
        $custom_lang = $this->customrepo->fieldTitles();

        //standard fields - loop thorugh all post data
        if (is_array(request('standard_field'))) {
            foreach (request('standard_field') as $key => $value) {
                if ($value == 'on') {
                    $heading[] = (isset($standard_lang[$key])) ? $standard_lang[$key] : $key;
                }
            }
        }

        //custom fields - loop thorugh all post data
        if (is_array(request('custom_field'))) {
            foreach (request('custom_field') as $key => $value) {
                if ($value == 'on') {
                    $heading[] = (isset($custom_lang[$key])) ? $custom_lang[$key] : $key;
                }
            }
        }

        //return full headings
        return $heading;
    }
}
