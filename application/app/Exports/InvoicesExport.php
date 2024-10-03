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
                    case 'bill_invoiceid':
                        $map[] = runtimeInvoiceIdFormat($invoices->bill_invoiceid);
                        break;
                    case 'bill_date':
                        $map[] = runtimeDate($invoices->bill_date);
                        break;
                    case 'client_company_name':
                        $map[] = $invoices->client_company_name;
                        break;
                    case 'bill_clientid':
                        $map[] = $invoices->bill_clientid;
                        break;
                    case 'project_title':
                        $map[] = $invoices->project_title;
                        break;
                    case 'bill_projectid':
                        $map[] = $invoices->bill_projectid;
                        break;
                    case 'bill_subtotal':
                        $map[] = runtimeMoneyFormat($invoices->bill_subtotal);
                        break;
                    case 'bill_discount_type':
                        $map[] = runtimeLang($invoices->bill_discount_type);
                        break;
                    case 'bill_discount_percentage':
                        //$map[] = ($invoices->bill_discount_type == 'fixed' || $invoices->bill_discount_type == 'none') ? '---' : $invoices->bill_discount_percentage;
                        $map[] = $invoices->bill_discount_percentage;
                        break;
                    case 'bill_discount_amount':
                        $map[] = runtimeMoneyFormat($invoices->bill_discount_amount);
                        break;
                    case 'bill_amount_before_tax':
                        $map[] = runtimeMoneyFormat($invoices->bill_amount_before_tax);
                        break;
                    case 'bill_tax_total_amount':
                        $map[] = runtimeMoneyFormat($invoices->bill_tax_total_amount);
                        break;
                    case 'bill_adjustment_description':
                        $map[] = $invoices->bill_adjustment_description;
                        break;
                    case 'bill_adjustment_amount':
                        $map[] = runtimeMoneyFormat($invoices->bill_adjustment_amount);
                        break;
                    case 'bill_final_amount':
                        $map[] = runtimeMoneyFormat($invoices->bill_final_amount);
                        break;
                    case 'bill_recurring':
                        $map[] = runtimeLang($invoices->bill_recurring);
                        break;
                    case 'bill_recurring_duration':
                        $map[] = $invoices->bill_recurring_duration;
                        break;
                    case 'bill_recurring_period':
                        $map[] = runtimeLang($invoices->bill_recurring_period);
                        break;
                    case 'bill_recurring_cycles':
                        $map[] = $invoices->bill_recurring_cycles;
                        break;
                    case 'bill_recurring_cycles_counter':
                        $map[] = $invoices->bill_recurring_cycles_counter;
                        break;
                    case 'bill_recurring_last':
                        $map[] = runtimeDate($invoices->bill_recurring_last);
                        break;
                    case 'bill_recurring_next':
                        $map[] = runtimeDate($invoices->bill_recurring_next);
                        break;
                    case 'bill_overdue_reminder_sent':
                        $map[] = runtimeLang($invoices->bill_overdue_reminder_sent);
                        break;
                    case 'bill_viewed_by_client':
                        $map[] = runtimeLang($invoices->bill_viewed_by_client);
                        break;
                    case 'bill_status':
                        $map[] = runtimeLang($invoices->bill_status);
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
            'bill_invoiceid' => __('lang.invoice_id'),
            'bill_date' => __('lang.invoice_date'),
            'client_company_name' => __('lang.client'),
            'bill_clientid' => __('lang.client_id'),
            'project_title' => __('lang.project_title'),
            'bill_projectid' => __('lang.project_id'),
            'bill_subtotal' => __('lang.sub_total'),
            'bill_discount_type' => __('lang.discount_type'),
            'bill_discount_percentage' => __('lang.discount_percentage'),
            'bill_discount_amount' => __('lang.discount_amount'),
            'bill_amount_before_tax' => __('lang.amount_before_tax'),
            'bill_tax_total_amount' => __('lang.tax'),
            'bill_adjustment_description' => __('lang.adjustment_description'),
            'bill_adjustment_amount' => __('lang.adjustment_amount'),
            'bill_final_amount' => __('lang.invoice_total'),
            'bill_recurring' => __('lang.recurring'),
            'bill_recurring_duration' => __('lang.recurring_duration'),
            'bill_recurring_period' => __('lang.recurring_period'),
            'bill_recurring_cycles' => __('lang.recurring_cycles'),
            'bill_recurring_cycles_counter' => __('lang.times_recurred'),
            'bill_recurring_last' => __('lang.last_recurred'),
            'bill_recurring_next' => __('lang.next_recurring'),
            'bill_overdue_reminder_sent' => __('lang.sent_overdue_reminder'),
            'bill_viewed_by_client' => __('lang.viewed_by_client'),
            'bill_status' => __('lang.status'),
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
