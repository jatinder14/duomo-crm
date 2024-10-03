<div class="form-group row">
    <label for="example-month-input" class="col-12 col-form-label text-left">{{ cleanLang(__('lang.move_to_this_priority')) }}</label>
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm" id="tasks_priority" name="tasks_priority">
            @foreach($priorities as $priority)
            <option value="{{ $priority->taskpriority_id }}">{{ $priority->taskpriority_title }}</option>
            @endforeach
        </select>
    </div>
</div>