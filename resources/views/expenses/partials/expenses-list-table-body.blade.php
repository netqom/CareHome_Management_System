@if (!$records->isEmpty())
    @foreach ($records as $key => $record)
        <tr>
            <!--td><input type="checkbox" class="i-checks" name="input[]"></td-->
            <td>
				{{ $key + 1 }}
			</td>
            <td>{{ $record->home ? $record->home->name : '' }}</td>
            <td>{{ $record->type == 1 ? 'Patient' : 'User' }}</td>
            <td>
				@if($record->type == 1)
				{{ $record->patient ? $record->patient->name : '' }}
				@else
				{{ $record->user ? $record->user->name : '' }}	
				@endif
			</td>
            <td>{{ $record->expense_type == 1 ? 'General' : 'Specific' }}</td>
            <td>{{ amountFormat($record->amount) }}</td>
			<td class="text-nowrap">
				<a class="btn-primary btn-sm open-close-row open-close-row" data-toggle="tooltip" data-placement="top"   href="javascript:void;" data-bs-toggle="collapse" data-bs-target="#info_row_{{ $record->id }}" data-item-id="{{ $record->id }}" data-toggle="tooltip" data-placement="top" title="View expenses">
					<i class="fa fa-eye"></i>
				</a>
			</td>
        </tr>
		<tr class="collapse accordion-collapse" id="info_row_{{ $record->id }}" data-bs-parent=".table">
			<td colspan="7">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<div>
						<span class="font-weight-bold">Description:</span>
						<span class="text-muted">{{ date('M d, Y', strtotime($record->created_at)) }}</span>
					</div>
					<div>
						<span class="font-weight-bold">By: {{ $record->added_by ? $record->added_by->name : '' }}</span>
					</div>
				</div>
			<p>{{ $record->description }}</p>
			
			
			</td>
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