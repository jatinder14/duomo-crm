<!--rows-->
@foreach($estimates as $estimate)
<tr>
    <td><a href="{{ url('estimates/'.$estimate->bill_estimateid) }}">{{ $estimate->formatted_bill_estimateid }}</a>
    </td>
    <td><a href="{{ url('clients/'.$estimate->bill_clientid) }}">{{ str_limit_reports($estimate->client_company_name ?? '---', 30) }}</a>
    </td>
    <td data-tableexport-cellformat=""><span class="hidden used-for-sorting">{{ $estimate->timestamp_bill_date }}</span>{{ runtimeDate($estimate->bill_date) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($estimate->bill_amount_before_tax) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($estimate->bill_tax_total_amount) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($estimate->bill_discount_amount) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($estimate->bill_adjustment_amount) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($estimate->bill_final_amount) }}
    </td>
    <td> <span class="label {{ runtimeEstimateStatusColors($estimate->bill_status, 'label') }}">{{
                    runtimeLang($estimate->bill_status) }}</span></td>
</tr>
@endforeach
