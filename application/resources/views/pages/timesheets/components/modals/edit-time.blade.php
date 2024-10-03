<div class="form-group row">
    <div class="col-4">
        @lang('lang.hours')
        <input type="number" class="form-control form-control-sm"
            name="manual_time_hours" id="manual_time_hours"
            value="{{ hourMinuteSeconds($time->timer_time ?? 0, 'hours') }}">
    </div>
    <div class="col-4">
        @lang('lang.minutes')
        <input type="number" class="form-control form-control-sm"
            name="manual_time_minutes" id="manual_time_minutes"
            value="{{ hourMinuteSeconds($time->timer_time ?? 0, 'minutes') }}">
    </div>
    <div class="col-4">
        @lang('lang.seconds')
        <input type="number" class="form-control form-control-sm"
            name="manual_time_seconds" id="manual_time_seconds"
            value="{{ hourMinuteSeconds($time->timer_time ?? 0, 'seconds') }}">
    </div>
</div>