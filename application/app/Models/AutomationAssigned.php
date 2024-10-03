<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationAssigned extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'automation_assigned';
    protected $primaryKey = 'fooo_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['automationassigned_id'];
    const CREATED_AT = 'automationassigned_created';
    const UPDATED_AT = 'automationassigned_updated';

}
