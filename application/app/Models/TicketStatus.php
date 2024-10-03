<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */
    protected $table = 'tickets_status';
    protected $primaryKey = 'ticketstatus_id';
    protected $guarded = ['ticketstatus_id'];
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'ticketstatus_created';
    const UPDATED_AT = 'ticketstatus_updated';

    /**
     * relatioship business rules:
     *         - the Ticket Status can have many Tickets
     *         - the Ticket belongs to one Ticket Status
     */
    public function tickets() {
        return $this->hasMany('App\Models\Ticket', 'ticket_status', 'ticketstatus_id');
    }

}
