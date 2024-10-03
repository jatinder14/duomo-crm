<!--january-->
@foreach($estimates as $estimate)
<tr>
    <td><span class="hidden used-for-sorting">{{ runtimeMonthNumeric($estimate->estimate_month) }}</span>
        {{ runtimeLang($estimate->estimate_month) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ $estimate->estimate_count }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($estimate->sum_bill_amount_before_tax) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($estimate->sum_bill_tax_total_amount) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($estimate->sum_bill_discount_amount) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($estimate->sum_bill_adjustment_amount) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($estimate->sum_bill_final_amount) }}
    </td>
</tr>
@endforeach
