<!--rows-->
@foreach($invoices as $invoice)
<tr>
    <td><a href="{{ url('clients/'.$invoice->bill_clientid) }}">{{ str_limit_reports($invoice->client_company_name ?? '---', 40) }}</a>
    </td>
    <td data-tableexport-cellformat="">{{ $invoice->invoice_count }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($invoice->sum_bill_amount_before_tax) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($invoice->sum_bill_tax_total_amount) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($invoice->sum_bill_discount_amount) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($invoice->sum_bill_adjustment_amount) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($invoice->sum_bill_final_amount) }}
    </td>
</tr>
@endforeach
