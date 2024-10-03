<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */
    protected $primaryKey = 'holiday_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['holiday_id'];
    const CREATED_AT = 'holiday_created';
    const UPDATED_AT = 'holiday_updated';

    /**
     * relatioship business rules:
     *         - the Creator (user) can have many Holidays
     *         - the Holiday belongs to one Creator (user)
     */
    public function creator() {
        return $this->belongsTo('App\Models\User', 'holiday_creatorid', 'id');
    }
}
