<div class="row" id="expense_list_div">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="">
                <div class="card p-3 shadow">
                    <div class="bg-transparent border-0 card-header mb-3 px-0 py-0">
                        <div class="card-title">
                            <h2 class="font-bold fs-18 fw-bold text-body">Current Active Staff Member/Manager</h2>
                        </div>
                        <div class="card-toolbar">
        
                        </div>
                    </div>
                    
                    <div class="table-responsive relative">
                        {{-- <table class="table table-striped table-bordered table-hover" id="patient_list"> --}}
                        <table class="table" id="expense_list">
                            <thead>
                            <tr>
                                <th style="min-width: 50px;" class="border-0">S.No</th>
                                <th style="min-width: 200px;" class="border-0">Type</th>
                                <th style="min-width: 200px;" class="border-0">Member/Manager Name</th>
                                <th style="min-width: 200px;" class="border-0 text-right">Shift Name</th> 
                            </tr>
                            </thead>
                            <tbody>
                                @if (!$currently_working_users->isEmpty())
                                    @foreach ($currently_working_users as $key => $record)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $record->role_id == 3 ? 'Manager' : 'Staff' }}</td>
                                            <td>{{ $record->name ? $record->name : '' }}</td>
                                            <td>{{ $record->shift_name ? $record->shift_name : '-'}}</td> 
                                        </tr> 
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-warning text-center" colspan="5">No Data Found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div id="pagination-section"></div>
                </div>
            </div>
        </div>
    </div>
</div>