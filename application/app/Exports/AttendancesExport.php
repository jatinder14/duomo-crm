<?php

namespace App\Exports;

use App\Repositories\CustomFieldsRepository;
use App\Repositories\AttendanceRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendancesExport implements FromCollection, WithHeadings, WithMapping {

    /**
     * The attendance repo repository
     */
    protected $attendancerepo;

    /**
     * The custom repo repository
     */
    protected $customrepo;

    public function __construct(AttendanceRepository $attendancerepo, CustomFieldsRepository $customrepo) {

        $this->attendancerepo = $attendancerepo;
        $this->customrepo = $customrepo;

    }

    //get the attendances
    public function collection() {

        //search
        $attendances = $this->attendancerepo->search('', ['no_pagination' => true]);
        //return
        return $attendances;
    }

    //map the columns that we want
    public function map($attendances): array{

        $map = [];

        //standard fields - loop thorugh all post data
        if (is_array(request('standard_field'))) {
            foreach (request('standard_field') as $key => $value) {
                if ($value == 'on') {
                    switch ($key) {
                    case 'attendance_team_member':
                        $map[] = $attendances->user->full_name;
                        break;
                    case 'attendance_date':
                        $map[] = $attendances->date;
                        break;
                    case 'attendance_in_time':
                        $map[] = $attendances->in_time;
                        break;
                    case 'attendance_out_time':
                        $map[] = $attendances->out_time;
                        break;
                    case 'attendance_reason':
                        $map[] = $attendances->reason;
                        break;
                    case 'attendance_status':
                        $map[] = $attendances->attendancestatus->attendancestatus_title;
                        break;
                    default:
                        $map[] = $attendances->{$key};
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
                            $map[] = runtimeDate($attendances->{$key});
                            break;
                        case 'checkbox':
                            $map[] = ($attendances->{$key} == 'on') ? __('lang.checked_custom_fields') : '---';
                            break;
                        default:
                            $map[] = $attendances->{$key};
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
            'attendance_id' => __('lang.id'),
            'attendance_team_member' => __('lang.team_member'),
            'attendance_date' => __('lang.date'),
            'attendance_in_time' => __('lang.in_time'),
            'attendance_out_time' => __('lang.out_time'),
            'attendance_reason' => __('lang.reason'),
            'attendance_status' => __('lang.status'),
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
