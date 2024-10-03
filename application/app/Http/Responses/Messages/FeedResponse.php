<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the project
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Messages;
use Illuminate\Contracts\Support\Responsable;

class FeedResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for project members
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //default
        $jsondata = [];

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        /** -------------------------------------------------------------------------
         * update time stamps - only if there were new messages
         * do not do this when we are autoloading feeds
         * -------------------------------------------------------------------------*/
        if (!request()->filled('source') || request('source') != 'feed') {
            if (count($messages) > 0 || request('message_load') == 'initial') {
                $jsondata['dom_val'][] = [
                    'selector' => '.tracking_timestamp',
                    'value' => $timestamp,
                ];
            }
        }

        /** -------------------------------------------------------------------------
         * feed content
         * -------------------------------------------------------------------------*/
        if (count($messages) > 0) {

            //reverse the order of the messages (but not for autoload feed)
            if (request('source') == 'feed') {
                $preprared_messages = $messages;
            } else {
                $preprared_messages = $messages->reverse();
            }

            //append or prepend (prepend is when we are autoloading at the top of the chat box)
            $append_prepend = (request('source') == 'feed') ? 'prepend' : 'append';

            //create an individual feed (sanity: so that we can use the JS frontend to avoid duplicates)
            foreach ($preprared_messages as $message) {
                $html = view('pages/messages/ajax', compact('message'))->render();
                $jsondata['dom_html'][] = [
                    'selector' => '#chat-messages-container',
                    'action' => $append_prepend,
                    'avoid_duplicates' => true,
                    'avoid_duplicates_id' => '#' . messageUniqueID($message->message_unique_id),
                    'value' => $html,
                ];
            }
        }

        /** -------------------------------------------------------------------------
         * update unread messages counters
         * -------------------------------------------------------------------------*/
        //reset all first
        $jsondata['dom_visibility'][] = [
            'selector' => ".messages_counter",
            'action' => 'hide',
        ];
        foreach ($message_counters as $message_count) {

            $count = $message_count->counter;
            $target = messagesCounterUniqueID($message_count->messagestracking_target);
            //show counters
            if ($count > 0) {
                $jsondata['dom_visibility'][] = [
                    'selector' => "#$target",
                    'action' => 'show',
                ];
                $jsondata['dom_html'][] = [
                    'selector' => "#$target",
                    'action' => 'replace',
                    'value' => $count,
                ];
            }
        }

        //topnav notification
        if (count($message_counters) > 0) {
            $jsondata['dom_visibility'][] = [
                'selector' => "#topnav-messages-notification-icon",
                'action' => 'show',
            ];
        } else {
            $jsondata['dom_visibility'][] = [
                'selector' => "#topnav-messages-notification-icon",
                'action' => 'hide',
            ];
        }

        /** -------------------------------------------------------------------------
         * remove deleted messages
         * -------------------------------------------------------------------------*/
        foreach ($deleted_messages as $deleted) {
            $jsondata['dom_visibility'][] = [
                'selector' => "#" . messageUniqueID($deleted),
                'action' => 'hide-remove',
            ];
        }

        /** -------------------------------------------------------------------------
         * display files and images
         * -------------------------------------------------------------------------*/
        if ($feed_source == 'posting-files') {
            $jsondata['dom_visibility'][] = [
                'selector' => '#messages_file_upload_wrapper',
                'action' => 'hide',
            ];
            $jsondata['dom_visibility'][] = [
                'selector' => '#messages_right_text_wrapper',
                'action' => 'show',
            ];
        }

        /** -------------------------------------------------------------------------
         * update each users online status
         * -------------------------------------------------------------------------*/
        //reset all users
        $jsondata['dom_classes'][] = [
            'selector' => ".messages_user_status",
            'action' => 'remove',
            'value' => 'text-success',
        ];
        $jsondata['dom_classes'][] = [
            'selector' => ".messages_user_status",
            'action' => 'remove',
            'value' => 'text-muted',
        ];
        foreach ($user_status as $id => $status) {

            if ($status) {
                //online
                $jsondata['dom_classes'][] = [
                    'selector' => "#user_status_$id",
                    'action' => 'add',
                    'value' => 'text-success',
                ];
                $jsondata['dom_html'][] = [
                    'selector' => "#user_status_lang_$id",
                    'action' => 'replace',
                    'value' => __('lang.online'),
                ];
            } else {
                //online
                $jsondata['dom_classes'][] = [
                    'selector' => "#user_status_$id",
                    'action' => 'add',
                    'value' => 'text-muted',
                ];
                $jsondata['dom_html'][] = [
                    'selector' => "#user_status_lang_$id",
                    'action' => 'replace',
                    'value' => __('lang.offline'),
                ];
            }
        }
        /** -------------------------------------------------------------------------
         * autoload
         * -------------------------------------------------------------------------*/
        if (request('action') == 'load') {
            if ($messages->currentPage() < $messages->lastPage()) {
                $url = loadMoreButtonUrl($messages->currentPage() + 1, 'feed');
                $jsondata['dom_attributes'][] = array(
                    'selector' => '#feed_container',
                    'attr' => 'data-url',
                    'value' => $url);
                $jsondata['dom_attributes'][] = array(
                    'selector' => '#feed_container',
                    'attr' => 'data-autoload',
                    'value' => 'yes');
            } else {
                $jsondata['dom_attributes'][] = array(
                    'selector' => '#feed_container',
                    'attr' => 'data-autoload',
                    'value' => 'no');
                $jsondata['dom_attributes'][] = array(
                    'selector' => '#feed_container',
                    'attr' => 'data-url',
                    'value' => '');
            }
        }

        //ajax response
        return response()->json($jsondata);
    }

}