<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for taxes
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Tax;
use Illuminate\Http\Request;
use Log;

class TaxRepository {

    /**
     * The tax repository instance.
     */
    protected $tax;

    /**
     * Inject dependecies
     */
    public function __construct(Tax $tax) {
        $this->tax = $tax;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object tax collection
     */
    public function search() {

        $tax = $this->tax->newQuery();

        //joins
        $tax->leftJoin('taxrates', 'taxrates.taxrate_id', '=', 'tax.tax_taxrateid');

        // all client fields
        $tax->selectRaw('*');

        //filter resources
        if (request()->filled('taxresource_type')) {
            $tax->where('taxresource_type', request('taxresource_type'));
        }
        if (request()->filled('taxresource_id')) {
            $tax->where('taxresource_id', request('taxresource_id'));
        }

        //default sorting
        $tax->orderBy('tax_name', 'asc');

        // Get the results and return them.
        return $tax->get();
    }

    /**
     * Create a new record
     * @param array $data payload data
     * @return mixed int|bool
     */
    public function create($data = []) {

        //save new user
        $tax = new $this->tax;

        //data
        $tax->tax_taxrateid = $data['tax_taxrateid'];
        $tax->tax_name = $data['tax_name'];
        $tax->tax_rate = $data['tax_rate'];
        $tax->taxresource_type = $data['taxresource_type'];
        $tax->taxresource_id = $data['taxresource_id'];

        //save and return id
        if ($tax->save()) {
            return $tax->tax_id;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[TaxRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * Delete existing tax lines and save new tax lines for a line item
     * @param string $taxresource_type estimate or invoice
     * @param string $taxresource_id estimate id or invoice id
     * @param int $lineitem_id payload data
     * @param string $tax_payload payload data coming from frontend
     * @return mixed int|bool
     */
    public function saveLineTaxes($taxresource_type = '', $taxresource_id = '', $lineitem_id = '', $tax_payload = '') {

        //validate
        if (!is_numeric($lineitem_id)) {
            Log::error("required lineitem id was not povided", ['process' => '[TaxRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //validate
        if ($taxresource_type == '') {
            Log::error("required taxresource_type was not povided", ['process' => '[TaxRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //validate
        if (!is_numeric($taxresource_id)) {
            Log::error("required taxresource_id was not povided", ['process' => '[TaxRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //validate
        if ($tax_payload == '') {
            Log::error("expected lineitem tax data is missing", ['process' => '[TaxRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //get the various parts of the tax data
        $data = explode('|', $tax_payload);

        //tax rate
        $tax_rate = $data[0];
        $tax_name = $data[1];
        $tax_uniqueid = $data[2];
        $tax_taxrateid = $data[3];

        //delete all taxes for this line item
        \App\Models\Tax::Where('taxresource_type', 'lineitem')->Where('taxresource_id', $lineitem_id)->delete();

        //save new tax
        $tax = new $this->tax;
        $tax->tax_taxrateid = $tax_taxrateid;
        $tax->tax_name = $tax_name;
        $tax->tax_rate = $tax_rate;
        $tax->tax_type = 'inline';
        $tax->tax_lineitem_id = $lineitem_id;
        $tax->taxresource_type = $taxresource_type;
        $tax->taxresource_id = $taxresource_id;
        $tax->save();
        
    }

}