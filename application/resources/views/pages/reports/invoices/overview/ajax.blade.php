<!--rows-->
@foreach($invoices as $invoice)
<tr>
    <td><a href="{{ url('invoices/'.$invoice->bill_invoiceid) }}">{{ $invoice->formatted_bill_invoiceid }}</a>
    </td>
    <td><a href="{{ url('clients/'.$invoice->bill_clientid) }}">{{ str_limit_reports($invoice->client_company_name ?? '---', 30) }}</a>
    </td>
    <td data-tableexport-cellformat=""><span class="hidden used-for-sorting">{{ $invoice->timestamp_bill_date }}</span>{{ runtimeDate($invoice->bill_date) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($invoice->bill_amount_before_tax) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($invoice->bill_tax_total_amount) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($invoice->bill_discount_amount) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($invoice->bill_adjustment_amount) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($invoice->bill_final_amount) }}
    </td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($invoice->sum_payments) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($invoice->invoice_balance) }}
    </td>
    <td> <span class="label {{ runtimeInvoiceStatusColors($invoice->bill_status, 'label') }}">{{
                    runtimeLang($invoice->bill_status) }}</span></td>
</tr>
@endforeach
