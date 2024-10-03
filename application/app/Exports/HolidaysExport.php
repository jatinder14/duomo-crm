<?php

namespace App\Exports;

use App\Repositories\CustomFieldsRepository;
use App\Repositories\HolidayRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HolidaysExport implements FromCollection, WithHeadings, WithMapping {

    /**
     * The holiday repo repository
     */
    protected $holidayrepo;

    /**
     * The custom repo repository
     */
    protected $customrepo;

    public function __construct(HolidayRepository $holidayrepo, CustomFieldsRepository $customrepo) {

        $this->holidayrepo = $holidayrepo;
        $this->customrepo = $customrepo;

    }

    //get the holidays
    public function collection() {

        //search
        $holidays = $this->holidayrepo->search('', ['no_pagination' => true]);
        //return
        return $holidays;
    }

    //map the columns that we want
    public function map($holidays): array{

        $map = [];

        //standard fields - loop thorugh all post data
        if (is_array(request('standard_field'))) {
            foreach (request('standard_field') as $key => $value) {
                if ($value == 'on') {
                    switch ($key) {
                    case 'holiday_date':
                        $map[] = $holidays->holiday_date;
                        break;
                    case 'holiday_description':
                        $map[] = $holidays->holiday_description;
                        break;
                    default:
                        $map[] = $holidays->{$key};
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
                        case 'holiday_date':
                            $map[] = runtimeDate($holidays->{$key});
                            break;
                        case 'checkbox':
                            $map[] = ($holidays->{$key} == 'on') ? __('lang.checked_custom_fields') : '---';
                            break;
                        default:
                            $map[] = $holidays->{$key};
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
            'holiday_id' => __('lang.id'),
            'holiday_date' => __('lang.date'),
            'holiday_description' => __('lang.description'),
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
