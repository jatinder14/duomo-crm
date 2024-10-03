<!--folders edit view-->
<div class="folders-edit-view p-t-10" id="folders-edit-view">

    <!--item-->
    @foreach($folders as $folder)
    <div class="form-group row" id="filefilefolder_id_{{ $folder->filefolder_id }}">
        <div class="col-12 each-folder">
            @if($folder->filefolder_default == 'yes')
            <input type="text" class="form-control form-control-sm" id="filefolder_name"
                name="filefolder_name[{{ $folder->filefolder_id }}]" value="{{ $folder->filefolder_name }}" disabled>
            <a href="javascript:void(0);" class="delete-button text-default"
                title="@lang('lang.system_default_folder_cannot_be_deleted')" data-toggle="tooltip">
                <i class="sl-icon-trash"></i>
            </a>
            @else
            <input type="text" class="form-control form-control-sm" id="filefolder_name"
                name="filefolder_name[{{ $folder->filefolder_id }}]" value="{{ $folder->filefolder_name }}">
            <a href="javascript:void(0);" class="delete-button text-danger confirm-action-danger"
                title="@lang('lang.delete')" data-confirm-title="@lang('lang.delete_folder')" data-confirm-text=""
                data-confirm-checkbox="yes" data-confirm-checkbox-label="@lang('lang.delete_all_files_in_folder')"
                data-confirm-checkbox-field-id="filefolder_{{ $folder->filefolder_id }}" data-ajax-type="DELETE"
                data-type="form" data-form-id="filefilefolder_id_{{ $folder->filefolder_id }}" data-ajax-type="post"
                data-url="{{ urlResource('/files/folders/'.$folder->filefolder_id.'/delete') }}">
                <i class="sl-icon-trash"></i>
            </a>
            <input type="hidden" class="confirm_hidden_fields" name="filefolder_{{ $folder->filefolder_id }}"
                id="filefolder_{{ $folder->filefolder_id }}">
            @endif
        </div>
    </div>
    @endforeach


    <!--form buttons-->
    <div class="text-right">
        <button type="submit" id="folders-add-button-submit"
            class="btn btn-default btn-xs waves-effect text-left ajax-request"
            data-url="{{ urlResource('/files/folders/show') }}" data-type="form" data-form-id="folders-add-view"
            data-ajax-type="get" data-button-loading-annimation="yes"
            data-on-start-submit-button="disable">@lang('lang.cancel')</button>
        <button type="submit" id="folders-edit-button-submit"
            class="btn btn-danger btn-xs waves-effect text-left ajax-request"
            data-url="{{ urlResource('/files/folders/update') }}" data-type="form" data-form-id="folders-edit-view"
            data-ajax-type="post" data-loading-target="folders-body"
            data-on-start-submit-button="disable">@lang('lang.submit')</button>
    </div>

</div>