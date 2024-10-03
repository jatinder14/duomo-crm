<!--only for estimate-->
@if($bill->bill_type == 'estimate')
<tr class="estimation-notes-template lineitem_{{ $lineitem->lineitem_id ?? '' }}">
    <td colspan="8" class="estimation-notes">
        <div class="x-wrapper estimation-notes-text">
            {!! $lineitem->item_notes_estimatation ?? '' !!}
        </div>
    </td>
</tr>
@endif