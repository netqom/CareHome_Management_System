<div class="row">
    <div class="col-12 col-xl-8 order-2 order-xl-1">
        <div class="card p-3 shadow">
            <div class="bg-transparent border-0 card-header mb-3 px-0 py-0">
                <div class="card-title">
                    <h2 class="font-bold fs-18 fw-bold text-body">Subscription Details</h2>
                </div>
                <div class="card-toolbar">

                </div>
            </div>
            <h5 class="font-bold h6 mb-4 text-body">Billing Address:</h5>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <table class="table table-borderless billing">
                        <tbody>
                            <tr>
                                <td class="font-bold  text-muted">Bill to:</td>
                                <td class="">
                                    <a href="#" class="font-bold text-navy">{{ !is_null($home->email) ? $home->email : 'Not Added !!' }}</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold  text-muted">Customer Name:</td>
                                <td class="font-bold  text-body">{{ $home->name }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold  text-muted">Address:</td>
                                <td class="font-bold  text-body">{{ $home->street }}, {{ $home->city }}, {{ $home->state }}, {{ $home->zip_code }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold  text-muted">Phone:</td>
                                <td class="font-bold  text-body">{{ !is_null($home->contact_no) ? $home->contact_no : 'Not Added !!' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-lg-6">
                    <table class="table table-borderless billing">
                        <tbody>
                            <tr>
                                <td class="font-bold  text-muted">Subscribed Package:</td>
                                <td class="font-bold  text-body">{{ $home->subscription ? $home->subscription->type : '--'}}</td>
                            </tr>
                            <tr>
                                <td class="font-bold  text-muted">Subscription Fees:</td>
                                <td class="font-bold  text-body">
                                    @if($home->subscription)
                                        ${{ $plan ? $plan->price : ''}} / {{ $plan && $plan->duration === 1 ? 'Month' : ($plan && $plan->duration === 0 ? 'Daily': 'Year')}}
                                    @else
                                        --
                                    @endif
                                </td>
                                
                            </tr>
                            <tr>
                                <td class="font-bold  text-muted">Billing Cycle:</td>
                                <td class="font-bold  text-body">  
                                    @if($home->subscription)
                                    {{ $plan && $plan->duration === 1 ? 'Monthly' : ($plan && $plan->duration === 0 ? 'Daily': 'Yearly')}}
                                    @else
                                     --
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold  text-muted">Currency:</td>
                                <td class="font-bold  text-body">USD - US Dollar</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            

        </div>
        <div class="card p-3 shadow mt-4">
            <h5 class="font-bold h6 mb-4 text-body">Invoices:</h5>
            @include('care-homes.partials.subscription-invoices')
        </div>
    </div>
    <div class="col-12 col-xl-4 order-1 order-xl-2 mb-4">
        <div class="card p-3 shadow">
            <div
                class="bg-transparent border-0 card-header mb-3 px-0 py-0 d-flex align-items-center justify-content-between">

                <h2 class="font-bold fs-18 fw-bold text-body">Summary</h2>
                @if(Auth::user()->role_id == 2)
                @if($home->subscription && $home->deleted_at == NULL)
                    @if($home->subscription->stripe_status  == 'active')
                        <div class="dropdown">
                            <button class="btn btn-primary border-0 btn-sm" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @if($home->subscription_status != 'pause' && $home->pause_at == null && $home->subscription->ends_at == '')
                                    <a class="dropdown-item p-2 pause_subscription" href="javascript:;" data-item-id="{{ $home->id }}" data-item-type="{{ $home->subscription->type }}" data-stripe-id="{{ $home->subscription->stripe_id}}">Pause Subscription</a>
                                @else
                                    <a class="dropdown-item p-2 resume_subscription" href="javascript:;" data-item-id="{{ $home->id }}" data-item-type="{{ $home->subscription->type }}" data-stripe-id="{{ $home->subscription->stripe_id}}">Resume Subscription</a>
                                @endif
                                @if($home->subscription->ends_at  == '')
                                    <a class="dropdown-item p-2 text-danger cancel_subscription" href="javascript:;" data-item-id="{{ $home->id }}" data-item-type="{{  $home->subscription->type }}" data-stripe-id="{{ $home->subscription->stripe_id}}">Cancel Subscription</a>
                                @endif
                                <a class="dropdown-item p-2" href="{{ route('subscription-manage', ['id' => $home->id, 'upgrade_downgrade_plan' => 'yes']) }}">Upgrade Subscription</a>
                                @if($plan && $plan->addon_status == 1)
                                    <a class="dropdown-item p-2" href="{{ route('subscription-manage', ['id' => $home->id, 'add_ons' => 'yes']) }}">Staff Addons</a>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
                @endif
            </div>
            <div class="card-body p-0">
                <div class="mb-4">
                    <div class="d-flex align-items-center">
                        <div class="mr-2 summary-img">
                            <img width="40" alt="Pic" src="{{ $home->image_path }}" class=" rounded-lg  @if(is_null($home->image) || (!is_null($home->image) && !file_exists(public_path($home->image))))  @endif">
                        </div>
                        <div class="d-flex flex-column font-bold ">
                            <a href="#" class="text-body">{{ $home->name }}</a>
                            <a href="#" class="text-navy">{{ !is_null($home->email) ? $home->email : 'Not Added !!' }}</a>
                        </div>
                    </div>
                </div>
                <div class="mb-4 separator-dashed"></div>
                <div class="mb-4">
                    <h5 class="font-bold fs-14 mb-2 text-body">Product details</h5>
                    <div class="mb-0">
                        @if($home->subscription)
                            <span class="badge badge-primary me-2 mr-2 small py-1">{{ $home->subscription ? $home->subscription->type : ''}}</span>
                            <span class="font-bold  text-muted">${{ $plan ? $plan->price : ''}} / {{ $plan && $plan->duration === 1 ? 'Month' : ($plan &&$plan->duration === 0 ? 'Daily': 'Year')}}</span>
                        @else
                            <p class="text-warning">No Data Found.</p>
                        @endif
                    </div>
                </div>

                <div class="mb-4 separator-dashed"></div>
                <div class="mb-4">
                    <h5 class="font-bold fs-14 mb-2 text-body">Payment Details</h5>
                    <div class="mb-0">
                        <div class="font-bold  text-body">
                            <span class="text-muted">{{ ucfirst($home->pm_type)}} </span>
                            <?php $img = $home->pm_type == 'visa' ? asset('assets/img/visa.svg') : asset('assets/img/mastercard.svg');?>
                            <img class="ml-2" width="35" src="{{$img}}" alt="">
                        </div>
                        <span class="font-bold  text-muted">****-****-****-{{ substr($home->pm_last_four, -4) }}
                        </span>
                        {{-- <span class="font-bold  text-muted">Expires Dec 2024
                        </span> --}}
                    </div>
                </div>
                <div class="mb-4 separator-dashed"></div>
                <h5 class="font-bold fs-14 mb-2 text-body">Subscription Details</h5>
                <table class="table table-borderless billing">
                    <tbody>
                        @if($home->subscription)
                            <tr>
                                <td class="font-bold  text-muted">Subscription ID:	</td>
                                <td class="font-bold  text-body">{{ $home->subscription ? Str::limit($home->subscription->stripe_id, 10, '...') : ''}}</td>
                            </tr>
                            <tr>
                                <td class="font-bold  text-muted">Started:	</td>
                                <td class="font-bold  text-body">{{ $home->subscription && $home->subscription->subscriptionPayment ? date('M d, Y', strtotime($home->subscription->subscriptionPayment->current_period_start)) : '' }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold  text-muted">Status:	</td>
                                <td class="font-bold  text-body"><span class="badge badge-primary mr-2 py-1">{{ $home->subscription ? ucfirst($home->subscription->stripe_status) : ''}}</span></td>
                            </tr>
                            <tr>
                                <td class="font-bold  text-muted">Next Invoice:</td>
                                <td class="font-bold  text-body">{{ $home->subscription && $home->subscription->subscriptionPayment ? date('M d, Y', strtotime($home->subscription->subscriptionPayment->current_period_end)) : '' }}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-warning" colspan="6">No Subscription Found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                @if(Auth::user()->role_id == 2)
                @if($home->subscription && $home->deleted_at == NULL)
                    @if($home->subscription->stripe_status  == 'active')
                        <div>
                            <a  data-toggle="tooltip" data-placement="top"  href="{{ route('subscription-manage', ['id' => $home->id, 'upgrade_downgrade_plan' => 'yes']) }}" class="btn btn-primary btn-sm">Upgrade Subscription</a>
                            @if($plan && $plan->addon_status == 1)
                                <a class="btn btn-primary btn-sm ml-2" data-toggle="tooltip" data-placement="top"   href="{{ route('subscription-manage', ['id' => $home->id, 'add_ons' => 'yes']) }}">Staff Addons</a>
                            @endif
                        </div>
                    @endif
                @endif
                @endif
            </div>
        </div>
    </div>
</div>