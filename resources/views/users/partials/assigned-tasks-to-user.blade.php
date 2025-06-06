<div class="">
    <div class="ibox">
        <div class="ibox-title d-flex pl-0">
            <h5>Assigned Tasks List </h5>
            @if (Auth::user()->role_id == 2 && $user->care_home->deleted_at == null && $user->deleted_at == null)
                <div class="ibox-tools">
                    <a href="{{ route('add-update-tasks') }}?staffid={{ $user->id }}"
                        class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Assign Task </a>
                </div>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Assigned By</th>
                        <th>Task Title</th>
                        <th>Start Time</th>
                        <!-- <th>End Time</th> -->
                        <th>Task Type</th>
                        <th>Task Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->getUserAssignedTasks as $key => $value)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $value->added_by ? ucfirst($value->added_by->name) : '-' }}</td>
                            <td>{{ $value->title ? ucfirst($value->title) : '-' }}</td>
                            <td>{{ $value->start_time }}</td>
                            {{-- <td>{{ $value->end_time }}</td> --}}
                            <td>
                                @php
                                    if ($value->task_type == 0) {
                                        $text_type = $task_type[0];
                                        $cls_name = 'label-warning';
                                    } else {
                                        $text_type = $task_type[1];
                                        $cls_name = 'label-primary';
                                    }
                                @endphp
                                <span class="label {{ $cls_name }}"> {{ $text_type }}</span>
                            </td>
                            <td>
                                @php
                                    if ($value->task_status == '0') {
                                        $text_status = $task_status[0];
                                        $cls_name = 'label label-danger';
                                    } elseif ($value->task_status == '2') {
                                        $text_status = $task_status[2];
                                        $cls_name = 'label label-primary';
                                    } elseif ($value->task_status == '1') {
                                        $text_status = $task_status[1];
                                        $cls_name = 'label label-primary';
                                    } else {
                                        $text_status = 'Pending';
                                        $cls_name = 'label label-warning';
                                    }
                                @endphp
                                <span class="{{ $cls_name }}"> {{ $text_status }}</span>
                            </td>
                            <td>
                                <a data-toggle="tooltip" data-placement="top" title="View Assigned Task"
                                    href="{{ route('view-user-assigned-task', ['id' => $value->id]) }}"
                                    class="btn-primary btn btn-sm"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-info" colspan="8">No Data Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
