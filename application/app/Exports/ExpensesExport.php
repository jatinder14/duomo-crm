<?php

namespace App\Exports;

use App\Repositories\CustomFieldsRepository;
use App\Repositories\ExpenseRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping {

    /**
     * The expense repo repository
     */
    protected $expenserepo;

    /**
     * The custom repo repository
     */
    protected $customrepo;

    public function __construct(ExpenseRepository $expenserepo, CustomFieldsRepository $customrepo) {

        $this->expenserepo = $expenserepo;
        $this->customrepo = $customrepo;

    }

    //get the expenses
    public function collection() {
        //search
        $expenses = $this->expenserepo->search('', ['no_pagination' => true]);
        //return
        return $expenses;
    }

    //map the columns that we want
    public function map($expenses): array{

        $map = [];

        //standard fields - loop thorugh all post data
        if (is_array(request('standard_field'))) {
            foreach (request('standard_field') as $key => $value) {
                if ($value == 'on') {
                    switch ($key) {
                    case 'expense_date':
                        $map[] = runtimeDate($expenses->expense_date);
                        break;
                    case 'expenses_user':
                        $map[] = runtimeUser($expenses->first_name, $expenses->last_name);
                        break;
                    case 'expense_description':
                        $map[] = $expenses->expense_description;
                        break;
                    case 'expense_amount':
                        $map[] = runtimeMoneyFormat($expenses->expense_amount);
                        break;
                    case 'expenses_client':
                        $map[] = $expenses->client_company_name;
                        break;
                    case 'expenses_client_id':
                        $map[] = $expenses->client_id;
                        break;
                    case 'expenses_project':
                        $map[] = $expenses->project_title;
                        break;
                    case 'expenses_project_id':
                        $map[] = $expenses->project_id;
                        break;
                    case 'expenses_invoiced':
                        $map[] = ($expenses->expense_billing_status == 'invoiced') ? __('lang.yes') : __('lang.no');
                        break;
                    case 'expenses_invoice_id':
                        $map[] = runtimeInvoiceIdFormat($expenses->expense_billable_invoiceid);
                        break;
                    default:
                        $map[] = $expenses->{$key};
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
                            $map[] = runtimeDate($expenses->{$key});
                            break;
                        case 'checkbox':
                            $map[] = ($expenses->{$key} == 'on') ? __('lang.checked_custom_fields') : '---';
                            break;
                        default:
                            $map[] = $expenses->{$key};
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
            'expense_date' => __('lang.date'),
            'expenses_user' => __('lang.user'),
            'expense_description' => __('lang.description'),
            'expense_amount' => __('lang.amount'),
            'expenses_client' => __('lang.client'),
            'expenses_client_id' => __('lang.client_id'),
            'expenses_project' => __('lang.project'),
            'expenses_project_id' => __('lang.project_id'),
            'expenses_invoiced' => __('lang.invoiced'),
            'expenses_invoice_id' => __('lang.invoice_id'),
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
