@if (!$records->isEmpty())
    @foreach ($records as $key => $record)
        <tr>
            <!--td><input type="checkbox" class="i-checks" name="input[]"></td-->
            <td>{{ $key + 1 }}</td>
			<td>{{ $record->name }}</td>
            <td>
				@php $shift_ids = explode(',', $record->shift_id); @endphp
				@foreach($shift_ids as $key => $value)
					{{ getActivityShiftName($value) }}
					@if($key < count($shift_ids) - 1)
					{{ ',' }}
					@endif
				@endforeach
			</td>
            <td>
                @php
					$cls_name = 'label-primary';
					$text = 'Active';
					if($record->status == 1 && $record->deleted_at == NULL){
						$text = 'Active';
						$cls_name = 'label-primary';
					}
					else if($record->deleted_at != NULL){
						$text = 'Deleted';
                        $cls_name = 'label-danger';
					}
					else if ($record->status == 0 && $record->deleted_at == NULL) {
                        $text = 'Inactive';
                        $cls_name = 'label-warning';
                    }
                @endphp
                <span class="label {{ $cls_name }}"> {{ $text }}</span>
            </td>
            <td>
				<div class="text-nowrap">
					<!--a href="{{ route('users.show', $record->id) }}" class="btn-primary btn btn-sm">View</a-->
					@if(Auth::user()->role_id == 1 || Auth::user()->id == $record->created_by)
						@if($record->deleted_at == null)
							<a href="{{ route('activities.edit', $record->id) }}" data-toggle="tooltip" data-placement="top" title="Edit Activity"  class="btn-warning btn btn-sm"><i class="fa fa-edit" ></i></a>
							
							<form action="{{ route('activities.destroy', $record->id) }}" method="post" id="delete_form_{{ $record->id }}" class="d-none">
								@csrf
								@method("DELETE")
							</form>
							<a href="javascript:;"   data-toggle="tooltip" data-placement="top" class="btn-danger btn btn-sm confirm_delete" title="Delete Activity" role="button" data-item-id="{{ $record->id }}" data-item-type="activity"><i class="fa fa-trash" aria-hidden="true" ></i>
							</a>
						@endif
					@endif
				</div>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td class="text-warning text-center" colspan="6">No Data Found</td>
    </tr>
@endif
<script>
$('.i-checks').iCheck({
	checkboxClass: 'icheckbox_square-green',
	radioClass: 'iradio_square-green',
});
</script>							