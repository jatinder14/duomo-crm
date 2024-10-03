<div class="col-12" id="bill-totals-wrapper">

    <!--FILE ATTACHEMENTS-->
    @if(config('visibility.bill_files_section'))
    <div class="pull-left m-t-30 text-left bill-file-attachments">
        <h6>@lang('lang.attachments')</h6>
        <div class="bill-file-attachments-wrapper" id="bill-file-attachments-wrapper">

            @foreach($files as $file)
            @include('pages.bill.components.elements.attachment')
            @endforeach
            <!--add attachments-->
            @if(config('visibility.bill_mode') == 'editing' && (auth()->check() && auth()->user()->role->role_estimates >= 3))
            <div class="x-add-file-button">
                <button type="button" id="bill-file-attachments-upload-button"
                    class="btn waves-effect waves-light btn-rounded btn-xs btn-danger">@lang('lang.add_file_attachments')</button>
            </div>
            @endif
        </div>
        <!--dropzone-->
        <!--fileupload-->
        @if(auth()->check() && auth()->user()->role->role_estimates >= 3)
        <div class="form-group row hidden" id="bill-file-attachments-dropzone-wrapper">
            <div class="col-12">
                <div class="dropzone dz-clickable fileupload_bills" id="fileupload_bills"
                    data-upload-url="{{ runtimeURLBillAttachFiles($bill) }}">
                    <div class="dz-default dz-message">
                        <i class="icon-Upload-toCloud"></i>
                        <span>@lang('lang.drag_drop_file')</span>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        id="bill-file-attachments-close-button">
                        <i class="ti-close"></i>
                    </button>
                </div>
            </div>
        </div>
        @endif
        <!--#fileupload-->
    </div>
    @endif

    <!--total amounts-->
    <div class="pull-right m-t-30 text-right">

        <table class="invoice-total-table">

            <!--invoice amount-->
            <tbody id="billing-table-section-subtotal" class="{{ $bill->visibility_subtotal_row }}">
                <tr>
                    <td>{{ cleanLang(__('lang.subtotal')) }}</td>
                    <td id="billing-subtotal-figure">
                        <span>{!! runtimeMoneyFormatPDF($bill->bill_subtotal) !!}</span>
                    </td>
                </tr>
            </tbody>

            <!--discounted invoice-->
            <tbody id="billing-table-section-discounts" class="{{ $bill->visibility_discount_row }}">
                <tr id="billing-sums-discount-container">
                    @if($bill->bill_discount_type == 'percentage')
                    <td>{{ cleanLang(__('lang.discount')) }} <span class="x-small"
                            id="dom-billing-discount-type">({{ $bill->bill_discount_percentage }}%)</span>
                    </td>
                    @else
                    <td>{{ cleanLang(__('lang.discount')) }} <span class="x-small"
                            id="dom-billing-discount-type">({{ cleanLang(__('lang.fixed')) }})</span></td>
                    @endif
                    <td id="billing-sums-discount">
                        -{!! runtimeMoneyFormatPDF($bill->bill_discount_amount) !!}
                    </td>
                </tr>

                <!-- 18 sep 2022 - removed. not really required--
                <tr id="billing-sums-before-tax-container" class="{{ $bill->visibility_before_tax_row }}">
                    <td>@lang('lang.total') <span class="x-small">({{ cleanLang(__('lang.before_tax')) }})</span></td>
                    <td id="billing-sums-before-tax">
                        <span>{!! runtimeMoneyFormatPDF($bill->bill_amount_before_tax) !!}</span></td>
                </tr>
                -->
            </tbody>

            <!--taxes (summary)-->
            @if($bill->bill_tax_type == 'summary')
            <tbody id="billing-table-section-tax" class="{{ $bill->visibility_tax_row }}">
                @foreach($bill->taxes as $tax)
                <tr class="billing-sums-tax-container" id="billing-sums-tax-container-{{ $tax->tax_id }}">
                    <td>{{ $tax->tax_name }} <span class="x-small">({{ $tax->tax_rate }}%)</span></td>
                    <td id="invoice-sums-tax-{{ $tax->tax_id }}">
                        <span>{!! runtimeMoneyFormatPDF(($bill->bill_amount_before_tax * $tax->tax_rate)/100) !!}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
            @endif


            <!--taxes (inline)-->
            @if($bill->bill_tax_type == 'inline')
            <tbody id="billing-table-section-tax" class="{{ $bill->visibility_tax_row }}">
                <tr class="billing-sums-tax-container">
                    <td>@lang('lang.tax')</td>
                    <td>
                        <span>{!! runtimeMoneyFormatPDF($bill->bill_tax_total_amount) !!}</span>
                    </td>
                </tr>
            </tbody>
            @endif


            <!--adjustment & invoice total-->
            <tbody id="invoice-table-section-total">
                <!--adjustment-->
                <tr class="billing-adjustment-container {{ $bill->visibility_adjustment_row }}"
                    id="billing-adjustment-container">
                    <td class="p-t-15 billing-adjustment-text" id="billing-adjustment-container-description">
                        {{ $bill->bill_adjustment_description }}</td>
                    <td class="p-t-15 billing-adjustment-text">
                        <span id="billing-adjustment-container-amount">{!!
                            runtimeMoneyFormatPDF($bill->bill_adjustment_amount) !!}</span>
                    </td>
                </tr>

                <!--total-->
                <tr class="text-themecontrast" id="billing-sums-total-container">
                    <td class="billing-sums-total">{{ cleanLang(__('lang.total')) }}</td>
                    <td id="billing-sums-total">
                        <span>{!! runtimeMoneyFormatPDF($bill->bill_final_amount) !!}</span>
                    </td>
                </tr>
            </tbody>

        </table>

    </div>

</div>