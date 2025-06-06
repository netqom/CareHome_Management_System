@if (!$records->isEmpty())
    @foreach ($records as $key => $record)
        <tr>
            <!--td><input type="checkbox" class="i-checks" name="input[]"></td-->
            <td>{{ $key + 1 }}</td>
            <td>{{ $record->care_home_detail->name }}</td>
           <!--  <td>{{ $record->staff_detail ? $record->staff_detail->name : '' }}</td> -->
            <td>{{ $record->title }}</td>
			<td>{{ $record->description }}</td>
            <!-- <td>{{ $record->due_date ? date('m-d-Y',strtotime($record->due_date)) : '' }}</td> -->
            <td>
                @php
					$cls_name = 'label-primary';
					$text = 'Active';
					if ($record->status == 0) {
                        $text = 'Inactive';
                        $cls_name = 'label-warning';
                    }else if($record->deleted_at != NULL){
						$text = 'Deleted';
                        $cls_name = 'label-danger';
					}
                 /*if ($record->training_status == 0) {
                        $text = 'Pending';
                        $cls_name = 'label-warning';
                    }
                    else if($record->training_status == 1)
                    {
                        $cls_name = 'label-info';
					    $text = 'In-progress';
                    }else{
                        $cls_name = 'label-primary';
					    $text = 'Completed';
                    }*/
                @endphp
                <span class="label {{ $cls_name }}"> {{ $text }}</span>
            </td>
            <td>
				<div class="text-nowrap">
                    @if($record->deleted_at == null)
                        <a data-toggle="tooltip" data-placement="top" title="Edit Training" href="{{ route('add-update-training-course', [$record->id]) }}" class="btn-warning btn btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        <form action="{{ route('delete-training-course', $record->id) }}" method="post" id="delete_form_{{ $record->id }}" class="d-none">
                            @csrf
                            @method("DELETE")
                        </form>
                        <a href="javascript:;"  data-toggle="tooltip" data-placement="top" title="Delete Training"  class="btn-danger btn btn-sm confirm_delete" role="button" data-item-id="{{ $record->id }}" data-item-type="training course"><i class="fa fa-trash" aria-hidden="true"></i></a>
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