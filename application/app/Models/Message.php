<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

     protected $primaryKey = 'message_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['message_id'];
    const CREATED_AT = 'message_created';
    const UPDATED_AT = 'message_updated';

}