<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [index] precheck processes for tickets
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Tickets;

use App\Models\Ticket;
use Closure;
use Log;

class Index {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] tickets
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //validate module status
        if (!config('visibility.modules.tickets')) {
            abort(404, __('lang.the_requested_service_not_found'));
            return $next($request);
        }

        //apply any existing filters
        $this->saveFilter();

        //apply any existing filters
        $this->applyFilter();

        //various frontend and visibility settings
        $this->fronteEnd();

        //embedded request: limit by supplied resource data
        if (request()->filled('ticketresource_type') && request()->filled('ticketresource_id')) {
            //client tickets
            if (request('ticketresource_type') == 'client') {
                request()->merge([
                    'filter_ticket_clientid' => request('ticketresource_id'),
                ]);
            }
        }

        //client user permission
        if (auth()->user()->is_client) {
            return $next($request);
        }

        //admin user permission
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_tickets >= 1) {
                return $next($request);
            }
        }

        //client users
        if (auth()->user()->is_client) {

            //show all projects
            request()->merge([
                'filter_show_archived_tickets' => 'yes',
            ]);
            return $next($request);
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][tickets][index]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        /**
         * shorten resource_type and resource_id (for easy appending in blade templates - action url's)
         * [usage]
         *   replace the usual url('ticket/edit/etc') with urlResource('ticket/edit/etc'), in blade templated
         *   usually in the ajax.blade.php files (actions links)
         * */
        if (request('ticketresource_type') != '' || is_numeric(request('ticketresource_id'))) {
            request()->merge([
                'resource_query' => 'ref=list&ticketresource_type=' . request('ticketresource_type') . '&ticketresource_id=' . request('ticketresource_id'),
            ]);
        } else {
            request()->merge([
                'resource_query' => 'ref=list',
            ]);
        }

        //default show some table columns
        config([
            'visibility.tickets_col_client' => true,
            'visibility.tickets_col_id' => true,
            'visibility.tickets_col_activity' => true,
            'visibility.filter_panel_client' => true,
        ]);

        //permissions -viewing
        if (auth()->user()->role->role_tickets >= 1) {
            if (auth()->user()->is_team) {
                config([
                    //visibility
                    'visibility.list_page_actions_filter_button' => true,
                    'visibility.list_page_actions_search' => true,
                    'visibility.stats_toggle_button' => true,
                    'visibility.tickets_col_action' => true,
                ]);
            }
            if (auth()->user()->is_client) {
                config([
                    //visibility
                    'visibility.list_page_actions_search' => true,
                    'visibility.list_page_actions_add_button_link' => true,
                    'visibility.tickets_col_client' => false,
                ]);
            }
        }

        //permissions -adding
        if (auth()->user()->role->role_tickets >= 2) {
            config([
                //visibility
                'visibility.list_page_actions_add_button_link' => true,
                'visibility.action_buttons_edit' => true,
                'visibility.tickets_col_checkboxes' => true,
            ]);
            if (auth()->user()->is_client) {
                config([
                    'visibility.action_buttons_edit' => false,
                ]);
            }
        }

        //permissions -deleting
        if (auth()->user()->role->role_tickets >= 3) {
            config([
                //visibility
                'visibility.action_buttons_delete' => true,
                'visibility.tickets_col_checkboxes' => true,
            ]);
        }

        //columns visibility
        if (request('ticketresource_type') == 'client') {
            config([
                //visibility
                'visibility.tickets_col_client' => false,
                'visibility.filter_panel_client_project' => false,
                'visibility.tickets_col_id' => false,
                'visibility.tickets_col_activity' => false,
            ]);
        }

        //update 'archived tickets filter'
        if (request('toggle') == 'pref_filter_show_archived_tickets') {
            //toggle database settings
            auth()->user()->pref_filter_show_archived_tickets = (auth()->user()->pref_filter_show_archived_tickets == 'yes') ? 'no' : 'yes';
            auth()->user()->save();
        }

        /** ----------------------------------------------------------------------------------------------------------------
         * if we are running filtering query from the panel
         *    - show or hide archived items
         *    - reset the users preferences
         *    - (settings2_tickets_archive_button) is set to 'yes' in default/typical installation of the CRM
         * ---------------------------------------------------------------------------------------------------------------*/
        // filtering (1) if we are filtering from the panel - update the users archived tickets preference to match filter option
        if (request('search_type') == 'filter') {
            if (request('show_archive_tickets') == 'on') {
                request()->merge(['filter_show_archived_tickets' => 'yes']);
                //reset user's archive pref  state
                if (config('system.settings2_tickets_archive_button') == 'yes') {
                    auth()->user()->pref_filter_show_archived_tickets = 'yes';
                    auth()->user()->save();
                }
            } else {
                //reset user's archive pref  state
                if (config('system.settings2_tickets_archive_button') == 'yes') {
                    auth()->user()->pref_filter_show_archived_tickets = 'no';
                    auth()->user()->save();
                }
            }
        }

        /** ----------------------------------------------------------------------------------------------------------------
         * if we are not running filtering query from the panel
         *    - apply the users filtering preferences
         * ---------------------------------------------------------------------------------------------------------------*/
        if (request('search_type') != 'filter' || !request()->filled('search_type')) {
            if (auth()->user()->pref_filter_show_archived_tickets == 'yes') {
                request()->merge(['filter_show_archived_tickets' => 'yes']);
            }
        }

        //importing and exporting
        config([
            'visibility.list_page_actions_exporting' => (auth()->user()->role->role_content_import == 'yes') ? true : false,
        ]);

        //show toggle archived tickets button
        if (auth()->user()->is_team && config('system.settings2_tickets_archive_button') == 'yes') {
            config([
                'visibility.archived_tickets_toggle_button' => true,
            ]);
        }
    }

    /**
     * update users filter settings
     */
    public function saveFilter() {

        //only on filter search
        if (request('action') != 'search') {
            return;
        }

        // retrieve form data
        $form_data = request()->all();

        //update the users profile
        \App\Models\User::where('id', auth()->id())
            ->update(
                [
                    'remember_filters_tickets_status' => (request('filter_remember') == 'on') ? 'enabled' : 'disabled',
                    'remember_filters_tickets_payload' => (request('filter_remember') == 'on') ? json_encode($form_data) : '',
                ]
            );
    }

    /**
     * check if this user has a previously set filter
     */
    public function applyFilter() {

        //do not do this if user is already filtering or if the user has disabled filter remembering
        if (request('action') == 'search' || auth()->user()->remember_filters_tickets_status == 'disabled' || auth()->user()->remember_filters_tickets_payload == '') {
            return;
        }

        //add this in the namespace at the top - ( use Exception; )
        if ($form_data = json_decode(auth()->user()->remember_filters_tickets_payload)) {

            //pick only the fields starting fwith 'filter_'
            $filter_data = [];
            foreach ($form_data as $key => $value) {
                if (strpos($key, 'filter_') === 0) {
                    $filter_data[$key] = $value;
                }
            }

            //note
            request()->merge([
                'filtered_results' => true,
                'filtered_url' => 'tickets?action=search&filter_remember=',
            ]);

            //show archived tickets
            if (isset($form_data->show_archive_tickets) && $form_data->show_archive_tickets == 'on') {
                request()->merge(['filter_show_archived_tickets' => 'yes']);
            }

            //save to current request
            if (!empty($filter_data)) {
                request()->merge($filter_data);
                return;
            }

            //filters seem to be empty - reset users profile
            \App\Models\User::where('id', auth()->id())
                ->update(
                    [
                        'remember_filters_tickets_status' => 'disabled',
                        'remember_filters_tickets_payload' => '',
                    ]
                );

        }
    }

}
