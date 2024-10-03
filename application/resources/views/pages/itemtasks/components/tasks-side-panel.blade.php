<!-- right-sidebar -->
<div class="right-sidebar products-tasks-side-panel sidebar-lg" id="products-tasks-side-panel">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <!--add class'due'to title panel -->
                <i class="ti-alarm-clock display-inline-block m-t--5"></i>
                <div class="display-inline-block">
                    @lang('lang.product_tasks') (@lang('lang.automation')) <span class="align-middle display-inline p-t-3 p-l-8"
                        data-toggle="tooltip" title="@lang('lang.product_tasks_info')" data-placement="top"><i class="ti-info-alt font-17"></i></span>
                </div>
                <span>
                    <i class="ti-close js-close-side-panels" data-target="products-tasks-side-panel"
                        id="products-tasks-side-panel-close-icon"></i>
                </span>
            </div>
            <!--title-->
            <!--body-->
            <div class="r-panel-body products-tasks-side-panel-body  p-b-80">

                <div class="text-right p-b-30">
                    <!--add item modal-->
                    <button type="button" id="create-product-task-button"
                        class="btn btn-info btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                        data-toggle="modal" data-target="#commonModal" data-url="--dynamic--"
                        data-loading-target="commonModalBody" data-modal-title="@lang('lang.add_task')"
                        data-action-type="" data-action-form-id="" data-action-url="--dynamic--"
                        data-action-method="POST" data-action-ajax-class="" data-modal-size="modal-lg"
                        data-header-close-icon="hidden" data-header-extra-close-icon="visible"
                        data-action-ajax-loading-target="commonModalBody">@lang('lang.add_task')
                    </button>
                </div>

                <div id="products-tasks-side-panel-content">


                </div>

            </div>
            <!--body-->
        </div>
    </form>
</div>
<!--sidebar-->