<div class="row">
    <!--options panel-->
    @include('pages.ticket.components.panel')


    <div class="col-sm-12 col-lg-9">

        <!--body-->
        <div class="card-body card x-message p-t-0" id="ticket-body">
            <!--message-->
            <div class="x-body">
                <div class="d-flex m-b-20">
                    <div>
                        <img src="{{ getUsersAvatar($ticket->avatar_directory, $ticket->avatar_filename)  }}" alt="user"
                            width="40" class="img-circle" />
                    </div>
                    <div class="p-l-10">
                        <h5 class="m-b-0">{{ $ticket->first_name ?? runtimeUnkownUser() }}</h5>
                        <small class="text-muted">{{ runtimeDateAgo($ticket->ticket_created ) }}</small>
                    </div>
                </div>

                {!! clean($ticket->ticket_message) !!}

            </div>
            <!--ticket attachements-->
            @if($ticket->attachments_count > 0)
            <div class="x-attachements">
                <!--attachments container-->
                <div class="row">
                    <!--attachments-->
                    @foreach($ticket->attachments as $attachment)
                    @include('pages.ticket.components.attachments')
                    @endforeach
                </div>
            </div>
            @endif
        </div>


        <!--replies-->
        <div id="ticket-replies-container">
            @include('pages.ticket.components.replies')
        </div>

        <!--reply notice-->
        @if(config('visibility.ticket_replying_on_hold'))
        <div class="p-b-40">
            <div class="alert alert-danger" id="ticket_reply_onhold_notice">
                {{ cleanLang(__('lang.ticket_is_on_hold')) }}</div>
        </div>
        @endif

        <!--reply button (popup)-->
        @if(config('visibility.ticket_replying') && config('system.settings2_tickets_replying_interface') == 'popup')
        <div class="p-b-40">
            <div class="x-reply text-center" id="ticket_reply_button">
                <button type="button" class="btn btn-rounded-x btn-info edit-add-modal-button js-ajax-ux-request"
                    data-toggle="modal" data-url="{{ urlResource('/tickets/'.$ticket->ticket_id.'/reply') }}"
                    data-action-url="{{ urlResource('/tickets/'.$ticket->ticket_id.'/postreply') }}"
                    data-target="#commonModal" data-loading-target="commonModalBody" data-action-method="POST"
                    data-modal-title="{{ cleanLang(__('lang.reply_ticket')) }}">
                    {{ cleanLang(__('lang.reply_ticket')) }}</button>
            </div>
        </div>
        @endif


        <!--reply button (inline)-->
        @if(config('visibility.ticket_replying') && config('system.settings2_tickets_replying_interface') == 'inline')
        <div class="p-b-40" id="ticket_replay_button_inline_container">
            <div class="x-reply text-center">
                <button type="button" class="btn btn-rounded-x btn-info" id="ticket_replay_button_inline">
                    {{ cleanLang(__('lang.reply_ticket')) }}</button>
            </div>
        </div>
        @endif

        <!--replying container-->
        @if(config('visibility.ticket_replying') && config('system.settings2_tickets_replying_interface') == 'inline')
        <div id="ticket_reply_inline_form" class="hidden">
            @include('pages.ticket.components.modals.reply')
            <!--form buttons-->
            <div class="text-right p-t-30">
                <button type="button" class="btn btn-danger waves-effect text-left" id="ticket_reply_button_close"
                    data-dismiss="modal">@lang('lang.cancel')</button>
                <button type="submit" id="ticket_reply_button_submit"
                    class="btn btn-info waves-effect text-left js-ajax-ux-request"
                    data-url="{{ url('tickets/'.$ticket->ticket_id.'/postreply?view=inline') }}" data-type="form" data-form-id="ticket_reply_inline_form"
                    data-ajax-type="post" data-loading-target="main-body"
                    data-on-start-submit-button="disable">@lang('lang.submit')</button>
            </div>
        </div>
        @endif


    </div>

</div>