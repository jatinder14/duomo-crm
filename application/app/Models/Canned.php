<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canned extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'canned';
    protected $primaryKey = 'canned_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['canned_id'];
    const CREATED_AT = 'canned_created';
    const UPDATED_AT = 'canned_updated';

    /**
     * relatioship rules:
     */
    public function category() {
        return $this->belongsTo('App\Models\Category', 'canned_categoryid', 'category_id');
    }
    
}
