<?php

namespace App\Exports;

use App\Repositories\CustomFieldsRepository;
use App\Repositories\PaymentRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping {

    /**
     * The payment repo repository
     */
    protected $paymentrepo;

    /**
     * The custom repo repository
     */
    protected $customrepo;

    public function __construct(PaymentRepository $paymentrepo, CustomFieldsRepository $customrepo) {

        $this->paymentrepo = $paymentrepo;
        $this->customrepo = $customrepo;

    }

    //get the payments
    public function collection() {
        //search
        $payments = $this->paymentrepo->search('', ['no_pagination' => true]);
        //return
        return $payments;
    }

    //map the columns that we want
    public function map($payments): array{

        $map = [];

        //standard fields - loop thorugh all post data
        if (is_array(request('standard_field'))) {
            foreach (request('standard_field') as $key => $value) {
                if ($value == 'on') {
                    switch ($key) {
                    case 'payment_date':
                        $map[] = runtimeDate($payments->payment_date);
                        break;
                    case 'payment_id':
                        $map[] = $payments->payment_id;
                        break;
                    case 'payment_transaction_id':
                        $map[] = $payments->payment_transaction_id;
                        break;
                    case 'payment_amount':
                        $map[] = runtimeMoneyFormat($payments->payment_amount);
                        break;
                    case 'payment_invoiceid':
                        $map[] = runtimeInvoiceIdFormat($payments->payment_invoiceid);
                        break;
                    case 'payment_client_name':
                        $map[] = $payments->client_company_name;
                        break;
                    case 'payment_clientid':
                        $map[] = $payments->payment_clientid;
                        break;
                    case 'payment_projectid':
                        $map[] = $payments->payment_projectid;
                        break;
                    case 'payment_project_title':
                        $map[] = $payments->project_title;
                        break;
                    case 'payment_gateway':
                        $map[] = $payments->payment_gateway;
                        break;
                    case 'payment_notes':
                        $map[] = strip_tags($payments->payment_notes);
                        break;
                    default:
                        $map[] = $payments->{$key};
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
                            $map[] = runtimeDate($payments->{$key});
                            break;
                        case 'checkbox':
                            $map[] = ($payments->{$key} == 'on') ? __('lang.checked_custom_fields') : '---';
                            break;
                        default:
                            $map[] = $payments->{$key};
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

        //lang - standard fields (map each field here)
        $standard_lang = [
            'payment_id' => __('lang.payment_id'),
            'payment_transaction_id' => __('lang.transaction_id'),
            'payment_date' => __('lang.date'),
            'payment_invoiceid' => __('lang.invoice_id'),
            'payment_client_name' => __('lang.client'),
            'payment_clientid' => __('lang.client_id'),
            'payment_projectid' => __('lang.project_id'),
            'payment_project_title' => __('lang.project_title'),
            'payment_amount' => __('lang.amount'),
            'payment_gateway' => __('lang.payment_gateway'),
            'payment_notes' => __('lang.notes'),
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
