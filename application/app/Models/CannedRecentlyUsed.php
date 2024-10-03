<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CannedRecentlyUsed extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'canned_recently_used';
    protected $primaryKey = 'cannedrecent_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['cannedrecent_id'];
    const CREATED_AT = 'cannedrecent_created';
    const UPDATED_AT = 'cannedrecent_updated';

}
