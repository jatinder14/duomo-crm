<?php

/** --------------------------------------------------------------------------------
 * This middleware class validates input requests for the estimates controller
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Requests\Projects;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateAutomation extends FormRequest {

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
        //custom error messages
        return [
            'project_automation_invoice_due_date.required_if' => __('lang.automation_invoice_due_date') . ' - ' . __('lang.is_required'),
        ];
    }

    /**
     * Validate the request
     * @return array
     */
    public function rules() {

        $rules = [];

        if (request('project_automation_status') == 'enabled') {
            /**-------------------------------------------------------
             * validation rules
             * ------------------------------------------------------*/
            $rules = [
                'project_automation_invoice_due_date' => [
                    'required_if:automation_create_invoices,on',
                ],
                //select atleast one automation option
                'project_automation_status' => [
                    'nullable',
                    function ($attribute, $value, $fail) {
                        $selected = 0;
                        if (request('project_automation_create_invoices') == 'on') {
                            $selected++;
                        }
                        //check
                        if ($selected == 0) {
                            return $fail(__('lang.no_automation_options_selected'));
                        }
                    },
                ],
                //select atleast one automation option
                'project_automation_create_invoices' => [
                    'nullable',
                    function ($attribute, $value, $fail) {
                        if (request('project_automation_create_invoices') == 'on') {
                            $selected = 0;
                            if (request('project_automation_convert_estimates_to_invoices') == 'on') {
                                $selected++;
                            }
                            if (request('project_automation_invoice_unbilled_hours') == 'on') {
                                $selected++;
                            }
                            //check
                            if ($selected == 0) {
                                return $fail(__('lang.select_atleast_one_invoice_creation_option'));
                            }
                        }
                    },
                ],
            ];
        }
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
