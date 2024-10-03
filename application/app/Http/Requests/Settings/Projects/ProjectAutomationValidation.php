<?php

/** --------------------------------------------------------------------------------
 * This middleware class validates input requests for the template controller
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Requests\Settings\Projects;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ProjectAutomationValidation extends FormRequest {

    /**
     * we are checking authorised users via the middleware
     * so just retun true here
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * custom error messages for specific valdation checks
     * @optional
     * @return array
     */
    public function messages() {
        return [
            'settings2_projects_automation_invoice_due_date.required_if' => __('lang.automation_invoice_due_date') . ' - ' . __('lang.is_required'),
            'settings2_project_automation_invoice_hourly_rate.required_if' => __('lang.default_hourly_rate') . ' - ' . __('lang.is_required'),
        ];
    }

    /**
     * Validate the request
     * @return array
     */
    public function rules() {

        /**-------------------------------------------------------
         * validation rules
         * ------------------------------------------------------*/
        $rules = [
            'settings2_projects_automation_invoice_due_date' => [
                'required_if:settings2_projects_automation_create_invoices,on',
            ],
            'settings2_project_automation_invoice_hourly_rate' => [
                'required_if:settings2_projects_automation_invoice_unbilled_hours,on',
            ],
            //select atleast one automation option
            'settings2_projects_automation_default_status' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (request('settings2_projects_automation_default_status') == 'enabled') {
                        $selected = 0;
                        if (request('settings2_projects_automation_create_invoices') == 'on') {
                            $selected++;
                        }
                        //check
                        if ($selected == 0) {
                            return $fail(__('lang.no_automation_options_selected'));
                        }
                    }
                },
                function ($attribute, $value, $fail) {
                    if (request('settings2_projects_automation_default_status') == 'enabled') {
                        if (request('settings2_projects_automation_create_invoices') == 'on') {
                            $selected = 0;
                            if (request('settings2_projects_automation_convert_estimates_to_invoices') == 'on') {
                                $selected++;
                            }
                            if (request('settings2_projects_automation_invoice_unbilled_hours') == 'on') {
                                $selected++;
                            }
                            //check
                            if ($selected == 0) {
                                return $fail(__('lang.select_atleast_one_invoice_creation_option'));
                            }
                        }
                    }
                },
            ],
        ];

        //validate
        return $rules;
    }

    /**
     * Deal with the errors - send messages to the frontend
     */
    public function failedValidation(Validator $validator) {

        $errors = $validator->errors();
        $messages = '';
        foreach ($errors->all() as $message) {
            $messages .= "<li>$message</li>";
        }

        abort(409, $messages);
    }
}
