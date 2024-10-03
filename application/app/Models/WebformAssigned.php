<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebformAssigned extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */
    protected $table = 'webforms_assigned';
    protected $primaryKey = 'webformassigned_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['webformassigned_id'];
    const CREATED_AT = 'webformassigned_created';
    const UPDATED_AT = 'webformassigned_updated';
}
