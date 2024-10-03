<div class="row">
    <div class="col-lg-12">
        <div class="p-b-10 text-right"><small>{{ runtimeDate($note->note_created) }}</small></div>
        <div class="p-b-30">{!! clean($note->note_description) ?? '---' !!}</div>
    </div>
    <div class="col-lg-12">
        <div class="p-t-30">
            <h6>@lang('lang.attachments')</h6>
            <table class="table table-bordered">
                <tbody>
                    @foreach($attachments as $attachment)
                    <tr id="note_attachment_{{ $attachment->attachment_id }}">
                        <td><a href="notes/attachments/download/{{ $attachment->attachment_uniqiueid }}" download>
                                {{ $attachment->attachment_filename }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>