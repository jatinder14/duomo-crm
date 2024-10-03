<!--rows-->
@foreach($estimates as $estimate)
<tr>
    <td><a href="{{ url('clients/'.$estimate->bill_clientid) }}">{{ str_limit_reports($estimate->client_company_name ?? '---', 40) }}</a>
    </td>
    <td data-tableexport-cellformat="">{{ $estimate->estimate_count }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($estimate->sum_bill_amount_before_tax) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($estimate->sum_bill_tax_total_amount) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($estimate->sum_bill_discount_amount) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($estimate->sum_bill_adjustment_amount) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($estimate->sum_bill_final_amount) }}
    </td>
</tr>
@endforeach
