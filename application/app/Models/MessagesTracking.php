<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessagesTracking extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'messages_tracking';
    protected $primaryKey = 'messagestracking_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['foo_id'];
    const CREATED_AT = 'messagestracking_created';
    const UPDATED_AT = 'messagestracking_update';

}