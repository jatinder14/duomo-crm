<!--item-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">@lang('lang.move_to_this_folder')</label>
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm" id="moving_filefolder_id"
            name="moving_filefolder_id">
            @foreach($folders as $folder)
            <option value="{{ $folder->filefolder_id }}">{{ $folder->filefolder_name }}</option>
            @endforeach
        </select>
    </div>
</div>