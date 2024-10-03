<!--fileupload-->
<div class="form-group row">
    <div class="col-12">
        <div class="dropzone dz-clickable" id="fileupload_files">
            <div class="dz-default dz-message">
                <i class="icon-Upload-toCloud"></i>
                <span>{{ cleanLang(__('lang.drag_drop_file')) }}</span>
            </div>
        </div>
    </div>

    <input type="hidden" name="fileresource_id" value="{{ request('fileresource_id') }}">
    <input type="hidden" name="fileresource_type" value="{{ request('fileresource_type') }}">
</div>

<!--tags-->
<div class="form-group row">
    <label class="col-12 text-left control-label col-form-label">{{ cleanLang(__('lang.tags')) }}</label>
    <div class="col-12">
        <select name="tags" id="tags"
            class="form-control form-control-sm select2-multiple {{ runtimeAllowUserTags() }} select2-hidden-accessible"
            multiple="multiple" tabindex="-1" aria-hidden="true">
            <!--array of selected tags-->
            @if(isset($page['section']) && $page['section'] == 'edit')
            @foreach($invoice->tags as $tag)
            @php $selected_tags[] = $tag->tag_title ; @endphp
            @endforeach
            @endif
            <!--/#array of selected tags-->
            @foreach($tags as $tag)
            <option value="{{ $tag->tag_title }}"
                {{ runtimePreselectedInArray($tag->tag_title ?? '', $selected_tags ?? []) }}>
                {{ $tag->tag_title }}
            </option>
            @endforeach
        </select>
    </div>
</div>

@if(config('visibility.file_modal_visible_to_client_option') && auth()->user()->role->role_id == 1)
<div class="form-group form-group-checkbox row p-l-15">
    <label class="float-left">{{ cleanLang(__('lang.visible_to_client')) }}?</label>
    <div class="float-left clearfix p-t-0 p-l-10">
        <input type="checkbox" id="file_visibility_client" name="file_visibility_client"
            class="filled-in chk-col-light-blue" checked="checked">
        <label for="file_visibility_client"></label>
    </div>
</div>
@endif

<!--fileupload-->
<!--pass source-->
<input type="hidden" name="source" value="{{ request('source') }}">