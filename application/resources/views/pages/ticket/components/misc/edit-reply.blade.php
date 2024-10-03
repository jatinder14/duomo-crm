 <div id="ticket_edit_reply_container_{{ $reply->ticketreply_id }}">

    <!--text editor-->
     <div class="form-group row">
         <div class="col-sm-12">
             <textarea class="form-control form-control-sm tinymce-textarea" rows="5"
                 name="ticketreply_text">
                 {!! $reply->ticketreply_text ?? '' !!}
                </textarea>
         </div>
     </div>

     <!--action buttons-->
     <div class="text-right p-t-0 p-b-30">
         <button type="button" class="btn btn-default btn-sm waves-effect text-left" id="ticket_edit_reply_cancel_buton"
             data-edit-reply-container="ticket_edit_reply_container_{{ $reply->ticketreply_id }}"
             data-reply-text-container="ticket_reply_text_{{ $reply->ticketreply_id }}">@lang('lang.cancel')</button>
         <button type="submit" id="ticket_reply_button_submit"
             class="btn btn-info btn-sm waves-effect text-left js-ajax-ux-request"
             data-url="{{ url('tickets/'.$reply->ticketreply_id.'/edit-reply') }}" data-type="form"
             data-form-id="ticket_edit_reply_container_{{ $reply->ticketreply_id }}" data-ajax-type="post" data-loading-target="main-body"
             data-on-start-submit-button="disable">@lang('lang.submit')</button>
     </div>
 </div>