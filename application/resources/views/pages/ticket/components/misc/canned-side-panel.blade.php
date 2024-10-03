<!-- right-sidebar -->
<div class="right-sidebar sidebar-lg" id="sidepanel-canned-messages">
    <div class="slimscrollright">
        <!--title-->
        <div class="rpanel-title">
            <i class="sl-icon-speech"></i>{{ cleanLang(__('lang.canned_messages')) }}
            <span>
                <i class="ti-close js-close-side-panels" data-target="sidepanel-filter-items"></i>
            </span>
        </div>

        <!--body-->
        <div class="r-panel-body">

            <!--search-->
            <div class="form-group row">
                <div class="col-sm-12 col-lg-6" id="canned-search-form">
                    <div class="search-text-field">
                        <i class="sl-icon-magnifier"></i>
                        <input type="text" class="form-control form-control-sm search_canned" id="search_canned"
                            name="search_canned" data-type="form" data-ajax-type="post"
                            data-form-id="canned-search-form" data-url="{{ url('canned/search') }}"
                            placeholder="@lang('lang.search')">
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6" id="canned-browse-form">
                    <select class="select2-basic form-control form-control-sm select2-preselected browse_canned"
                        data-type="form" data-ajax-type="post" data-form-id="canned-browse-form"
                        data-url="{{ url('canned/search') }}" id="browse_canned" name="browse_canned"
                        data-placeholder="Browse">
                        <option>@lang('lang.categories')</option>
                        @foreach($canned_categories as $category)
                        <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="canned-reponses-container" id="canned-reponses-container">

                @if(count($canned_recently_used) > 0)
                <h5 class="m-b-20 m-t-40">@lang('lang.recently_used')</h5>

                @foreach($canned_recently_used as $canned)
                @include('pages.ticket.components.misc.canned')
                @endforeach

                @else
                <div class="page-notification">
                    <img src="{{ url('/') }}/public/images/no-results-found.png" alt="404" />
                    <h4>@lang('lang.canned_no_recently_found')</h4>
                    <h5>@lang('lang.canned_you_can_search_or_browse')</h5>
                </div>
                @endif

            </div>
        </div>
        <!--body-->
    </div>
</div>
<!--sidebar-->