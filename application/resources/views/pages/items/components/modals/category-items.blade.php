<div class="table-responsive" id="category-items-list-table">
    <table class="table">
        <thead>
            <tr>
                <!--with sort-->
                <th class="col_category"><a href="javascript:void(0)">@lang('lang.category')</a></th>
                <!--actions-->
                <th class="col_no_sort"><a href="javascript:void(0)">@lang('lang.products')</a></th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <!--list checkbox-->
                <td class="items_col_checkbox checkitem">
                    <div class="clearfix">
                        <div class="pull-left p-l-10">
                            <span class="list-checkboxes display-inline-block w-px-20">
                                <input type="checkbox" id="category-{{ $category->category_id }}"
                                    name="ids[{{ $category->bar }}]" {{ runtimeCategoryItemsDisabledCheck(count($category->items)) }}
                                    data-target=".checkbox_items_{{ $category->category_id }}"
                                    class="listcheckbox listcheckbox-items filled-in chk-col-light-blue category-items-checkbox">
                                <label for="category-{{ $category->category_id }}"></label>
                            </span>
                        </div>
                        <div class="pull-left p-l-30">
                            {{ $category->category_name }}
                        </div>
                    </div>
                    <!--each category product (hidden)-->
                    <div class="list m-t-20 hidden" id="category-items-{{ $category->category_id }}">

                        <!--all items-->
                        @if(count($category->items) > 0)
                        @foreach($category->items as $item)
                        <div class="x-each-item m-b-10 p-l-40 clearfix">
                            <div class="pull-left p-l-10">
                                <span class="list-checkboxes display-inline-block w-px-20">
                                    <input type="checkbox" name="items[{{ $category->bar }}]"
                                        id="item-{{ $item->item_id }}"
                                        data-actions-container-class="items-checkbox-actions-container"
                                        data-item-id="{{ $item->item_id }}" data-unit="{{ $item->item_unit }}"
                                        data-quantity="1" data-description="{{ $item->item_description }}"
                                        data-type="{{ $item->item_type }}"
                                        data-length="{{ $item->item_dimensions_length }}"
                                        data-width="{{ $item->item_dimensions_width }}"
                                        data-tax-status="{{ $item->item_tax_status }}"
                                        data-has-estimation-notes="{{ $item->has_estimation_notes }}"
                                        data-estimation-notes="{{ $item->estimation_notes_encoded }}"
                                        data-rate="{{ $item->item_rate }}"
                                        class="listcheckbox listcheckbox-items filled-in chk-col-light-blue checkbox_items_{{ $category->category_id }} items-checkbox">
                                    <label for="item-{{ $item->item_id }}"></label>
                                </span>
                            </div>
                            <div class="pull-left p-l-30">
                                {{ $item->item_description }}
                            </div>
                        </div>
                        @endforeach
                        @endif

                        <!--no items-->
                        @if(count($category->items) == 0)
                        <div class="text-center m-b-20 m-t--10 opacity-9">
                            <small>@lang('lang.no_products_in_category')</small>
                        </div>
                        @endif
                    </div>
                </td>
                <!--count & toggle-->
                <td class="vt">
                    <div class="clearfix">
                        <div class="pull-left w-px-51">
                            {{ count($category->items) }}
                        </div>
                        <div class="switch pull-right m-l-20">
                            <label>
                                <input type="checkbox" name="add_client_option_contact" id="add_client_option_contact"
                                    data-target="category-items-{{ $category->category_id }}"
                                    class="js-switch-toggle-hidden-content">
                                <span class="lever switch-col-light-blue"></span>
                            </label>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="text-right m-t-20">
    <button type="submit" id="categoryItemsModalSelectButton"
        class="btn btn-rounded-x btn-danger waves-effect text-left" data-url="" data-loading-target=""
        data-ajax-type="POST"
        data-on-start-submit-button="disable">{{ cleanLang(__('lang.add_selected_items')) }}</button>
</div>