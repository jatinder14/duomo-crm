<!--item-->
<div class="form-group row">
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm select2-preselected" id="ticket_status"
            name="ticket_status" data-preselected="1">
            @foreach($statuses as $status)
            <option value="{{ $status->ticketstatus_id }}">{{ $status->ticketstatus_title }}</option>
            @endforeach
        </select>
    </div>
</div>

<!--form buttons-->
<div class="text-right p-t-30">
    <button type="submit" id="submitButton" class="btn btn-danger waves-effect text-left ajax-request" 
        data-url="{{ url('tickets/change-status') }}"
        data-form-id="main-body"
        data-ajax-type="POST" 
        data-on-start-submit-button="disable">@lang('lang.submit')</button>
</div>