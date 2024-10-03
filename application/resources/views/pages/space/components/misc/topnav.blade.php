<div class="row">
    <div class="col-lg-12">
        <!-- Nav tabs -->
        <ul data-modular-id="project_tabs_menu" class="nav nav-tabs profile-tab project-top-nav list-pages-crumbs"
            role="tablist">
            <!--[files]-->
            <li class="nav-item">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request {{ $page['tabmenu_files'] ?? '' }}"
                    data-toggle="tab" id="tabs-menu-files" data-loading-class="loading-tabs"
                    data-loading-target="embed-content-container"
                    data-dynamic-url="{{ _url('/spaces') }}/{{ $space->project_id }}/files"
                    data-url="{{ url('/files') }}?source=ext&fileresource_type=project&fileresource_id={{ $space->project_id }}&filter_folderid={{ $space->default_folder_id }}"
                    href="#projects_ajaxtab" role="tab">{{ cleanLang(__('lang.files')) }}</a>
            </li>
        </ul>
    </div>
</div>