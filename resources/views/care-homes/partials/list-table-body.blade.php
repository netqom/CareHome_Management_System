@if (!$records->isEmpty())
    @foreach ($records as $key => $record)
        @php
            $cls_name = 'label-primary';
            $text = 'Active';
            if ($record->status == 1 && $record->deleted_at == null) {
                $text = 'Active';
                $cls_name = 'label-primary';
            } elseif ($record->status == 0 && $record->deleted_at == null) {
                $text = 'Inactive';
                $cls_name = 'label-warning';
            } elseif ($record->deleted_at != null) {
                $text = 'Deleted';
                $cls_name = 'label-danger';
            }
            $stripe_status = isset($record->getCareHomeSubscription->stripe_status)
                ? $record->getCareHomeSubscription->stripe_status
                : '';
        @endphp
        <tr style="color: {{ $stripe_status != 'active' ? '#9d9d9d' : '' }}">
            <!--td><input type="checkbox" class="i-checks" name="input[]"></td-->
            <td>{{ $key + 1 }}</td>

            <td>{{ $record->name }}</td>
            <td>{{ getPlanName($record->subscription_plan_id) }}</td>
            @if (Auth::user()->role_id != 2)
                <td>{{ $record->admin ? $record->admin->name : '' }}</td>
            @endif
            <td>{{ $record->contact_no }}</td>
            <td>{{ $record->street }}, {{ $record->city }}, {{ $record->state }}, {{ $record->zip_code }}</td>
            @if (Auth::user()->role_id != 2)
                <td>{{ countAddedStaff($record->id) . ' / ' . checkUserAllowedCapacity($record->id) }} </td>
                <td>{{ getCareHomePatientCount($record->id) }} </td>
            @endif
            {{-- <td>{{ $record->city }}</td>
            <td>{{ $record->state }}</td>
            <td>{{ $record->zip_code }}</td> --}}
            <td>

                <span class="label {{ $cls_name }}"
                    style="background-color: {{ $stripe_status != 'active' ? '#9d9d9d' : '' }}">
                    {{ $text }}</span>
            </td>
            <td>
                <div class="text-nowrap">
                    <a href="{{ route('homes.show', $record->id) }}" data-toggle="tooltip" data-placement="top"
                        title="View Care Home"
                        class="btn-primary btn btn-sm mb-0 {{ $stripe_status != 'active' ? 'disabled' : '' }}"><i
                            class="fa fa-eye" aria-hidden="true"></i></a>
                    @if (Auth::user()->role_id == 2 && $record->deleted_at == null)
                        <a href="{{ route('get-activity-time-form', ['home_id' => $record->id]) }}"
                            data-toggle="tooltip" data-placement="top" title="Adjust Shift Time"
                            class="btn-info btn btn-sm mb-0 {{ $stripe_status != 'active' ? 'disabled' : '' }}"><i
                                class="fa fa-clock-o"></i></a>
                        <a href="{{ route('homes.edit', $record->id) }}" data-toggle="tooltip" data-placement="top"
                            title="Edit Care Home"
                            class="btn-warning btn btn-sm mb-0 {{ $stripe_status != 'active' ? 'disabled' : '' }}"><i
                                class="fa fa-edit"></i></a>
                        <form action="{{ route('homes.destroy', $record->id) }}" method="post"
                            id="delete_form_{{ $record->id }}" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                        <a href="javascript:;" data-toggle="tooltip" data-placement="top"
                            class="btn-danger btn btn-sm mb-0 confirm_delete {{ $stripe_status != 'active' ? 'disabled' : '' }}"
                            role="button" data-item-id="{{ $record->id }}" data-item-type="care home"
                            title="Delete Care Home"><i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                        {{-- @if ($record->getCareHomeSubscription) --}}
                        @php $careHomeSubscriptions = $record->getCareHomeActiveSubscription($record->id); @endphp
                        @if($careHomeSubscriptions)
                            @if ($careHomeSubscriptions->stripe_status != 'active')
                                <a href="{{ route('subscription-manage', $record->id) }}?new_plan=yes"
                                    class="btn-info btn btn-sm mb-0" data-toggle="tooltip" data-placement="top"
                                    title="Buy Subscription">
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                            @endif
                        @endif
                        <!--a href="{{ route('care-homes-subscription.index', $record->id) }}" class="btn-info btn btn-sm mb-0">View Subscription Plan</a-->
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td class="text-warning text-center" colspan="9">No Data Found</td>
    </tr>
@endif
<script>
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
</script>
