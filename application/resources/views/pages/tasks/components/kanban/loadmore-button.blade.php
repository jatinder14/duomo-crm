<!-- dynamic load more button-->
<div class="kanban-board-element autoload loadmore-button-container {{ $board['load_more'] }}"
    id="tasks-loadmore-container-{{ $board['id'] }}">
    <a data-url="{{ $board['load_more_url'] }}" href="javascript:void(0)"
        class="btn btn-rounded-x btn-secondary js-ajax-ux-request"
        id="load-more-button-{{ $board['id'] }}">{{ cleanLang(__('lang.show_more')) }}</a>
</div>
<!-- /#dynamic load more button-->