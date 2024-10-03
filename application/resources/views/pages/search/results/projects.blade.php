<!--each category-->
<div class="x-each-category {{ $projects['search_type'] ?? 'all'}}">

    <!--heading-->
    @if($projects['search_type'] == 'all')
    <div class="x-heading clearfix">
        <span class="pull-left x-title">
            @lang('lang.projects')
        </span>
        <span class="pull-right x-count">
            <a href="javascript:void(0);" class="ajax-request" data-url="{{ url('search?search_type=projects') }}"
                data-type="form" data-form-id="global-search-form" data-ajax-type="post"
                data-loading-target="global-search-form" name="search_query">@lang('lang.view_all')
                ({{ $projects['count'] }})</a>
        </span>
    </div>
    @endif

    <!--results-->
    <ul>

        <!-- each result -->
        @foreach($projects['results']->take(runtimeSearchDisplyLimit($projects['search_type'])) as $project)
        <li class="projects">
            <a href="javascript:void(0);">
                <!--icon-->
                <span class="x-icon">
                    <i class="ti-folder"></i>
                </span>
                <!--title-->
                <span class="x-title">
                    <a href="{{ url('projects/'.$project->project_id) }}">{{ $project->project_title }}</a>
                </span>
                <!--matched  on tags-->
                @if($project->tags->isNotEmpty() && $project->tags->contains('tag_title', $search_query))
                <span class="ti-bookmark x-tag-match" title="@lang('lang.matched_tags')" data-toggle="tooltip"></span>
                @endif
                <!--meta-->
                <span class="x-meta">
                    - #{{ $project->project_id ?? 0 }}
                </span>
            </a>
        </li>
        @endforeach

        <!--ajax loading-->

    </ul>
</div>