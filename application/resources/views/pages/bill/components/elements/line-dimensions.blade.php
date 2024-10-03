<!--EACH LINE ITEM X-->
<tr class="billing-line-item" id="lineitem_{{ $lineitem->lineitem_id ?? '' }}" type="dimensions">
    <!--action-->
    <td class="td-action list-table-action x-action bill_col_action">
        <button type="button"
            class="btn btn-outline-danger btn-circle btn-sm js-billing-line-item-delete">
            <i class="sl-icon-trash"></i>
        </button>
                <!--drg-drop icon-->
                <i class="sl-icon-menu font-14 display-inline-block m-l-2 opacity-4 cursor-pointer"></i>
    </td>
    <!--description-->
    <td class="form-group x-description bill_col_description">
        <textarea class="form-control form-control-sm js_item_description js_line_validation_item" rows="3"
            name="js_item_description[{{ $lineitem->lineitem_id ?? '' }}]">{{ $lineitem->lineitem_description ?? '' }}</textarea>
    </td>
    <!--quantity-->
    <td class="form-group x-quantity bill_col_quantity">
        <input class="form-control form-control-sm js_item_quantity calculation-element js_line_validation_item"
            type="number" step="1" name="js_item_quantity[{{ $lineitem->lineitem_id ?? '' }}]"
            value="{{ $lineitem->lineitem_quantity ?? '' }}">

    </td>
    <!--units (hrs)-->
    <td class="form-group x-unit bill_col_unit">

        <!--length-->
        <div class="input-group input-group-sm m-b-4">
            <span class="input-group-addon" id="fx-line-item-hrs">L</span>
            <input type="number" class="form-control js_item_length calculation-element js_line_validation_item"
                name="js_item_length[{{ $lineitem->lineitem_id ?? '' }}]"
                value="{{ $lineitem->lineitem_dimensions_length ?? '' }}">
        </div>
        <!--width-->
        <div class="input-group input-group-sm">
            <span class="input-group-addon" id="fx-line-item-min">W</span>
            <input type="number" class="form-control js_item_width calculation-element js_line_validation_item"
                name="js_item_width[{{ $lineitem->lineitem_id ?? '' }}]"
                value="{{ $lineitem->lineitem_dimensions_width ?? '' }}">
        </div>
    </td>
    <!--rate-->
    <td class="form-group x-price bill_col_price">
        <div class="input-group input-group-sm m-b-4">
            <input
                class="form-control form-control-sm js_item_rate calculation-element decimal-field js_line_validation_item"
                type="number" step="1" name="js_item_rate[{{ $lineitem->lineitem_id ?? '' }}]"
                value="{{ $lineitem->lineitem_rate ?? '' }}">
        </div>
        <div class="input-group input-group-sm">
            <span class="input-group-addon" id="fx-line-item-min">per/</span>
            <input class="form-control form-control-sm js_item_unit js_line_validation_item" type="text"
                name="js_item_unit[{{ $lineitem->lineitem_id ?? '' }}]" value="{{ $lineitem->lineitem_unit ?? '' }}">
        </div>

    </td>
    <!--inline tax-->
    <td
        class="bill_col_tax form-group x-tax {{ runtimeVisibility('invoice-column-inline-tax', $bill->bill_tax_type) }} ">
        <select name="js_item_tax[{{ $lineitem->lineitem_id ?? '' }}]" {{ runtimeLineItemTaxStatus($lineitem->lineitem_tax_status ?? '') }}
            class="form-control form-control-sm js_item_tax calculation-element" tabindex="-1" aria-hidden="true">
            <!--show zero rated tax first-->
            @foreach($taxrates as $taxrate)
            @if($taxrate->taxrate_uniqueid == 'zero-rated-tax-rate')
            <option value="0|{{ $taxrate->taxrate_name }}|zero-rated-tax-rate|{{ $taxrate->taxrate_id }}"
                {{ runtimeInlineTaxPreselected($taxrate->taxrate_id ?? '', $lineitem->lineitem_id ?? '') }}>
                0% - {{ $taxrate->taxrate_name }}
            </option>
            @endif
            @endforeach
            <!--show all other tax rates-->
            @foreach($taxrates as $taxrate)
            @if($taxrate->taxrate_uniqueid != 'zero-rated-tax-rate')
            <option
                value="{{ $taxrate->taxrate_value }}|{{ $taxrate->taxrate_name }}|{{ $taxrate->taxrate_uniqueid }}|{{ $taxrate->taxrate_id }}"
                {{ runtimeInlineTaxPreselected($taxrate->taxrate_id ?? '', $lineitem->lineitem_id ?? '') }}>
                {{ $taxrate->taxrate_value }}% - {{ $taxrate->taxrate_name }}</option>
            @endif
            @endforeach
        </select>
        <input type="hidden" class="js_linetax_total" name="js_linetax_rate[{{ $lineitem->lineitem_id ?? '' }}]"
            value="0">
    </td>
    <!--total-->
    <td class="form-group x-total" id="bill_col_total">
        <input class="form-control form-control-sm js_item_total decimal-field" type="number" step="0.01"
            name="js_item_total[{{ $lineitem->lineitem_id ?? '' }}]" value="{{ $lineitem->lineitem_total ?? '' }}"
            disabled>
    </td>

    <!--linked items-->
    <input type="hidden" class="js_item_linked_type" name="js_item_linked_type[{{ $lineitem->lineitem_id ?? '' }}]"
        value="dimensions">
    <input type="hidden" class="js_item_linked_id" name="js_item_linked_id[{{ $lineitem->lineitem_id ?? '' }}]"
        value="{{ $lineitem->lineitemresource_linked_id ?? '' }}">
    <input type="hidden" class="js_item_dimensionsrs_list"
        name="js_item_dimensionsrs_list[{{ $lineitem->lineitem_id ?? '' }}]"
        value="{{ $lineitem->lineitem_dimensions_dimensionsrs_list ?? '' }}">

    <!--item type-->
    <input type="hidden" class="js_item_type" name="js_item_type[{{ $lineitem->lineitem_id ?? '' }}]"
        value="dimensions">

    <!-- original product id-->
    <input type="hidden" class="js_item_id" name="js_item_id[{{ $lineitem->lineitem_id ?? '' }}]"
        value="{{ $lineitem->lineitem_linked_product_id ?? '' }}">

    <!-- tax status-->
    <input type="hidden" class="js_item_tax_status"
        name="js_item_tax_status[{{ $lineitem->lineitem_id ?? '' }}]"
        value="{{ $lineitem->lineitem_tax_status ?? '' }}">
</tr>
<!--/#EACH LINE ITEM-->