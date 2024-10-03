<div class="board">
    <div class="board-body {{ runtimeKanbanBoardColors($board['color']) }}">
        <div class="board-heading clearfix">
            <div class="pull-left">{{ runtimeLang($board['name']) }}</div>
            <div class="pull-right x-action-icons">
                <!--action add-->
                <span class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form cursor-pointer"
                    data-toggle="modal" data-target="#commonModal"
                    data-url="{{ urlResource('/leads/create?status='.$board['id']) }}"
                    data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.add_new_lead')) }}"
                    data-action-url="{{ urlResource('/leads?type=kanban') }}" data-action-method="POST"
                    data-action-ajax-loading-target="commonModalBody"
                    data-save-button-class="" data-action-ajax-loading-target="commonModalBody"><i
                        class="mdi mdi-plus-circle"></i></span>
            </div>
        </div>
        <!--cards-->
        <div class="content kanban-content" id="kanban-board-wrapper-{{ $board['id'] }}" data-board-name="{{ $board['id'] }}">

            <!--dynamic content-->
            @if(@count($board['leads'] ?? []) > 0)
            @include('pages.leads.components.kanban.card')
            @endif

            <!-- dynamic load more button-->
            @include('pages.leads.components.kanban.loadmore-button')
        </div>
    </div>
</div>