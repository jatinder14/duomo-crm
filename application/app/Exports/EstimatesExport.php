<?php

namespace App\Exports;

use App\Repositories\EstimateRepository;
use App\Repositories\CustomFieldsRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EstimatesExport implements FromCollection, WithHeadings, WithMapping {

    /**
     * The estimate repo repository
     */
    protected $estimaterepo;

    /**
     * The custom repo repository
     */
    protected $customrepo;

    public function __construct(EstimateRepository $estimaterepo, CustomFieldsRepository $customrepo) {

        $this->estimaterepo = $estimaterepo;
        $this->customrepo = $customrepo;

    }

    //get the estimates
    public function collection() {

        //search
        $estimates = $this->estimaterepo->search('', ['no_pagination' => true]);
        //return
        return $estimates;
    }

    //map the columns that we want
    public function map($estimates): array{

        $map = [];

        //standard fields - loop thorugh all post data
        if (is_array(request('standard_field'))) {
            foreach (request('standard_field') as $key => $value) {
                if ($value == 'on') {
                    switch ($key) {
                    case 'bill_estimateid':
                        $map[] = runtimeEstimateIdFormat($estimates->bill_estimateid);
                        break;
                    case 'bill_date':
                        $map[] = runtimeDate($estimates->bill_date);
                        break;
                    case 'client_company_name':
                        $map[] = $estimates->client_company_name;
                        break;
                    case 'bill_clientid':
                        $map[] = $estimates->bill_clientid;
                        break;
                    case 'project_title':
                        $map[] = $estimates->project_title;
                        break;
                    case 'bill_projectid':
                        $map[] = $estimates->bill_projectid;
                        break;
                    case 'bill_subtotal':
                        $map[] = runtimeMoneyFormat($estimates->bill_subtotal);
                        break;
                    case 'bill_discount_type':
                        $map[] = runtimeLang($estimates->bill_discount_type);
                        break;
                    case 'bill_discount_percentage':
                        //$map[] = ($estimates->bill_discount_type == 'fixed' || $estimates->bill_discount_type == 'none') ? '---' : $estimates->bill_discount_percentage;
                        $map[] = $estimates->bill_discount_percentage;
                        break;
                    case 'bill_discount_amount':
                        $map[] = runtimeMoneyFormat($estimates->bill_discount_amount);
                        break;
                    case 'bill_amount_before_tax':
                        $map[] = runtimeMoneyFormat($estimates->bill_amount_before_tax);
                        break;
                    case 'bill_tax_total_amount':
                        $map[] = runtimeMoneyFormat($estimates->bill_tax_total_amount);
                        break;
                    case 'bill_adjustment_description':
                        $map[] = $estimates->bill_adjustment_description;
                        break;
                    case 'bill_adjustment_amount':
                        $map[] = runtimeMoneyFormat($estimates->bill_adjustment_amount);
                        break;
                    case 'bill_final_amount':
                        $map[] = runtimeMoneyFormat($estimates->bill_final_amount);
                        break;
                    case 'bill_viewed_by_client':
                        $map[] = runtimeLang($estimates->bill_viewed_by_client);
                        break;
                    case 'bill_status':
                        $map[] = runtimeLang($estimates->bill_status);
                        break;
                    default:
                        $map[] = $estimates->{$key};
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
                            $map[] = runtimeDate($estimates->{$key});
                            break;
                        case 'checkbox':
                            $map[] = ($estimates->{$key} == 'on') ? __('lang.checked_custom_fields') : '---';
                            break;
                        default:
                            $map[] = $estimates->{$key};
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
            'bill_estimateid' => __('lang.estimate_id'),
            'bill_date' => __('lang.estimate_date'),
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
            'bill_final_amount' => __('lang.estimate_total'),
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
