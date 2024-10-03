<!--details-->
<div class="col-sm-12 col-lg-3" id="ticket-left-panel">
    <div class="card m-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="ticket-panel">
                    <div class="x-top-header">
                        {{ cleanLang(__('lang.categories')) }}
                    </div>

                    <div class="x-body">

                        <!--department-->
                        @foreach($category_list as $category)
                        <div class="x-list ajax-request cursor-pointer canned_category {{ runtimeCannedCategory($category['category_id']) }}"
                            data-url="{{ url('canned?filter_categoryid='.$category['category_id']) }}"
                            id="canned_category_{{ $category['category_id'] }}">
                            <div class="x-name">{{ $category['category_name'] }}</div>
                            <div class="x-details"><span
                                    id="canned_category_count_{{ $category['category_id'] }}">{{ $category['count_canned'] }}</span>
                                @lang('lang.count_canned_responses')</div>
                        </div>
                        @endforeach


                        <!--edit button-->
                        @if(config('visibility.action_buttons_manage'))
                        <div class="x-list b-none">
                            <!--add item modal-->
                            <a href="{{ url('app/categories?filter_category_type=canned&source=ext') }}" type="button"
                                class="btn btn-info btn-sm edit-add-modal-button">@lang('lang.manage_categories')
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!--options-->