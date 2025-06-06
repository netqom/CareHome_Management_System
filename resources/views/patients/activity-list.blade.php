@extends('layouts.admin')

@section('title', 'Patient Activity')

@section('content')
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				<li class="breadcrumb-item">
					<a href="{{ route('homes.show', $patient->care_home->id) }}">Care Home</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>Patients Activity</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
        <div class="col-lg-12">
        <div class="ibox shadow border rounded">
        <div class="ibox-title">
            <h5>Patient Activity List </h5>
        </div>
        <div class="ibox-content">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
						<tr>
							<th>#</th>
							<th>Name </th>
							<th>Date</th>
							<th>Reported By</th>
							<th>Action</th>
						</tr>
                    </thead>
                    <tbody>
						@forelse($logs as $key => $log)
							<tr>
								<td>{{ $key + 1 }}</td>
								<td>{{ $log->patient ? $log->patient->name : '-' }}</td>
								<td>{{ $log->report_date }}</td>
								<td>{{ $log->added_by ? $log->added_by->name : '-' }}</td>
								<td><a href="{{ route('patients-show-activity-detail', $log->id) }}" class="btn-primary btn btn-sm">View Detail</a></td>
							</tr>
						@empty
							<tr><td class="text-center text-info" colspan="5">No Data Found</td></tr>
						@endforelse
                    </tbody>
                </table>
            </div>

        </div>
        </div>
        </div>

        </div>

	</div>	
@endsection
@section('script')
    <script type="text/javascript">
	 //page script goes here
    </script>
@endsection