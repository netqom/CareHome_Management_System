<div class="row" id="invoices_list_div">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="px-2">
                <div class="table-responsive relative">
                    <table class="table font-bold" id="invoices_table">
                        <thead>
                            <tr>
                                <th class="border-0">Subscription ID</th>
                                <th class="border-0">Amount</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Date</th>
                                <th class="border-0 text-center">Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$subscription_invoices->isEmpty())
                                @foreach($subscription_invoices as $key => $invoice)
                                    <tr class="text-muted">
                                        <td>{{ $invoice->stripe_subscription_id}}
                                            {{-- <div class="text-muted"></div> --}}
                                        </td>
                                        <td>${{ $invoice->unit_amount }} </td>
                                        <td><span class="badge badge-primary text-capitalize py-1">{{ $invoice->stripe_status}}</span></td>
                                        <td>{{  date('M d, Y', strtotime($invoice->created_at)) }}</td>
                                        <td class="text-center">
                                            <a class="btn-primary btn btn-sm" data-toggle="tooltip" data-placement="top"  href="{{$invoice->invoice_pdf}}" download="{{$invoice->invoice_pdf}}"><i class="fa fa-download" aria-hidden="true"></i>  Download</a>
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