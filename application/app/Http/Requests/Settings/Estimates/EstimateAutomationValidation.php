<?php

/** --------------------------------------------------------------------------------
 * This middleware class validates input requests for the template controller
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Requests\Settings\Estimates;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class EstimateAutomationValidation extends FormRequest {

    //use App\Http\Requests\Foo\TemplateValidation;
    //function update(TemplateValidation $request,

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
            'settings2_estimates_automation_project_title.required_if' => __('lang.project_title') . ' - ' . __('lang.is_required'),
            'settings2_estimates_automation_invoice_due_date.required_if' => __('lang.automation_invoice_due_date') . ' - ' . __('lang.is_required'),
        ];
    }

    /**
     * Validate the request
     * @return array
     */
    public function rules() {

        /**-------------------------------------------------------
         * common rules for both [create] and [update] requests
         * ------------------------------------------------------*/
        $rules = [
            'settings2_estimates_automation_project_title' => [
                'required_if:settings2_estimates_automation_create_project,on',
            ],
            'settings2_estimates_automation_invoice_due_date' => [
                'required_if:settings2_estimates_automation_create_invoice,on',
            ],
            //select atleast one automation option
            'settings2_estimates_automation_default_status' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (request('settings2_estimates_automation_default_status') == 'enabled') {
                        $selected = 0;
                        if (request('settings2_estimates_automation_create_project') == 'on') {
                            $selected++;
                        }
                        if (request('settings2_estimates_automation_create_invoice') == 'on') {
                            $selected++;
                        }
                        //check
                        if ($selected == 0) {
                            return $fail(__('lang.no_automation_options_selected'));
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
