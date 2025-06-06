@if (!$records->isEmpty())
    @foreach ($records as $key => $record)
        <tr>
            <!--td><input type="checkbox" class="i-checks" name="input[]"></td-->
            <td>{{ $key + 1 }}</td>
            <td>{{ isset($record->user_name) ? ucfirst($record->user_name) : (isset($record->patient_name) ? ucfirst($record->patient_name) : '-')}}</td>
            <td>{{ ucfirst($record->user_type) }}</td>
            <td>{{ $record->title }}</td>
			<td>{{ date('m-d-Y', strtotime($record->start_date)) }}</td>
            <td>{{ $record->task_type == 1 ? date('m-d-Y',strtotime($record->end_date)) : '-'}}</td>
            <td>
                @php
                    $task_type = config('const.task_type'); 
					if ($record->task_type == 0) {
                        $text = $task_type[0];
                        $cls_name = 'label-warning';
                    }
                    else{
					    $text = $task_type[1]; 
                        $cls_name = 'label-primary';
                    }
                @endphp
                <span class="label {{ $cls_name }}"> {{ $text }}</span>
            </td>
            <td>
				<div class="text-nowrap">
                    <a data-toggle="tooltip" data-placement="top" title="View task" href="{{ route('tasks.show', $record->id) }}" class="btn-primary btn btn-sm mb-0"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    @if($record->deleted_at == null)
                        @if($record->user_deleted_at == null && $record->user_deleted_at == '')
                            <a data-toggle="tooltip" data-placement="top" title="Edit task" href="{{ route('add-update-tasks', [$record->id]) }}" class="btn-warning btn btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        @endif
                        <form action="{{ route('delete-task', $record->id) }}" method="post" id="delete_form_{{ $record->id }}" class="d-none">
                            @csrf
                            @method("DELETE")
                        </form>
                        <a data-toggle="tooltip" data-placement="top" title="Delete task"  href="javascript:;" class="btn-danger btn btn-sm confirm_delete" role="button" data-item-id="{{ $record->id }}" data-item-type="training course"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    @endif
				</div>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td class="text-warning text-center" colspan="7">No Data Found</td>
    </tr>
@endif
<script>
$('.i-checks').iCheck({
	checkboxClass: 'icheckbox_square-green',
	radioClass: 'iradio_square-green',
});
</script>							