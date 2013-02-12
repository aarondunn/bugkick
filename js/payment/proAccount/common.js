(function() {
	if(window.location.protocol === 'file:') {
		alert('stripe.js does not work when included in pages served over file:// URLs. Try serving this page over a webserver. Contact support@stripe.com if you need assistance.');
	}
	var PaymentType = {
		CHARGE: 'charge',
		SUBSCRIPTION: 'subscription'
	};
	function stripeResponseHandler(status, response) {
		if(response.error) {
			handleStripeResponseError(response);
		} else {
			handleStripeResponseSuccess(response);
		}
	}
	function handleStripeResponseError(response) {
		// re-enable the submit button
		$('.submit-button').removeAttr('disabled');
		$('#progressImg').hide();
		// show the errors on the form
		$('.payment-errors').html(response.error.message).show();
	}
	function handleStripeResponseSuccess(response) {
		$('.payment-errors').hide();
		var form = $('#payment-form');
		// token contains id, last4, and card type
		var token = response['id'];
		// insert the token into the form so it gets submitted to the server
		/*token looks like
								{
								  "amount": 1000,
								  "created": 1324958786,
								  "currency": "usd",
								  "id": "tok_vx0U9EpkMXfRpd",
								  "livemode": false,
								  "object": "token",
								  "used": false,
								  "card": {
									"country": "US",
									"cvc_check": "pass",
									"exp_month": 1,
									"exp_year": 2018,
									"last4": "4242",
									"object": "card",
									"type": "Visa"
								  }
								}
		 */
		form.find('.stripe-token').val(token);
		// and submit
		var paymentType=PaymentType.SUBSCRIPTION;
		//var paymentType=PaymentType.CHARGE;
		form.append('<input type="hidden" name="' + paymentType + '" value="1" />').get(0).submit();
	if(false)
		$.post(
			'',
			$.extend(
				{
					paymentType:1
				},
				form.serializeObject()
			),
			function(data) {
				
			}
		)
		.error(function() {
			log(arguments);
		});
	}
	$(function() {
		$('#payment-form').submit(function(event) {
			// disable the submit button to prevent repeated clicks
			$('.submit-button').attr('disabled', 'disabled');
			$('#progressImg').show();
			var chargeAmount = 0; //amount you want to charge, in cents. 1000 = $10.00, 2000 = $20.00 ...
			// createToken returns immediately - the supplied callback submits the form if there are no errors
			Stripe.createToken({
				number: $('.card-number').val(),
				cvc: $('.card-cvc').val(),
				exp_month: $('.card-expiry-month').val(),
				exp_year: $('.card-expiry-year').val()
			}, chargeAmount, stripeResponseHandler);
			return false; // submit from callback
		});
	});
})();