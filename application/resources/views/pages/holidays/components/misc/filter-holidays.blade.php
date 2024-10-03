<!-- right-sidebar -->
<div class="right-sidebar" id="sidepanel-filter-holidays">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i class="icon-Filter-2"></i>{{ cleanLang(__('lang.filter_holidays')) }}
                <span>
                    <i class="ti-close js-close-side-panels" data-target="sidepanel-filter-holidays"></i>
                </span>
            </div>
            <!--body-->
            <div class="r-panel-body">
                <!--date-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.date')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="filter_holiday_date"
                                    class="form-control form-control-sm pickadate" autocomplete="off"
                                    placeholder="{{ cleanLang(__('lang.date')) }}">
                                <input class="mysql-date" type="hidden" name="filter_holiday_date"
                                    id="filter_holiday_date"value="">
                            </div>
                        </div>
                    </div>
                </div>

                <!--reason-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.description')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <textarea name="filter_holiday_description" id="filter_holiday_description" class="form-control form-control-sm"
                                    autocomplete="off" placeholder="{{ cleanLang(__('lang.description')) }}"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!--buttons-->
                <div class="buttons-block">
                    <button type="button" name="foo1"
                        class="btn btn-rounded-x btn-secondary js-reset-filter-side-panel">{{ cleanLang(__('lang.reset')) }}</button>
                    <input type="hidden" name="action" value="search">
                    <input type="hidden" name="source" value="{{ $page['source_for_filter_panels'] ?? '' }}">
                    <button type="button" class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button"
                        data-url="{{ urlResource('/holidays/search?') }}" data-type="form"
                        data-ajax-type="GET">{{ cleanLang(__('lang.apply_filter')) }}</button>
                </div>
            </div>
            <!--body-->
        </div>
    </form>
</div>
<!--sidebar-->
