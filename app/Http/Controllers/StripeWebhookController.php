<?php

namespace App\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Cashier\Events\WebhookReceived;
use Laravel\Cashier\Events\WebhookHandled;
use Str;


class StripeWebhookController extends CashierController
{
	
	
	/**
	 * Handle a Stripe webhook call.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function handleWebhook(Request $request)
	{
		$payload = json_decode($request->getContent(), true);
		$method = 'handle'.Str::studly(str_replace('.', '_', $payload['type']));

		WebhookReceived::dispatch($payload);

		if (method_exists($this, $method)) {
			$response = $this->{$method}($payload);

			WebhookHandled::dispatch($payload);

			return $response;
		}

		return $this->missingMethod($payload);
	}
}
