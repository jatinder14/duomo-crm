<?php

namespace App\Exports;

use App\Repositories\CustomFieldsRepository;
use App\Repositories\TimerRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TimesheetsExport implements FromCollection, WithHeadings, WithMapping {

    /**
     * The timesheet repo repository
     */
    protected $timesheetrepo;

    /**
     * The custom repo repository
     */
    protected $customrepo;

    public function __construct(TimerRepository $timesheetrepo, CustomFieldsRepository $customrepo) {

        $this->timesheetrepo = $timesheetrepo;
        $this->customrepo = $customrepo;

    }

    //get the timesheets
    public function collection() {
        //search
        $timesheets = $this->timesheetrepo->search('', ['no_pagination' => true]);
        //return
        return $timesheets;
    }

    //map the columns that we want
    public function map($timesheets): array{

        $map = [];

        //standard fields - loop thorugh all post data
        if (is_array(request('standard_field'))) {
            foreach (request('standard_field') as $key => $value) {
                if ($value == 'on') {
                    switch ($key) {
                    case 'timesheet_user':
                        $map[] = (config('visibility.timesheets_grouped_by_users')) ? __('lang.multiple') : runtimeUser($timesheets->first_name, $timesheets->last_name);
                        break;
                    case 'timesheet_task':
                        $map[] = $timesheets->task_title;
                        break;
                    case 'timesheet_client':
                        $map[] = $timesheets->client_company_name;
                        break;
                    case 'timesheet_client_id':
                        $map[] = $timesheets->timer_clientid;
                        break;
                    case 'timesheet_project':
                        $map[] = $timesheets->task_title;
                        break;
                    case 'timesheet_project':
                        $map[] = $timesheets->project_title;
                        break;
                    case 'timesheet_project_id':
                        $map[] = $timesheets->timer_projectid;
                        break;
                    case 'timesheet_date':
                        $map[] = runtimeDate($timesheets->timer_created);
                        break;
                    case 'timesheet_time':
                        $map[] = runtimeSecondsWholeHours($timesheets->time).':'.runtimeSecondsWholeMinutesZero($timesheets->time);
                        break;
                    default:
                        $map[] = $timesheets->{$key};
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
                            $map[] = runtimeDate($timesheets->{$key});
                            break;
                        case 'checkbox':
                            $map[] = ($timesheets->{$key} == 'on') ? __('lang.checked_custom_fields') : '---';
                            break;
                        default:
                            $map[] = $timesheets->{$key};
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
            'timesheet_user' => __('lang.user'),
            'timesheet_task' => __('lang.task'),
            'timesheet_client' => __('lang.client'),
            'timesheet_client_id' => __('lang.client_id'),
            'timesheet_project' => __('lang.project'),
            'timesheet_project_id' => __('lang.project_id'),
            'timesheet_date' => __('lang.date'),
            'timesheet_time' => __('lang.time'),
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
