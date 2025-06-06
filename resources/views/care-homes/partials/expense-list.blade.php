<div class="row" id="expense_list_div">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="">
                <div class="table-responsive relative">
                    {{-- <table class="table table-striped table-bordered table-hover" id="patient_list"> --}}
                    <table class="table" id="expense_list">
                        <thead>
                        <tr>
                            <th style="min-width: 50px;" class="border-0">S.No</th>
                            <th style="min-width: 200px;" class="border-0">Type</th>
                            <th style="min-width: 200px;" class="border-0">User/Patient Name</th>
                            <th style="min-width: 200px;" class="border-0">Expense Type</th>
                            <th style="min-width: 70px;" class="border-0">Amount</th>
                            <th style="min-width: 50px;" class="border-0">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if (!$patient_expenses->isEmpty())
                                @foreach ($patient_expenses as $key => $record)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $record->type == 1 ? 'Patient' : 'Staff' }}</td>
                                        <td>
                                            @if($record->type == 1)
                                                {{ $record->patient ? $record->patient->name : '' }}
                                            @else
                                                {{ $record->user ? $record->user->name : '' }}	
                                            @endif
                                        </td>
                                        <td>{{ $record->expense_type == 1 ? 'General' : 'Specific' }}</td>
                                        <td>{{ amountFormat($record->amount) }}</td>
                                        <td class="text-nowrap text-center">
                                            <a  data-toggle="tooltip" data-placement="top" title="View Expense" class="btn-primary btn-sm open-close-row" href="javascript:void;" data-bs-toggle="collapse" data-bs-target="#info_row_{{ $record->id }}" data-item-id="{{ $record->id }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="collapse accordion-collapse" id="info_row_{{ $record->id }}" data-bs-parent=".table">
                                        <td colspan="7">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <span class="font-weight-bold">Description:</span>
                                                    <span class="text-muted">{{ date('M d, Y', strtotime($record->created_at)) }}</span>
                                                </div>
                                                <div>
                                                    <span class="font-weight-bold">By: {{ $record->added_by ? $record->added_by->name : '' }}</span>
                                                </div>
                                            </div>
                                        <p class="text-left">{{ $record->description }}</p>
                                        
                                        
                                        </td>
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