@if (!$records->isEmpty())
    @foreach ($records as $key => $record)
        <tr>
            <!--td><input type="checkbox" class="i-checks" name="input[]"></td-->
            <td>{{ $key + 1 }}</td>
            <td>{{ @$record->course_detail->title }}</td>
            <td>@foreach($course_status as $key => $value)
                    @if($record->course_status === $key)
                        {{$value}}
                    @endif
                @endforeach
            </td>
			<td>{{ changeDateFormat($record->start_date,'m-d-Y') }}</td>
            <td>{{ changeDateFormat($record->end_date,'m-d-Y') }}</td>
            <td>
                @php
					$cls_name = 'label-primary';
					$text = 'Active';
					if ($record->status == 0) {
                        $text = 'Inactive';
                        $cls_name = 'label-warning';
                    }
                @endphp
                <span class="label {{ $cls_name }}"> {{ $text }}</span>
            </td>
            <td>
				<div class="text-nowrap">
                    
                    @if($record->course_status != 3 && $record->course_status != 5)
                        @if($user_detail->deleted_at == null && $user_detail->deleted_at == '')
				            <a data-toggle="tooltip" data-placement="top" title="Edit Assigned Training"  href="{{ route('assign-training-to-staff.edit', [$record->id, 'home_id'=> @$record->course_detail->home_id]) }}" class="btn-warning btn btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        @endif
                    @else
                        <a data-toggle="tooltip" data-placement="top" title="View Assigned Training"   href="{{ route('assign-training-to-staff.show', [$record->id, 'home_id'=> @$record->course_detail->home_id]) }}" class="btn-warning btn btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    @endif
					<form action="{{ route('assign-training-to-staff.destroy', $record->id) }}" method="post" id="delete_form_{{ $record->id }}" class="d-none">
						@csrf
						@method("DELETE")
					</form>
					<a href="javascript:;"  data-toggle="tooltip" data-placement="top" title="Delete Assigned Training"  class="btn-danger btn btn-sm confirm_delete" role="button" data-item-id="{{ $record->id }}" data-item-type="assign training"><i class="fa fa-trash" aria-hidden="true"></i></a>

                    
				</div>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td class="text-warning text-center" colspan="{{ Auth::user()->role_id == 2 ? 9 : 8 }}">No Data Found</td>
    </tr>
@endif
<script>
$('.i-checks').iCheck({
	checkboxClass: 'icheckbox_square-green',
	radioClass: 'iradio_square-green',
});
</script>							