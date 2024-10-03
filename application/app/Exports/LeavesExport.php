<?php

namespace App\Exports;

use App\Repositories\CustomFieldsRepository;
use App\Repositories\LeaveRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LeavesExport implements FromCollection, WithHeadings, WithMapping {

    /**
     * The leave repo repository
     */
    protected $leaverepo;

    /**
     * The custom repo repository
     */
    protected $customrepo;

    public function __construct(LeaveRepository $leaverepo, CustomFieldsRepository $customrepo) {

        $this->leaverepo = $leaverepo;
        $this->customrepo = $customrepo;

    }

    //get the leaves
    public function collection() {

        //search
        $leaves = $this->leaverepo->search('', ['no_pagination' => true]);
        //return
        return $leaves;
    }

    //map the columns that we want
    public function map($leaves): array{

        $map = [];

        //standard fields - loop thorugh all post data
        if (is_array(request('standard_field'))) {
            foreach (request('standard_field') as $key => $value) {
                if ($value == 'on') {
                    switch ($key) {
                    case 'leave_team_member':
                        $map[] = $leaves->creator->full_name;
                        break;
                    case 'leave_date':
                        $map[] = $leaves->leave_date_from == $leaves->leave_date_to ? $leaves->leave_date_from : $leaves->leave_date_from.' - '.$leaves->leave_date_to;
                        break;
                    case 'leave_category':
                        $map[] = $leaves->leavecategory->leavecategory_title;
                        break;
                    case 'leave_reason':
                        $map[] = $leaves->leave_reason;
                        break;
                    case 'leave_status':
                        $map[] = $leaves->leavestatus->leavestatus_title;
                        break;
                    case 'leave_comment':
                        $map[] = $leaves->leave_comment;
                        break;
                    case 'leave_created':
                        $map[] = $leaves->leave_created;
                        break;
                    default:
                        $map[] = $leaves->{$key};
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
                            $map[] = runtimeDate($leaves->{$key});
                            break;
                        case 'checkbox':
                            $map[] = ($leaves->{$key} == 'on') ? __('lang.checked_custom_fields') : '---';
                            break;
                        default:
                            $map[] = $leaves->{$key};
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
            'leave_id' => __('lang.id'),
            'leave_team_member' => __('lang.team_member'),
            'leave_date' => __('lang.date'),
            'leave_category' => __('lang.category'),
            'leave_reason' => __('lang.reason'),
            'leave_status' => __('lang.status'),
            'leave_comment' => __('lang.comment'),
            'leave_created' => __('lang.created'),
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
