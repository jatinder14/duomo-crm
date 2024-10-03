<!--each category-->
<div class="x-each-category {{ $attachments['search_type'] ?? 'all'}}">

    <!--heading-->
    @if($attachments['search_type'] == 'all')
    <div class="x-heading clearfix">
        <span class="pull-left x-title">
            @lang('lang.attachments')
        </span>
        <span class="pull-right x-count">
            <a href="javascript:void(0);" class="ajax-request" data-url="{{ url('search?search_type=attachments') }}"
                data-type="form" data-form-id="global-search-form" data-ajax-type="post"
                data-loading-target="global-search-form" name="search_query">@lang('lang.view_all')
                ({{ $attachments['count'] }})</a>
        </span>
    </div>
    @endif

    <!--results-->
    <ul>

        <!-- each result -->
        @foreach($attachments['results']->take(runtimeSearchDisplyLimit($attachments['search_type'])) as $attachment)
        <li class="attachments">
            <!--icon-->
            <span class="x-icon">
                <i class="sl-icon-paper-clip"></i>
            </span>
            <!--title-->
            <span class="x-title">
                <a href="{{ url('files/download-attachment?attachment_id='.$attachment->attachment_uniqiueid) }}"
                    download>{{ $attachment->attachment_filename }}</a>

            </span>
            <!--matched  on tags-->
            @if($attachment->tags->isNotEmpty() && $attachment->tags->contains('tag_title', $search_query))
            <span class="ti-bookmark x-tag-match" title="@lang('lang.matched_tags')" data-toggle="tooltip"></span>
            @endif
            <!--meta-->
            <span class="x-meta">
                @if($attachment->attachmentresource_type == 'task')
                - {{ str_limit($attachment->task_title ?? '', 50) }}
                @endif
                @if($attachment->attachmentresource_type == 'lead')
                - {{ str_limit($attachment->lead_title ?? '', 50) }}
                @endif
            </span>
        </li>
        @endforeach
        <!--ajax loading-->

    </ul>
</div>