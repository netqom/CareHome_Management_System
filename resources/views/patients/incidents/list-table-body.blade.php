@if (!$records->isEmpty())
    @foreach ($records as $key => $record)
        <tr> 
            
            <td>{{ $key + 1 }}</td>
            <td>
            {{-- <a href="{{ route('patients.show', $record->patient_id) }}" class=""> --}} @if(strlen($record->patient_name) > 50)
                    {{ substr($record->patient_name, 0, 50) }}...
                @else
                    {{ $record->patient_name }}
                @endif
               {{--  </a> --}}
            </td>
            <td>
             @if(strlen($record->care_home_name) > 50)
                    {{ substr($record->care_home_name, 0, 50) }}...
                @else
                    {{ $record->care_home_name }}
                @endif
            </td>
           <td>
                @if(strlen($record->title) > 70)
                    {{ substr($record->title, 0, 70) }}...
                @else
                    {{ $record->title }}
                @endif
            </td>
            <td>
            @if(strlen($record->incident_created_by) > 50)
                    {{ substr($record->incident_created_by, 0, 50) }}...
                @else
                    {{ $record->incident_created_by }}
                @endif
           </td>
            <td>{{ date('m-d-Y',strtotime($record->created_at)) }}</td>
            <td><a data-toggle="tooltip" data-placement="top" title="View Incident Reports"  href="{{ route('patients-show-incident-detail', $record->id) }}" class="btn-primary btn btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
         
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