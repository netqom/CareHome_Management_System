@if (!$records->isEmpty())
    @foreach ($records as $key => $record)
        <tr> 
            
            <td>{{ $key + 1 }}</td>
            <td>{{ !empty($record->care_name) ? $record->care_name : '' }}</td>
            <td>{{ !empty($record->name) ? $record->name : '' }}</td>
            <td>{{ $record->added_by ? $record->added_by->name : '-' }}</td>
            <td>{{ date('m-d-Y', strtotime($record->report_date)) }}</td>
            <td>{{ date('h:i a', strtotime($record->updated_at))}} </td>
            <td><a href="{{ route('patients-show-activity-detail', ['patient_id'=>$record->log_patient_id,'id'=>$record->id]) }}{{!empty(request()->get('shift')) ?'?shift='.request()->get('shift') : ''}}" class="btn-primary btn btn-sm white-space-nowrap">View Detail</a></td>
         
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