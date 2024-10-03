<!--modal-->
<div class="modal" role="dialog" aria-labelledby="categoryItemsModal" id="categoryItemsModal" {!! runtimeAllowCloseModalOptions()
    !!}>
    <div class="modal-dialog modal-xl" id="categoryItemsModalContainer">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="categoryItemsModalTitle">{{ cleanLang(__('lang.product_category')) }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="ti-close"></i>
                </button>
            </div>
            
            <div class="modal-body p-t-10 p-b-20 min-h-200" id="categoryItemsModalBody">
                
            </div>
        </div>
    </div>
</div>