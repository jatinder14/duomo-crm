<div class="x-file-attachment" id="file_attachment_{{ $file->file_uniqueid }}">
    <a href="{{ url('storage/files/'.$file->file_directory.'/'.$file->file_filename) }}" download><span
            class="x-extension"><i class="ti-clip"></i></span>
        <span class="x-file-name">{{ str_limit($file->file_filename ?? '---', 17) }}</span>
    </a>
    @if(config('visibility.bill_attachments_delete_button'))
    <span class="x-delete" id="delete-bill-file-attachment" data-parent="file_attachment_{{ $file->file_uniqueid }}"
        data-progress-bar="hidden"
        data-url="{{ url(config('bill.url_end_point').'/delete-attachment?file_uniqueid='.$file->file_uniqueid) }}"><i
            class="sl-icon-close"></i></span>
    @endif
</div>