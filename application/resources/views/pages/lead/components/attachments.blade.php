<div class="card-attachments" id="card-attachments"
    data-upload-url="{{ url('/leads/'.$lead->lead_id.'/attach-files')}}">
    <div class="x-heading"><i class="mdi mdi-cloud-download"></i>{{ cleanLang(__('lang.attachments')) }}</div>
    <div class="x-content row" id="card-attachments-container">
        <!--dynamic content here-->
    </div>
    @if($lead->permission_participate)
    <div class="x-action"><a class="card_fileupload" id="js-card-toggle-fileupload"
            href="javascript:void(0)">{{ cleanLang(__('lang.add_attachment')) }}</a></div>


    <div class="hidden" id="card-fileupload-container">
        
        <!--tags-->
        <div class="form-group row">
            <label
                class="col-12 text-left control-label col-form-label required p-l-35">{{ cleanLang(__('lang.tags')) }}</label>
            <div class="col-12 p-l-35">
                <select name="tags" id="tags"
                    class="form-control form-control-sm select2-multiple {{ runtimeAllowUserTags() }} select2-hidden-accessible card-attachment-tags"
                    multiple="multiple" tabindex="-1" aria-hidden="true">
                    @foreach($attachment_tags as $attachment_tag)
                    <option value="{{ $attachment_tag->tag_title }}">
                        {{ $attachment_tag->tag_title }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!--dropzone-->
        <div class="dropzone dz-clickable" id="card_fileupload">
            <div class="dz-default dz-message">
                <i class="icon-Upload-toCloud"></i>
                <span>{{ cleanLang(__('lang.drag_drop_file')) }}</span>
            </div>
        </div>
    </div>
    @endif
</div>
<!--attachemnts js-->