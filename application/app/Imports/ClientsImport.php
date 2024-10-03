<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ClientsImport implements ToModel, WithStartRow, WithHeadingRow, WithValidation, SkipsOnFailure {

    use Importable, SkipsFailures;

    private $rows = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row) {

        ++$this->rows;

        return new Client([
            'client_importid' => request('import_ref'),
            'client_company_name' => $row['companyname'] ?? '',
            'client_phone' => $row['phone'] ?? '',
            'client_website' => $row['Website'] ?? '',
            'client_billing_street' => $row['billingstreet'] ?? '',
            'client_billing_city' => $row['billingcity'] ?? '',
            'client_billing_state' => $row['billingstate'] ?? '',
            'client_billing_zip' => $row['billingzipcode'] ?? '',
            'client_billing_country' => $row['billingcountry'] ?? '',
            'client_shipping_street' => $row['shippingstreet'] ?? '',
            'client_shipping_city' => $row['shippingcity'] ?? '',
            'client_shipping_state' => $row['shippingstate'] ?? '',
            'client_shipping_zip' => $row['shippingzipcode'] ?? '',
            'client_shipping_country' => $row['shippingcountry'] ?? '',
            'client_custom_field_1' => $row['customfield1'] ?? '',
            'client_custom_field_2' => $row['customfield3'] ?? '',
            'client_custom_field_3' => $row['customfield3'] ?? '',
            'client_custom_field_4' => $row['customfield4'] ?? '',
            'client_custom_field_5' => $row['customfield5'] ?? '',
            'client_custom_field_6' => $row['customfield6'] ?? '',
            'client_custom_field_7' => $row['customfield7'] ?? '',
            'client_custom_field_8' => $row['customfield8'] ?? '',
            'client_custom_field_9' => $row['customfield9'] ?? '',
            'client_custom_field_10' => $row['customfield10'] ?? '',
            'client_custom_field_11' => $row['customfield11'] ?? '',
            'client_custom_field_12' => $row['customfield12'] ?? '',
            'client_custom_field_13' => $row['customfield13'] ?? '',
            'client_custom_field_14' => $row['customfield14'] ?? '',
            'client_custom_field_15' => $row['customfield15'] ?? '',
            'client_custom_field_16' => $row['customfield16'] ?? '',
            'client_custom_field_17' => $row['customfield17'] ?? '',
            'client_custom_field_18' => $row['customfield18'] ?? '',
            'client_custom_field_19' => $row['customfield19'] ?? '',
            'client_custom_field_20' => $row['customfield20'] ?? '',
            'client_custom_field_21' => $row['customfield21'] ?? '',
            'client_custom_field_22' => $row['customfield22'] ?? '',
            'client_custom_field_23' => $row['customfield23'] ?? '',
            'client_custom_field_24' => $row['customfield24'] ?? '',
            'client_custom_field_25' => $row['customfield25'] ?? '',
            'client_custom_field_26' => $row['customfield26'] ?? '',
            'client_custom_field_27' => $row['customfield27'] ?? '',
            'client_custom_field_28' => $row['customfield28'] ?? '',
            'client_custom_field_29' => $row['customfield29'] ?? '',
            'client_custom_field_30' => $row['customfield30'] ?? '',
            'client_custom_field_31' => $row['customfield31'] ?? '',
            'client_custom_field_32' => $row['customfield32'] ?? '',
            'client_custom_field_33' => $row['customfield33'] ?? '',
            'client_custom_field_34' => $row['customfield34'] ?? '',
            'client_custom_field_35' => $row['customfield35'] ?? '',
            'client_custom_field_36' => $row['customfield36'] ?? '',
            'client_custom_field_37' => $row['customfield37'] ?? '',
            'client_custom_field_38' => $row['customfield38'] ?? '',
            'client_custom_field_39' => $row['customfield39'] ?? '',
            'client_custom_field_40' => $row['customfield40'] ?? '',
            'client_custom_field_41' => $row['customfield41'] ?? '',
            'client_custom_field_42' => $row['customfield42'] ?? '',
            'client_custom_field_43' => $row['customfield43'] ?? '',
            'client_custom_field_44' => $row['customfield44'] ?? '',
            'client_custom_field_45' => $row['customfield45'] ?? '',
            'client_custom_field_46' => $row['customfield46'] ?? '',
            'client_custom_field_47' => $row['customfield47'] ?? '',
            'client_custom_field_48' => $row['customfield48'] ?? '',
            'client_custom_field_49' => $row['customfield49'] ?? '',
            'client_custom_field_50' => $row['customfield50'] ?? '',
            'client_custom_field_51' => $row['customfield51'] ?? '',
            'client_custom_field_52' => $row['customfield52'] ?? '',
            'client_custom_field_53' => $row['customfield53'] ?? '',
            'client_custom_field_54' => $row['customfield54'] ?? '',
            'client_custom_field_55' => $row['customfield55'] ?? '',
            'client_custom_field_56' => $row['customfield56'] ?? '',
            'client_custom_field_57' => $row['customfield57'] ?? '',
            'client_custom_field_58' => $row['customfield58'] ?? '',
            'client_custom_field_59' => $row['customfield59'] ?? '',
            'client_custom_field_60' => $row['customfield60'] ?? '',
            'client_custom_field_61' => $row['customfield61'] ?? '',
            'client_custom_field_62' => $row['customfield62'] ?? '',
            'client_custom_field_63' => $row['customfield63'] ?? '',
            'client_custom_field_64' => $row['customfield64'] ?? '',
            'client_custom_field_65' => $row['customfield65'] ?? '',
            'client_custom_field_66' => $row['customfield66'] ?? '',
            'client_custom_field_67' => $row['customfield67'] ?? '',
            'client_custom_field_68' => $row['customfield68'] ?? '',
            'client_custom_field_69' => $row['customfield69'] ?? '',
            'client_custom_field_70' => $row['customfield70'] ?? '',
            'client_import_first_name' => $row['firstname'] ?? '',
            'client_import_last_name' => $row['lastname'] ?? '',
            'client_import_email' => $row['email'] ?? '',
            'client_import_job_title' => $row['jobtitle'] ?? '',
            'client_creatorid' => auth()->id(),
            'client_created' => now(),
            'client_status' => 'active',
        ]);
    }

    public function rules(): array
    {
        return [
            'companyname' => [
                'required',
            ],
            'firstname' => [
                'required',
            ],
            'lastname' => [
                'required',
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],
        ];
    }

    /**
     * we are ignoring the header and so we will start with row number (2)
     * @return int
     */
    public function startRow(): int {
        return 2;
    }

    /**
     * lets count the total imported rows
     * @return int
     */
    public function getRowCount(): int {
        return $this->rows;
    }
}
