@if (!$records->isEmpty())
    @foreach ($records as $key => $record)
        <tr>
            <!--td><input type="checkbox" class="i-checks" name="input[]"></td-->
            <td>
				{{ $key + 1 }}
			</td>
            <td>{{ $record->home ? $record->home->name : '' }}</td>
            <td>{{ $record->patient ? $record->patient->name : '' }}</td>
            <td>{{ $record->expense_type == 1 ? 'General' : 'Specific' }}</td>
            <td>{{ amountFormat($record->amount) }}</td>
			<td>
				<a class="btn-primary btn-sm open-close-row open-close-row" href="#" data-bs-toggle="collapse" data-bs-target="#info_row_{{ $record->id }}" data-item-id="{{ $record->id }}">
					<i class="fa fa-eye"></i>
				</a>
			</td>
        </tr>
		<tr class="collapse accordion-collapse" id="info_row_{{ $record->id }}" data-bs-parent=".table">
			<td colspan="5">
			<span class="font-weight-bold d-block">Description:</span>
			<p>{{ $record->description }}</p>
			<span class="text-muted">{{ date('d M, Y', strtotime($record->created_at)) }}</span>
			<span class="font-weight-bold d-block">{{ $record->added_by ? $record->added_by->name : '' }}</span>
			<td>
		</tr>
    @endforeach
@else
    <tr>
        <td class="text-warning text-center" colspan="8">No Data Found</td>
    </tr>
@endif
<script>
$('.i-checks').iCheck({
	checkboxClass: 'icheckbox_square-green',
	radioClass: 'iradio_square-green',
});
</script>							