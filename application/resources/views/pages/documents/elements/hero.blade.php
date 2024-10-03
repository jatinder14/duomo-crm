<div class="header-cover" id="hero-header-cover" {!! clean(getDocumentHeroImage($document->doc_hero_direcory ?? '',
    $document->doc_hero_filename ?? '', $document->doc_hero_updated ?? '', $document->doc_type)) !!}>
    <!--draft-->
    <div class="document-status-ribbon bg-draft {{ documentRibbonVisibility('draft', $document->doc_status) }}"
        id="doc_status_ribbon_draft">
        @lang('lang.draft')
    </div>
    <!--new-->
    <div class="document-status-ribbon bg-info {{ documentRibbonVisibility('new', $document->doc_status) }}"
        id="doc_status_ribbon_new">
        @lang('lang.new')
    </div>
    <!--accepted-->
    <div class="document-status-ribbon bg-success {{ documentRibbonVisibility('accepted', $document->doc_status) }}"
        id="doc_status_ribbon_accepted">
        @lang('lang.accepted')
    </div>
    <!--declined-->
    <div class="document-status-ribbon bg-danger {{ documentRibbonVisibility('declined', $document->doc_status) }}"
        id="doc_status_ribbon_declined">
        @lang('lang.declined')
    </div>
    <!--revised-->
    <div class="document-status-ribbon bg-primary {{ documentRibbonVisibility('revised', $document->doc_status) }}"
        id="doc_status_ribbon_revised">
        @lang('lang.revised')
    </div>
    <!--expired-->
    <div class="document-status-ribbon bg-danger {{ documentRibbonVisibility('expired', $document->doc_status) }}"
        id="doc_status_ribbon_expired">
        @lang('lang.expired')
    </div>
    <!--awiting-signature-->
    <div class="document-status-ribbon bg-warning {{ documentRibbonVisibility('awaiting_signatures', $document->doc_status) }}"
        id="doc_status_ribbon_awaiting_signatures">
        @lang('lang.awaiting_signatures')
    </div>
    <!--active-->
    <div class="document-status-ribbon bg-info {{ documentRibbonVisibility('active', $document->doc_status) }}"
        id="doc_status_ribbon_active">
        @lang('lang.active')
    </div>
    <div class="doc-hero-header {{ documentEditingModeCheck1($payload['mode'] ?? '') }}"
        data-block-styling="hero-heading" id="doc-element-hero">
        <!--editing icons-->
        <div class="doc-edit-icon {{ documentEditingModeCheck2($payload['mode'] ?? '') }}">
            <span class="x-edit-icon js-toggle-side-panel" data-reset-form='skip'
                data-target="documents-side-panel-hero" data-value-header="{{ $document->doc_heading }}"
                data-value-title="{{ $document->doc_title }}">
                <i class="sl-icon-note"></i>
            </span>
        </div>

        <!--main heading-->
        <div class="main-heading" data-block-src="block_sub_heading_1" {!! clean(getFontColor($document->
            doc_heading_color ?? '')) !!}>{{ $document->doc_heading }}</div>

        <!--document title-->
        <div class="main-title" {!! clean(getFontColor($document->doc_title_color ?? '')) !!}>{{ $document->doc_title }}
        </div>
    </div>
</div>