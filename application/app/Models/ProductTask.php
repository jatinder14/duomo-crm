<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTask extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'product_tasks';
    protected $primaryKey = 'product_task_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['product_task_id'];
    const CREATED_AT = 'product_task_created';
    const UPDATED_AT = 'product_task_updated';

}