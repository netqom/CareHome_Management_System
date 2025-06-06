<div class="">
	<div class="ibox">
		<div class="ibox-title d-flex pl-0">
			<h5>Staff Document list</h5>
			@if(Auth::user()->role_id == 2 && $user->deleted_at == NULL)
				<div class="ibox-tools">
					<a href="{{ route('add-update-home-document', ['home_id' => $user->home_id, 'id' => '0',"staff_id"=>$user->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add Document </a>
				</div>
			@endif
		</div>
		<div class="table-responsive">
			<table class="table table-hover no-margins" id="document_list">
				<thead>
				<tr>
					<th>Id</th>
					<th>Title</th>
					<th>Expiry Date</th>
					
					@if(Auth::user()->role_id == 2)
						<th>Action</th>
					@endif
				</tr>
				</thead>
				<tbody>
					
					@forelse($user->documents as $key => $document)
						<tr>
							<td>{{ $key + 1 }}</td>
							<td>{{ $document->name }}</td>
							<td>{{ !empty($document->expiry_date) ? date('m-d-Y', strtotime($document->expiry_date)) : 'N/A' }}</td>
							<td class="text-dark text-nowrap">
								@if($document->document_url != null && $document->document_url != '')
									<a target="_blank" href="{{ $document->document_url }}" class="btn-primary btn btn-sm" data-toggle="tooltip" data-placement="top"  title="View Patient Document">
										<i class="fa fa-eye" aria-hidden="true"></i>
									</a>	
								@else
									<a href="javascript:;" class="btn-primary btn btn-sm" data-toggle="tooltip" data-placement="top"  title="{{ Auth::user()->role_id == 2 ? 'Document not Available. Please Attach it' : 'Document not Available'}}">
										<i class="fa fa-eye" aria-hidden="true"></i>
									</a>
								@endif	
								@if(Auth::user()->role_id == 2)
									@if($user->deleted_at == NULL)					
										<a href="{{ route('add-update-home-document', ['home_id' => $user->home_id, 'id' => $document->id]) }}" class="btn-primary btn btn-sm" data-toggle="tooltip" data-placement="top" title="Edit staff Document">
											<i class="fa fa-edit" aria-hidden="true"></i>
										</a> 
										<form action="{{ route('delete-home-document', $document->id) }}" method="post" id="delete_form_{{ $document->id }}" class="d-none">
											@csrf
											@method("DELETE")
										</form>
										<a href="javascript:;" data-toggle="tooltip" data-placement="top" class="btn-danger btn btn-sm confirm_delete" role="button" data-item-id="{{ $document->id }}" data-item-type="patient document" title="Delete Patient Document"><i class="fa fa-trash" aria-hidden="true"></i></a>
									@endif
								@endif
							</td>
							
						</tr>
					@empty
						<tr>
							<td class="text-center" colspan="{{Auth::user()->role_id == 2 ? 6 : 5}}">No Data Found</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>