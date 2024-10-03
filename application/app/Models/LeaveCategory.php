<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveCategory extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */
    protected $table = 'leaves_categories';
    protected $primaryKey = 'leavecategory_id';
    protected $guarded = ['leavecategory_id'];
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'leavecategory_created';
    const UPDATED_AT = 'leavecategory_updated';

    /**
     * relatioship business rules:
     *         - the Leave Category can have many Leaves
     *         - the Leave belongs to one Leave Category
     */
    public function leaves() {
        return $this->hasMany('App\Models\Leave', 'leave_categoryid', 'leavecategory_id');
    }

}
