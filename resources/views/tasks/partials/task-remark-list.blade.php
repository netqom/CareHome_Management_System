<div class="">
	<div class="ibox">
		<div class="ibox-title d-flex pl-0">
			<h5>Task Remarks list</h5>
		</div>
		<div class="table-responsive">
			<table class="table table-hover no-margins" id="task_remark_list">
				<thead>
				<tr>
                    <th>#</th>
					<th>Task Mark Date </th>
					<th>Task Status</th>
                    <th>Task Marked By</th>
				</tr>
				</thead>
				<tbody>
					@forelse($tasks->task_remarks as $key => $task_remark)
                        @php
                            $task_status = config('const.task_status'); 
                            if ($task_remark->status == '0') {
                                $text_status = $task_status[0]; 
                            }else if($task_remark->status == '2'){
                                $text_status = $task_status[2]; 
                            }
                            else if($task_remark->status == '1'){
                                $text_status = $task_status[1];  
                            }
                            else{
                                $text_status = '--';  
                            }
                        @endphp
						<tr>
							<td>{{ $key + 1 }}</td>
							<td>{{ date_format($task_remark->created_at, 'm-d-Y'); }}</td>
                            <td>{{ $text_status }}</td>
                            <td>{{ $task_remark->added_by->name }}</td>
						</tr>
					@empty
						<tr>
							<td class="text-center" colspan="{{Auth::user()->role_id == 2 ? 8 : 7}}">No Data Found</td>
						</tr>						
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>