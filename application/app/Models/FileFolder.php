<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filefolder extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'file_folders';
    protected $primaryKey = 'filefolder_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['filefolder_id'];
    const CREATED_AT = 'filefolder_created';
    const UPDATED_AT = 'filefolder_updated';

}
