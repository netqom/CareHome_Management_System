@if (!$records->isEmpty())
    @foreach ($records as $key => $record)
        <tr>
            <!--td><input type="checkbox" class="i-checks" name="input[]"></td-->
            <td>{{ $key + 1 }}</td>
			<td>
				@if($record->role_id == 2) 
					{{ getAdminCareHome($record->id) }}
				@else	
					{{ $record->care_home ? $record->care_home->name : '' }}
				@endif
			</td>
            <td>{{ $record->name }}</td>
            <td>{{ $record->role_name->name }}</td>
			<td>{{ $record->email }}</td>
            <td>{{ $record->phone_number }}</td>
			@if(Auth::user()->role_id == 2)
				<td>{{ $record->shift_id > 0 ? getWorkShiftName($record->shift_id) : '-' }}</td>
			@endif
            <td>
                @php
					$cls_name = 'label-primary btn-primary';
					$text = 'Active';
					$tooltip = "Make in-active user";
					if ($record->status == 0 && $record->deleted_at == NULL) {
                        $text = 'Inactive';
                        $cls_name = 'label-warning btn-warning';
						$tooltip = "Make active user";
                    }else if($record->status == 0 && $record->deleted_at != NULL){
						$text = 'Deleted';
                        $cls_name = 'label-danger btn-danger';
					}

                @endphp
				@if($text == 'Deleted')
				<button type="button" class="btn btn-sm {{ $cls_name }} " > {{ $text }}</button>
				@else
                <button type="button" data-toggle="tooltip" data-placement="top" title="{{$tooltip}}" class="btn  btn-sm change_status {{ $cls_name }} change_status" data-item-id="{{ $record->id }}" data-status="{{ $record->status }}" data-item-type="user"> {{ $text }}</button>
				@endif
            </td>
            <td>
				<div class="text-nowrap">
					<a href="{{ route('users.show', $record->id) }}" data-toggle="tooltip" data-placement="top" class="btn-primary btn btn-sm" title="View Staff"><i class="fa fa-eye" aria-hidden="true"></i></a>
					@if($record->role_id != 2 && Auth::user()->role_id != 1)
						@if($record->deleted_at == null)
						<a href="{{ route('users.edit', $record->id) }}" data-toggle="tooltip" data-placement="top" class="btn-warning btn btn-sm" title="Edit Staff"><i class="fa fa-edit" aria-hidden="true"></i></a>
						@endif
					@endif
					@if(Auth::user()->role_id == 2)
						<a data-toggle="tooltip" data-placement="top"  href="{{ route('assign-training-to-staff.index',['home_id' => $record->home_id,'staff_id' => $record->id]) }}" class="btn-warning btn btn-sm" title="Assign Training"><i class="fa fa-file-text-o"></i></a>
					@endif
					@if($record->deleted_at == null && Auth::user()->role_id != 1)
						<form action="{{ route('users.destroy', $record->id) }}" method="post" id="delete_form_{{ $record->id }}" class="d-none">
							@csrf
							@method("DELETE")
						</form>
						<a data-toggle="tooltip" data-placement="top" href="javascript:;" class="btn-danger btn btn-sm confirm_delete" role="button" data-item-id="{{ $record->id }}" data-item-type="user" title="Delete Staff"><i class="fa fa-trash" aria-hidden="true"></i></a>
					@endif

					<!-- @if(Auth::user()->role_id == 1)
						@if($record->status == 1)
							<a data-toggle="tooltip" data-placement="top" href="javascript:;" class="btn-danger btn btn-sm change_status" role="button" data-item-id="{{ $record->id }}" data-status="{{ $record->status }}" data-item-type="user" title="Active user"><i class="fas fa-user"></i></a>
						@else
							<a data-toggle="tooltip" data-placement="top" href="javascript:;" class="btn-danger btn btn-sm change_status" role="button" data-item-id="{{ $record->id }}" data-status="{{ $record->status }}" data-item-type="user" title="In-active user"><i class="fas fa-user-slash"></i></a>
						@endif
					@endif -->
					@if(Auth::user()->role_id == 2 && $record->deleted_at != NULL)
					<a  data-toggle="tooltip" data-placement="top" href="javascript:;" title="Restore staff" class="btn-danger btn btn-sm restore_staff" role="button" data-item-id="{{ $record->id }}" data-home-id="{{ $record->home_id }}" data-item-type="user"><i class="fa fa-trash-restore" aria-hidden="true"></i></a>
					@endif
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