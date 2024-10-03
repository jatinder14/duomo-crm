<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Space extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'spaces';
    protected $primaryKey = 'space_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['space_id'];
    const CREATED_AT = 'space_created';
    const UPDATED_AT = 'space_updated';

}
