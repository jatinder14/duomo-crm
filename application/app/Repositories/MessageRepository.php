<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @message    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Message;

class MessageRepository {

    /**
     * The leads repository instance.
     */
    protected $message;

    /**
     * Inject dependecies
     */
    public function __construct(Message $message) {
        $this->message = $message;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object messages collection
     */
    public function search($id = '', $data = []) {

        $messages = $this->message->newQuery();

        //joins
        $messages->leftJoin('users', 'users.id', '=', 'messages.message_creatorid');

        // all client fields
        $messages->selectRaw('*');

        //default where
        $messages->whereRaw("1 = 1");

        //filter
        if (isset($data['apply_filters']) && $data['apply_filters']) {

            //message_source
            if (isset($data['message_source']) && isset($data['message_target'])) {
                //do not do for team messages
                if ($data['message_target'] == 'team') {
                    $messages->where('message_target', 'team');
                }else{
                    $messages->whereIN('message_source', [$data['message_source'],$data['message_target']]);
                    $messages->whereIN('message_target', [$data['message_source'],$data['message_target']]);
                }
            }

            //message_timestamp
            if (isset($data['timestamp'])) {
                $messages->where('message_timestamp', '>', $data['timestamp']);
            }

        }

        //sorting
        $messages->orderBy('message_id', 'desc');

        // Get the results and return them.
        return $messages->paginate(config('system.settings_system_pagination_limits'));
    }
}