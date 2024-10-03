<!--rows-->
@foreach($clients as $client)
<tr>

    <!--client_company_name-->
    <td><a href="{{ url('clients/'.$client->client_id) }}">{{ $client->client_company_name }}</a>
    </td>

    <!--count_projects_completed-->
    <td>
        {{ $client->count_projects_completed }}</td>

    <!--count_projects_pending-->
    <td>
        {{ $client->count_projects_pending }}</td>

    <!--sum_invoices_paid-->
    <td>
        {{ runtimeMoneyFormat($client->sum_invoices_paid) }}
    </td>

    <!--sum_invoices_due-->
    <td>
        {{ runtimeMoneyFormat($client->sum_invoices_due) }}
    </td>

     <!--sum_invoices_overdue-->
     <td>
        {{ runtimeMoneyFormat($client->sum_invoices_overdue) }}
    </td>
 
    <!--sum_estimates_accepted-->
    <td>
        {{ runtimeMoneyFormat($client->sum_estimates_accepted) }}
    </td>

    <!--sum_estimates_declined-->
    <td>
        {{ runtimeMoneyFormat($client->sum_estimates_declined) }}
    </td>

    <!--sum_expenses_invoiced-->
    <td>
        {{ runtimeMoneyFormat($client->sum_expenses_invoiced) }}
    </td>

    <!--sum_expenses_not_invoiced-->
    <td>
        {{ runtimeMoneyFormat($client->sum_expenses_not_invoiced) }}
    </td>

    <!--sum_expenses_not_billable-->
    <td>
        {{ runtimeMoneyFormat($client->sum_expenses_not_billable) }}
    </td>
</tr>
@endforeach