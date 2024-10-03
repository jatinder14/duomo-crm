<!--each category-->
<div class="x-each-category {{ $files['search_type'] ?? 'all'}}">

    <!--heading-->
    @if($files['search_type'] == 'all')
    <div class="x-heading clearfix">
        <span class="pull-left x-title">
            @lang('lang.files')
        </span>
        <span class="pull-right x-count">
            <a href="javascript:void(0);" class="ajax-request" data-url="{{ url('search?search_type=files') }}"
                data-type="form" data-form-id="global-search-form" data-ajax-type="post"
                data-loading-target="global-search-form" name="search_query">@lang('lang.view_all')
                ({{ $files['count'] }})</a>
        </span>
    </div>
    @endif

    <!--results-->
    <ul>

        <!-- each result -->
        @foreach($files['results']->take(runtimeSearchDisplyLimit($files['search_type'])) as $file)
        <li class="files">
            <a href="javascript:void(0);">
                <!--icon-->
                <span class="x-icon">
                    <i class="sl-icon-cloud-download"></i>
                </span>
                <!--title-->
                <span class="x-title">
                    <a href="{{ url('files/download?file_id='.$file->file_uniqueid) }}"
                        download>{{ $file->file_filename }}</a>
                </span>
                <!--matched  on tags-->
                @if($file->tags->isNotEmpty() && $file->tags->contains('tag_title', $search_query))
                <span class="ti-bookmark x-tag-match" title="@lang('lang.matched_tags')" data-toggle="tooltip"></span>
                @endif
                <!--meta-->
                <span class="x-meta">
                    @if($file->fileresource_type == 'project')
                    - {{ str_limit($file->project_title ?? '', 50) }}
                    @endif
                    @if($file->fileresource_type == 'client')
                    - {{ str_limit($file->client_company_name ?? '', 50) }}
                    @endif
                </span>

            </a>
        </li>
        @endforeach

        <!--ajax loading-->

    </ul>
</div>