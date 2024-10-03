<?php

namespace App\Exports;

use App\Repositories\InvoiceRepository;
use App\Repositories\CustomFieldsRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoicesExport implements FromCollection, WithHeadings, WithMapping {

    /**
     * The invoice repo repository
     */
    protected $invoicerepo;

    /**
     * The custom repo repository
     */
    protected $customrepo;

    public function __construct(InvoiceRepository $invoicerepo, CustomFieldsRepository $customrepo) {

        $this->invoicerepo = $invoicerepo;
        $this->customrepo = $customrepo;

    }

    //get the invoices
    public function collection() {
        //search
        $invoices = $this->invoicerepo->search('', ['no_pagination' => true]);
        //return
        return $invoices;
    }

    //map the columns that we want
    public function map($invoices): array{

        $map = [];

        //standard fields - loop thorugh all post data
        if (is_array(request('standard_field'))) {
            foreach (request('standard_field') as $key => $value) {
                if ($value == 'on') {
                    switch ($key) {
                    case 'invoice_id':
                        $map[] = $invoices->invoice_id;
                        break;
                    case 'invoices_total':
                        $map[] = runtimeMoneyFormat($invoices->sum_all_payments);
                        break;
                    case 'invoice_status':
                        $map[] = runtimeLang($invoices->invoice_status);
                        break;
                    case 'invoice_date':
                        $map[] = runtimeDate($invoices->invoice_status);
                        break;
                    default:
                        $map[] = $invoices->{$key};
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
                            $map[] = runtimeDate($invoices->{$key});
                            break;
                        case 'checkbox':
                            $map[] = ($invoices->{$key} == 'on') ? __('lang.checked_custom_fields') : '---';
                            break;
                        default:
                            $map[] = $invoices->{$key};
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
            'invoice_billing_country' => __('lang.country'),
            'invoice_status' => __('lang.status'),
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
