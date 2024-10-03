<!--users-->
<div class="form-group row">
    <label class="col-12 text-left control-label col-form-label required">@lang('lang.user')</label>
    <div class="col-12">
        <select class="select2-basic form-control form-control-sm select2-preselected" id="user_id"
            name="user_id">
            <option></option>
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
            @endforeach
        </select>
    </div>
</div>