<?php

namespace App\Exports;

use App\Repositories\CustomFieldsRepository;
use App\Repositories\ProjectRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProjectsExport implements FromCollection, WithHeadings, WithMapping {

    /**
     * The project repo repository
     */
    protected $projectrepo;

    /**
     * The custom repo repository
     */
    protected $customrepo;

    public function __construct(ProjectRepository $projectrepo, CustomFieldsRepository $customrepo) {

        $this->projectrepo = $projectrepo;
        $this->customrepo = $customrepo;

    }

    //get the projects
    public function collection() {

        //search
        $projects = $this->projectrepo->search('', ['no_pagination' => true]);
        //return
        return $projects;
    }

    //map the columns that we want
    public function map($projects): array{

        $map = [];

        //standard fields - loop thorugh all post data
        if (is_array(request('standard_field'))) {
            foreach (request('standard_field') as $key => $value) {
                if ($value == 'on') {
                    switch ($key) {
                    case 'project_client_name':
                        $map[] = $projects->client_company_name;
                        break;
                    case 'project_created_by':
                        $map[] = $projects->first_name . ' ' . $projects->last_name;
                        break;
                    case 'project_category_name':
                        $map[] = $projects->category_name;
                        break;
                    case 'project_status':
                        $map[] = runtimeLang($projects->project_status);
                        break;
                    case 'project_billing_type':
                        $map[] = runtimeLang($projects->project_billing_type);
                        break;
                    case 'project_tasks_all':
                        $map[] = $projects->count_all_tasks;
                        break;
                    case 'project_tasks_due':
                        $map[] = $projects->count_pending_tasks;
                        break;
                    case 'project_tasks_completed':
                        $map[] = $projects->count_completed_tasks;
                        break;
                    case 'project_sum_invoices_all':
                        $map[] = runtimeMoneyFormat($projects->sum_invoices_all);
                        break;
                    case 'project_sum_invoices_due':
                        $map[] = runtimeMoneyFormat($projects->sum_invoices_due);
                        break;
                    case 'project_sum_invoices_overdue':
                        $map[] = runtimeMoneyFormat($projects->sum_invoices_overdue);
                        break;
                    case 'project_sum_invoices_paid':
                        $map[] = runtimeMoneyFormat($projects->sum_invoices_paid);
                        break;
                    case 'project_sum_payments':
                        $map[] = runtimeMoneyFormat($projects->sum_all_payments);
                        break;
                    case 'project_sum_expenses':
                        $map[] = runtimeMoneyFormat($projects->sum_expenses);
                        break;
                    case 'project_description':
                        $map[] = (config('system.settings_system_exporting_strip_html') == 'yes') ? strip_tags(html_entity_decode($projects->project_description)) : html_entity_decode($projects->project_description);
                        break;
                    default:
                        $map[] = $projects->{$key};
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
                            $map[] = runtimeDate($projects->{$key});
                            break;
                        case 'checkbox':
                            $map[] = ($projects->{$key} == 'on') ? __('lang.checked_custom_fields') : '---';
                            break;
                        default:
                            $map[] = $projects->{$key};
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
            'project_id' => __('lang.id'),
            'project_created' => __('lang.date_created'),
            'project_title' => __('lang.title'),
            'project_status' => __('lang.status'),
            'project_clientid' => __('lang.client_id'),
            'project_client_name' => __('lang.client_name'),
            'project_created_by' => __('lang.created_by'),
            'project_category_name' => __('lang.category'),
            'project_date_start' => __('lang.start_date'),
            'project_date_due' => __('lang.due_date'),
            'project_description' => __('lang.description'),
            'project_progress' => __('lang.progress'),
            'project_billing_type' => __('lang.billing_type'),
            'project_billing_estimated_hours' => __('lang.estimated_hours'),
            'project_billing_costs_estimate' => __('lang.estimated_cost'),
            'project_visibility' => __('lang.visibility'),
            'project_tasks_all' => __('lang.all_tasks'),
            'project_tasks_due' => __('lang.due_tasks'),
            'project_tasks_completed' => __('lang.completed_tasks'),
            'project_sum_invoices_all' => __('lang.all_invoices'),
            'project_sum_invoices_due' => __('lang.due_invoices'),
            'project_sum_invoices_overdue' => __('lang.overdue_invoices'),
            'project_sum_invoices_paid' => __('lang.paid_invoices'),
            'project_sum_payments' => __('lang.payments'),
            'project_sum_expenses' => __('lang.expenses'),
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
