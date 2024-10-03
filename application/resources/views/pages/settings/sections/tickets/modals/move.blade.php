<div class="form-group row">
    <label for="example-month-input" class="col-12 col-form-label text-left">{{ cleanLang(__('lang.move_to_this_stage')) }}</label>
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm" id="tickets_status" name="tickets_status">
            @foreach($statuses as $status)
            <option value="{{ $status->ticketstatus_id }}">{{ $status->ticketstatus_title }}</option>
            @endforeach
        </select>
    </div>
</div>