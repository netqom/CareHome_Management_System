<div class="row" id="revenue_list_div">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="">
                <div class="table-responsive relative">
                    {{-- <table class="table table-striped table-bordered table-hover" id="patient_list"> --}}
                    <table class="table" id="revenue_list">
                        <thead>
                        <tr>
                            <th style="min-width: 50px;" class="border-0">S.No</th>
                            <th style="min-width: 200px;" class="border-0">Patient Name</th>
                            <th style="min-width: 200px;" class="border-0">Payment For</th>
                            <th style="min-width: 70px;" class="border-0">Amount</th>
                            <th style="min-width: 70px;" class="border-0">Payment Info
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            @if (!$revenue->isEmpty())
                                @foreach ($revenue as $key => $record)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $record->patient_name }}</td>
                                        <td>{{ $record->is_in_house == 1 ? 'Day Care Charges' : 'Bed Reservation Charge' }}</td>
                                        <td>{{ amountFormat($record->charge_amount) }}</td>
                                        <td>{{ date('M d, Y', strtotime($record->created_at)) }}
                                            <br><span class="badge badge-primary py-1">By: {{ $record->added_by ? $record->added_by->name : '' }}</span>
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