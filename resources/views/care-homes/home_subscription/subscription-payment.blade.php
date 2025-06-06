@extends('layouts.admin')
    
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Buy Subscription Plan</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        @if($staff_capacity)
                            You will be charged ${{ number_format($plan->addons_price, 2) }} for Per Staff Addons in {{ $plan->name }} Plan
                        @else
                            You will be charged ${{ number_format($plan->price, 2) }} for {{ $plan->name }} Plan
                        @endif
                    </div>
    
                    <div class="card-body">
    
                        <form id="payment-form" action="{{ route('subscription.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan" id="plan" value="{{ $plan->id }}">
                            <input type="hidden" name="home_id" id="home_id" value="{{ $home_id }}">
                            <input type="hidden" name="email" id="email" value="{{ $care_home->email }}">

                            @if($staff_capacity)
                                <input type="hidden" name="staff_capacity" id="addon_staff" value="{{ $staff_capacity }}">
                            @endif
    
                            <div class="row">
                                <div class="col-xl-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="">Name</label>
                                        <input type="text" name="name" id="card-holder-name" class="form-control" value="" placeholder="Name on the card" required>
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-xl-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="">Card details</label>
                                        <div id="card-element"></div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12">
                                <hr>
                                    <button type="button" class="btn btn-primary" id="card-button" onclick="createStripeToken();">Purchase</button>
                                </div>
                            </div>
    
                        </form>
    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ config('app.stripe_key') }}")
    
        const elements = stripe.elements()
        const cardElement = elements.create('card')
    
        cardElement.mount('#card-element')
    
        const form = document.getElementById('payment-form')
        const cardBtn = document.getElementById('card-button')
        const cardHolderName = document.getElementById('card-holder-name')
        const email = document.getElementById('email')
        /*form.addEventListener('submit', async (e) => {
            e.preventDefault()
    
            // cardBtn.disabled = true
           const { setupIntent, error } = await stripe.confirmCardSetup(
                cardBtn.dataset.secret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: cardHolderName.value,
                            email:email
                        }   
                    }
                }
            )
    
            if(error) {
                cardBtn.disable = false
            } else {
                let token = document.createElement('input')
                token.setAttribute('type', 'hidden')
                token.setAttribute('name', 'stripeToken')
                token.setAttribute('value', setupIntent.payment_method)
                form.appendChild(token)
                form.submit();
            }
        })*/
		function createStripeToken()
		{
			stripe.createPaymentMethod({ elements, params: {
			  billing_details: {
					name: $('#card-holder-name').val(),
					email: $('#email').val(),
			  },
			},
		  })
		  .then(function(result) {
			  console.log('payment method result', result)
			  stripeResponseHandler(result);
		  });
		}
	
	function stripeResponseHandler(response) {
		console.log('stripeResponseHandler', response);
		var $form = $('#payment-form');
		if (response.error) {
			// alert(response.error)
			$form.find('.payment-errors').text(response.error.message);
			$form.find('.payment-errors').addClass('alert alert-danger');
			$('#submit_button_div').show();
			$('#loader_button_div').hide();
		} else {
			var token = response.paymentMethod.id;
			$form.append($('<input type="hidden" name="stripeToken" />').val(token));
			$('#payment-form').submit();
			//submitFormData();
		}
	};
    </script>
@endsection
