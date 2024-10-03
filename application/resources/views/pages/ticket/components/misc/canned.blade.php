@if(is_numeric($canned->canned_id))
<div class="ticket-canned-response">
    <div class="canned-header clearfix">
        <div class="x-title">
            {{ str_limit($canned->canned_title ?? '---', 100) }}
        </div>

        <div class="x-buttons">
            <!--expan button-->
            <button type="button" class="btn btn-default btn-sm waves-effect waves-dark js-canned-button-expand m-b-20"
                data-meta="canned-item-meta-{{ $canned->canned_id }}" data-up="canned-arrow-up-{{ $canned->canned_id }}"
                data-down="canned-arrow-down-{{ $canned->canned_id }}"
                data-body="canned-item-body-{{ $canned->canned_id }}">
                <i class="sl-icon-arrow-down canned-icon-down" id="canned-arrow-down-{{ $canned->canned_id }}"></i>
                <i class="sl-icon-arrow-up hidden canned-icon-up" id="canned-arrow-up-{{ $canned->canned_id }}"></i>
            </button>

            <!--insert button-->
            <button type="button" class="btn btn-default btn-sm waves-effect waves-dark js-canned-button-insert m-b-20"
                data-url="{{ url('canned/update-recently-used/'.$canned->canned_id) }}"
                data-progress-bar="hidden"
                data-body="canned-item-body-{{ $canned->canned_id }}">
                <i class="ti-angle-double-left"></i>
            </button>
        </div>
    </div>

    <div class="canned-meta" id="canned-item-meta-{{ $canned->canned_id }}">
        <i class="sl-icon-folder-alt"></i> <span class="x-folder-name">{{ $canned->category_name }}</span>
    </div>

    <!--canned body-->
    <div class="canned-body hidden" id="canned-item-body-{{ $canned->canned_id }}">
        {!! $canned->canned_message !!}
    </div>
</div>
@endif