<?php

namespace App\Exports;

use App\Repositories\CustomFieldsRepository;
use App\Repositories\TicketRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketsExport implements FromCollection, WithHeadings, WithMapping {

    /**
     * The ticket repo repository
     */
    protected $ticketrepo;

    /**
     * The custom repo repository
     */
    protected $customrepo;

    public function __construct(TicketRepository $ticketrepo, CustomFieldsRepository $customrepo) {

        $this->ticketrepo = $ticketrepo;
        $this->customrepo = $customrepo;

    }

    //get the tickets
    public function collection() {

        //search
        $tickets = $this->ticketrepo->search('', ['no_pagination' => true]);
        //return
        return $tickets;
    }

    //map the columns that we want
    public function map($tickets): array{

        $map = [];

        //standard fields - loop thorugh all post data
        if (is_array(request('standard_field'))) {
            foreach (request('standard_field') as $key => $value) {
                if ($value == 'on') {
                    switch ($key) {
                    case 'ticket_created':
                        $map[] = $tickets->ticket_created;
                        break;
                    case 'ticket_priority':
                        $map[] = runtimeLang($tickets->ticket_priority);
                        break;
                    case 'ticket_last_updated':
                        $map[] = runtimeDate($tickets->ticket_last_updated);
                        break;
                    case 'ticket_status':
                        $map[] = runtimeLang($tickets->ticket_status);
                        break;
                    case 'created_by_name':
                        $map[] = $tickets->first_name . ' ' . $tickets->last_name;
                        break;
                    case 'created_by_email':
                        $map[] = $tickets->email;
                        break;
                    case 'ticket_message':
                        $map[] = (config('system.settings_system_exporting_strip_html') == 'yes') ? strip_tags(html_entity_decode($tickets->ticket_message)) : html_entity_decode($tickets->ticket_message);
                        break;
                    default:
                        $map[] = $tickets->{$key};
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
                            $map[] = runtimeDate($tickets->{$key});
                            break;
                        case 'checkbox':
                            $map[] = ($tickets->{$key} == 'on') ? __('lang.checked_custom_fields') : '---';
                            break;
                        default:
                            $map[] = $tickets->{$key};
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
            'ticket_id' => __('lang.id'),
            'client_company_name' => __('lang.client_name'),
            'created_by_name' => __('lang.created_by_name'),
            'created_by_email' => __('lang.created_by_email'),
            'category_name' => __('lang.department'),
            'ticket_subject' => __('lang.subject'),
            'ticket_message' => __('lang.message'),
            'department' => __('lang.department'),
            'ticket_created' => __('lang.date_created'),
            'ticket_priority' => __('lang.priority'),
            'ticket_last_updated' => __('lang.latest_activity'),
            'ticket_status' => __('lang.status'),
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
