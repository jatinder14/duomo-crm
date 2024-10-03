<!--each reply-->
@foreach($replies as $reply)
<div class="comment-widgets" id="ticket_reply_{{ $reply->ticketreply_id }}">
    <div class="d-flex flex-row comment-rowp-b-0">
        <div class="p-2"><span class="round"><img
                    src="{{ getUsersAvatar($reply->avatar_directory, $reply->avatar_filename)  }}" width="50"></span>
        </div>
        <div class="comment-text w-100">
            <h5 class="m-b-0">{{ $reply->first_name ?? runtimeUnkownUser() }}</h5>

            <div class="text-muted m-b-5"><small class="text-muted">
                    {{ runtimeDate($reply->ticketreply_created) }} -
                    ({{ runtimeDateAgo($reply->ticketreply_created) }})</small></div>

            <div id="ticket_reply_text_{{ $reply->ticketreply_id }}">
                {!! clean($reply->ticketreply_text) !!}
            </div>

            <div id="ticket_edit_reply_container_{{ $reply->ticketreply_id }}">
                <!--dynamic content-->
            </div>

            <!--action buttons [edit & delete]-->
            @if(permissionEditTicketReply($reply))
            <div class="text-right">
                <!--edit reply-->
                <small><a class="text-muted ajax-request"
                        data-loading-target="ticket_reply_text_{{ $reply->ticketreply_id }}" href="javascript:void(0);"
                        data-url="{{ url('tickets/'.$reply->ticketreply_id.'/edit-reply') }}">@lang('lang.edit')</a></small>
                |
                <!--delete reply-->
                <small><a class="text-muted confirm-action-danger" href="javascript:void(0);"
                        data-confirm-title="@lang('lang.delete_reply')" data-confirm-text="@lang('lang.are_you_sure')"
                        data-ajax-type="DELETE" data-url="{{ url('tickets/'.$reply->ticketreply_id.'/delete-reply') }}">@lang('lang.delete')</a></small>
            </div>
            @endif
        </div>
    </div>

    <!--ticket attachements-->
    @if($reply->attachments_count > 0)
    <div class="x-attachements">
        <!--attachments container-->
        <div class="row">
            <!--attachments-->
            @foreach($reply->attachments as $attachment)
            @include('pages.ticket.components.attachments')
            @endforeach
        </div>
    </div>
    @endif
</div>
@endforeach