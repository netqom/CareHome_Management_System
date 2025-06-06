@if (!$records->isEmpty())
    {{-- @foreach ($records->data as $key => $record) --}}
    @foreach ($records as $key => $record)
		{{-- @php  $plan = getSubscriptionPlanData($record->id); @endphp --}}
        <tr>
            {{-- <td><input type="checkbox" class="i-checks" name="input[]"></td> --}}
            <td>{{ $key + 1 }}</td>
            <td>{{ $record->name }}</td>
            <!--td>{{ $record->description ? Str::words($record->description, 10,'...') : '-'}}
			</td-->
            <td>{{ $record->duration == 1 ? 'Monthly' : ($record->duration == 2 ? 'Yearly' : 'Daily') }}</td>
            <td>${{ $record->price}}</td>
            <td>
                @php
					$cls_name = 'label-primary';
					$text = 'Active';
					if ($record->status != 1) {
                        $text = 'Inactive';
                        $cls_name = 'label-warning';
                    }
                @endphp
                <span class="label {{ $cls_name }}"> {{ $text }}</span>
            </td>
            <td>
				<div class="text-nowrap">
					{{-- <a href="{{ route('users.show', $record->id) }}" class="btn-primary btn btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a> --}}
					<a href="{{ route('subscription_plans.getEdit', $record->id) }}" class="btn-warning btn btn-sm"><i class="fa fa-edit"></i></a>
                    <a href="{{ route('subscription_plans.subscriptionPrice', $record->id) }}" class="btn-warning btn btn-sm" title="Update Subscription Price"><i class="fa fa-usd"></i></a>
                    {{-- <a href="javascript:void(0);" class="btn-warning btn btn-sm updatePrice" title="Update Subscription Price" data-id="{{$record->id}}" data-stripe-product="{{$record->stripe_product_id}}"><i class="fa fa-usd"></i></a> --}}
					{{-- <form action="{{ route('users.destroy', $record->id) }}" method="post" id="delete_form_{{ $record->id }}" class="d-none">
						@csrf
						@method("DELETE")
					</form>
					<a href="javascript:;" class="btn-danger btn btn-sm confirm_delete" role="button" data-item-id="{{ $record->id }}" data-item-type="user"><i class="fa fa-trash" aria-hidden="true"></i>
</a> --}}
				</div>
            </td>
        </tr>
		<tr>
			<td class="text-warning text-center" colspan="7"> 
				Per Staff Addons Price: {{ amountFormat($record->addons_price) }}
			</td>
		</tr>
    @endforeach
@else
    <tr>
        <td class="text-warning text-center" colspan="7">No Data Found</td>
    </tr>
@endif
<script type="text/javascript">
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });

    $('.updatePrice').on('click', function(){
        var id = $(this).attr('data-id');
        var stripe_product_id = $(this).attr('data-stripe-product');
        // Perform Ajax request to update stripe new price
        addCardLoader('.ibox-content');
        $.ajax({
            url: '{{ route('subscription_plans.update-subscription-price') }}',
            type: "POST",
            data: {id:id,stripe_product_id:stripe_product_id},
        }).done(function(response) {
            if (response.type == 'error') {
                toastAlert(response.type, response.msg);
            } else {
                removeCardLoader();
                toastAlert(response.type, response.msg);
                window.location.reload();
            }
        });
    })
</script>							