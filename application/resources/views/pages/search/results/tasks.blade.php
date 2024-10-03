<!--each category-->
<div class="x-each-category {{ $task['search_type'] ?? 'all'}}">

    <!--heading-->
    @if($tasks['search_type'] == 'all')
    <div class="x-heading clearfix">
        <span class="pull-left x-title">
            @lang('lang.task')
        </span>
        <span class="pull-right x-count">
            <a href="javascript:void(0);" class="ajax-request" data-url="{{ url('search?search_type=tasks') }}"
                data-type="form" data-form-id="global-search-form" data-ajax-type="post"
                data-loading-target="global-search-form" name="search_query">@lang('lang.view_all')
                ({{ $tasks['count'] }})</a>
        </span>
    </div>
    @endif

    <!--results-->
    <ul>

        <!-- each result -->
        @foreach($tasks['results']->take(runtimeSearchDisplyLimit($tasks['search_type'])) as $task)
        <li class="tasks">
            <a href="javascript:void(0);">
                <!--icon-->
                <span class="x-icon">
                    <i class="ti-menu-alt"></i>
                </span>
                <!--title-->
                <span class="x-title">
                    <a href="{{ url('/tasks/v/'.$task->task_id.'/'.$task->task_title) }}">{{ $task->task_title }}</a>
                </span>
                <!--matched  on tags-->
                @if($task->tags->isNotEmpty() && $task->tags->contains('tag_title', $search_query))
                <span class="ti-bookmark x-tag-match" title="@lang('lang.matched_tags')" data-toggle="tooltip"></span>
                @endif
                <!--meta-->
                <span class="x-meta">
                    - {{ str_limit($task->project_title ?? '', 50) }}
                </span>
            </a>
        </li>
        @endforeach

        <!--ajax loading-->

    </ul>
</div>