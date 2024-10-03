<div class="row">
    <div class="col-lg-12">

        <!--title-->
        <div class="form-group row">
            <label
                class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.title')) }}*</label>
            <div class="col-sm-12">
                <input type="text" class="form-control form-control-sm" autocomplete="off" id="note_title"
                    name="note_title" value="{{ $note->note_title ?? '' }}">
            </div>
        </div>

        <!--description-->
        <div class="form-group row">
            <label
                class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.description')) }}*</label>
            <div class="col-sm-12">
                <textarea id="note_description" name="note_description"
                    class="tinymce-textarea hidden">{{ $note->note_description ?? '' }}</textarea>
            </div>
        </div>

        <!--tags-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.tags')) }}</label>
            <div class="col-sm-12">
                <select name="tags" id="tags"
                    class="form-control form-control-sm select2-multiple {{ runtimeAllowUserTags() }} select2-hidden-accessible"
                    multiple="multiple" tabindex="-1" aria-hidden="true">
                    <!--array of selected tags-->
                    @if(isset($page['section']) && $page['section'] == 'edit')
                    @foreach($note->tags as $tag)
                    @php $selected_tags[] = $tag->tag_title ; @endphp
                    @endforeach
                    @endif
                    <!--/#array of selected tags-->
                    @foreach($tags as $tag)
                    <option value="{{ $tag->tag_title }}"
                        {{ runtimePreselectedInArray($tag->tag_title ?? '', $selected_tags  ?? []) }}>
                        {{ $tag->tag_title }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <!--/#tags-->

        <!--attach recipt - toggle-->
        <div class="spacer row">
            <div class="col-sm-12 col-lg-8">
                <span class="title">{{ cleanLang(__('lang.add_file_attachments')) }}</span class="title">
            </div>
            <div class="col-sm-12 col-lg-4">
                <div class="switch  text-right">
                    <label>
                        <input type="checkbox" name="show_more_settings_notes" id="show_more_settings_notes"
                            class="js-switch-toggle-hidden-content" data-target="add_file_attachments">
                        <span class="lever switch-col-light-blue"></span>
                    </label>
                </div>
            </div>
        </div>


        <!--attach recipt-->
        <div class="hidden" id="add_file_attachments">
            <!--fileupload-->
            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="dropzone dz-clickable" id="dropzone_upload_multiple_files">
                        <div class="dz-default dz-message">
                            <i class="icon-Upload-toCloud"></i>
                            <span>{{ cleanLang(__('lang.drag_drop_file')) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!--fileupload-->
            <!--existing files-->
            @if(isset($page['section']) && $page['section'] == 'edit')
            <table class="table table-bordered">
                <tbody>
                    @foreach($attachments as $attachment)
                    <tr id="note_attachment_{{ $attachment->attachment_id }}">
                        <td>{{ $attachment->attachment_filename }} </td>
                        <td class="w-px-40"> <button type="button"
                                class="btn btn-danger btn-circle btn-sm confirm-action-danger"
                                data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" active"
                                data-ajax-type="DELETE"
                                data-url="{{ url('/notes/attachments/'.$attachment->attachment_uniqiueid) }}">
                                <i class="sl-icon-trash"></i>
                            </button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        <!--pass source-->
        <input type="hidden" name="source" value="{{ request('source') }}">

        <!--notes-->
        <div class="row">
            <div class="col-12">
                <div><small><strong>* {{ cleanLang(__('lang.required')) }}</strong></small></div>
            </div>
        </div>

        <!--info-->
        @if(request('noteresource_type') == 'project' && auth()->user()->is_team)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info">{{ cleanLang(__('lang.project_notes_not_visible_to_client')) }}</div>
            </div>
        </div>
        @endif

    </div>
</div>