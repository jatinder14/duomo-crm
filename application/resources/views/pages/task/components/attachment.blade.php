@foreach($attachments as $attachment)
<div class="col-sm-12" id="card_attachment_{{ $attachment->attachment_uniqiueid }}">
    <div class="file-attachment m-b-25">
        @if($attachment->attachment_type == 'image')
        <!--dynamic inline style-->
        <div class="">
            <a class="fancybox preview-image-thumb"
                href="storage/files/{{ $attachment->attachment_directory }}/{{ $attachment->attachment_filename  }}"
                title="{{ str_limit($attachment->attachment_filename, 60) }}"
                alt="{{ str_limit($attachment->attachment_filename, 60) }}">
                <img class="x-image"
                    src="{{ url('storage/files/' . $attachment->attachment_directory .'/'. $attachment->attachment_thumbname) }}">
            </a>
        </div>
        @else
        <div class="x-image">
            <a class="preview-image-thumb" href="tasks/download-attachment/{{ $attachment->attachment_uniqiueid }}"
                download>
                {{ $attachment->attachment_extension }}
            </a>
        </div>
        @endif
        <div class="x-details">
            <div><span class="x-meta">{{ $attachment->first_name ?? runtimeUnkownUser() }}</span>
                [{{ runtimeDateAgo($attachment->attachment_created) }}]</div>
            <div class="x-name"><span
                    title="{{ $attachment->attachment_filename }}">{{ str_limit($attachment->attachment_filename, 60) }}</span>
            </div>
            <div class="x-tags">
                @foreach($attachment->tags as $tag)
                <span class="x-each-tag">{{ $tag->tag_title }}</span>  
                @endforeach
            </div>
            <div class="x-actions"><strong>
                    <!--action: download-->
                    <a href="tasks/download-attachment/{{ $attachment->attachment_uniqiueid }}"
                        download>{{ cleanLang(__('lang.download')) }} <span class="x-icons"><i
                                class="ti-download"></i></span></strong></a>

                <!--action: cover image-->
                @if($attachment->permission_set_cover)
                <!--add cover---->
                <span id="cover_image_add_{{ $attachment->attachment_id }}"
                    class="cover_image_buttons cover_image_buttons_add js-add-cover-image {{ runtimeCoverImageAddButton($attachment->attachment_uniqiueid, $attachment->task_cover_image_uniqueid) }}"
                    data-image-url="storage/files/{{ $attachment->attachment_directory }}/{{ $attachment->attachment_filename  }}"
                    data-progress-bar="hidden" data-add-cover-button="cover_image_add_{{ $attachment->attachment_id }}"
                    data-remove-cover-button="cover_image_remove_{{ $attachment->attachment_id }}"
                    data-cover-remove-button-url="{{ url('/tasks/'.$attachment->attachmentresource_id.'/remove-cover-image') }}"
                    data-id="{{ $attachment->attachmentresource_id }}"
                    data-url="{{ url('/tasks/'.$attachment->attachmentresource_id.'/add-cover-image?imageid='.$attachment->attachment_uniqiueid) }}">
                    |
                    <strong><a href="javascript:void(0)">@lang('lang.set_cover')</a>
                    </strong></span>
                <!--remove cover---->
                <span id="cover_image_remove_{{ $attachment->attachment_id }}"
                    class="cover_image_buttons cover_image_buttons_remove js-remove-cover-image  {{ runtimeCoverImageRemoveButton($attachment->attachment_uniqiueid, $attachment->task_cover_image_uniqueid) }}"
                    data-progress-bar="hidden" data-add-cover-button="cover_image_add_{{ $attachment->attachment_id }}"
                    data-remove-cover-button="cover_image_remove_{{ $attachment->attachment_id }}"
                    data-id="{{ $attachment->attachmentresource_id }}"
                    data-url="{{ url('/tasks/'.$attachment->attachmentresource_id.'/remove-cover-image') }}">
                    |
                    <strong><a href="javascript:void(0)">@lang('lang.remove_cover')</a>
                    </strong></span>
                @endif

                <!--action: delete-->
                @if($attachment->permission_delete_attachment)
                <span> |
                    <strong><a href="javascript:void(0)" class="text-danger js-delete-ux-confirm confirm-action-danger"
                            data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                            data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                            data-parent-container="card_attachment_{{ $attachment->attachment_uniqiueid }}"
                            data-progress-bar="hidden"
                            data-url="{{ urlResource('/tasks/delete-attachment/'.$attachment->attachment_uniqiueid) }}">{{ cleanLang(__('lang.delete')) }}</a>
                    </strong></span>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach