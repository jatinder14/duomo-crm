<div class="row">
    <div class="col-lg-12">
        <ul data-modular-id="contract_tabs_menu" class="nav nav-tabs contract-tab contract-top-nav list-pages-crumbs"
            role="tablist">
            <!--prview-->
            <li class="nav-item">
                <a class="nav-link tabs-menu-item" href="/contracts/{{ $document->doc_id }}" role="tab"
                    id="tabs-menu-overview">@lang('lang.preview')</a>
            </li>

            <!--edit-->
            <li class="nav-item">
                <a class="nav-link tabs-menu-item" href="/contracts/{{ $document->doc_id }}/edit" role="tab"
                    id="tabs-menu-overview">@lang('lang.edit_contract')</a>
            </li>
        </ul>
    </div>
</div>