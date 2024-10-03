<!--rows-->
@foreach($estimates as $estimate)
<tr>
    <td>{{ str_limit_reports($estimate->category_name ?? '---', 40) }}</td>
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
