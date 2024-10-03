<div class="form-group row m-b-50 m-t-20">
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm select2-preselected" id="status" name="status"
            data-preselected="1">
            <!--statuses-->
            @foreach($statuses as $status)
            <option value="{{ $status->leadstatus_id }}">
                {{ $status->leadstatus_title }}</option>
            @endforeach
        </select>

    </div>
</div>
