@if (!$records->isEmpty())
    @foreach ($records as $key => $record)
        <tr>
            <!--td><input type="checkbox" class="i-checks" name="input[]"></td-->
            <td>{{ $key + 1 }}</td>
			<td>
				@if($record->role_id == 2) 
					{{ getAdminCareHome($record->home_id) }}
				@else	
					{{ $record->care_home ? $record->care_home->name : '' }}
				@endif
			</td>
            <td>{{ $record->name }} 
				@if($record->discharged_request == 1)
				<br/><span class="badge badge-warning py-1">Discharge request</span>
				@elseif($record->delete_request == 1 && $record->deleted_at == NULL)
				<br/><span class="badge badge-warning py-1">Delete request</span>
				@endif
			</td>
			<td>{{ $record->email }}</td>
            <td>{{ $record->phone }}</td>
            <td>
                @php
					$cls_name = 'label-primary';
					$text = 'Active';
					if ($record->discharged == 1) {
                        $text = 'Discharged';
                        $cls_name = 'label-warning';
                    }else if ($record->deleted_at != NULL) {
                        $text = 'Deleted';
                        $cls_name = 'label-danger';
                    }
                @endphp
                <span class="label {{ $cls_name }}"> {{ $text }}</span>
            </td>
            <td>
				<div class="text-nowrap">
					<a data-toggle="tooltip" data-placement="top" title="View Patient" href="{{ route('patients.show', $record->id) }}" class="btn-primary btn btn-sm" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
					@if($record->role_id != 2)
						@if($record->deleted_at == NULL && $record->discharged == 0)
							<a data-toggle="tooltip" data-placement="top"  href="{{ route('patients.edit', $record->id) }}"  class="btn-warning btn btn-sm" title="Edit Patient"><i class="fa fa-edit" aria-hidden="true"></i></a>
						@endif
					@endif
					@if(Auth::user()->role == 2)
						<a href="{{ route('assign-training-to-staff.index',['home_id' => $record->home_id,'staff_id' => $record->id]) }}" class="btn-warning btn btn-sm" title="Assign Training"><img src="{{asset('assets/img/list-check.svg')}}" style="width:12px; height:auto;"></a>
					@endif

					{{-- @if($record->delete_request == 1 && $record->deleted_at == NULL) --}}
					@if($record->discharged == 0 && $record->deleted_at == NULL)
					<form action="{{ route('delete-patient', ['id' => $record->id]) }}" method="post" id="delete_form_{{ $record->id }}" class="d-none">
						@csrf
						@method("DELETE")
					</form>

					<form action="{{ route('delete-patient-request', ['id' => $record->id]) }}" method="get" id="delete_request_form_{{ $record->id }}" class="d-none">
						@csrf
						@method("GET")
					</form>
					<a  data-toggle="tooltip" data-placement="top" href="javascript:;" title="Delete Patient" class="btn-danger btn btn-sm confirm_delete" role="button" data-item-id="{{ $record->id }}" data-item-type="user" data-delete-request={{$record->delete_request == 1 ? 1 : 0}}><i class="fa fa-trash" aria-hidden="true"></i></a>
					@endif

					@if(Auth::user()->role_id == 2 && $record->deleted_at != NULL)
					<a  data-toggle="tooltip" data-placement="top" href="javascript:;" title="Restore Patient" class="btn-danger btn btn-sm restore_patient" role="button" data-item-id="{{ $record->id }}" data-home-id="{{ $record->home_id }}" data-item-type="user"><i class="fa fa-trash-restore" aria-hidden="true"></i></a>
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