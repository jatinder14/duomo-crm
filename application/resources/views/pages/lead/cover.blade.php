<!--cover image-->
<div class="card-cover-image-wrapper hidden" id="card-cover-image-wrapper">
    <span class="cover-image-remove-button hidden">
        <button type="button" id="remove-cover-image-button" data-progress-bar="hidden" data-id="{{ $lead->lead_id }}"
            data-url="{{ url('/leads/'.$lead->lead_id.'/remove-cover-image') }}"
            class="btn btn-outline-secondary btn-sm js-remove-cover-image remove-cover-image-button">@lang('lang.remove_cover')</button>
    </span>
    <div class="card-cover-image-container fancybox" id="card-cover-image-container"
        href="{{ url('storage/files/'. $lead->lead_cover_image_uniqueid .'/'. $lead->lead_cover_image_filename) }}" {!!
        runtimeCoverImage($lead->lead_cover_image_uniqueid, $lead->lead_cover_image_filename) !!}>

    </div>
</div>