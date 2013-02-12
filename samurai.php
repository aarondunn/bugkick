<?php die; ?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Samurai API test</title>
	<script type="text/javascript" src="https://samurai.feefighters.com/assets/api/samurai.js"></script>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript">
	/*<![CDATA[*/
	google.load('jquery', '1');
	/*]]>*/
	</script>
	<script type="text/javascript">
		/*<![CDATA[*/
		Samurai.init({
			merchant_key: '952695745c65d49afe12bd78',
			//merchant_key: 'e84803ad9cf61cb21405ab79',
			sandbox: true
		});
		var form = $('#payment-form form').get(0);
		var errorHandler = new Samurai.PaymentErrorHandler(form);
		
		// Bind to the samurai.payment event, which lets you know when a payment_method
		// has been created and gives you a payment_method_token to use in the transaction
		Samurai.on('form', 'payment', function(event, data) {
			// Send the payment_method to the server and let it create the transaction
			$.post('/payment/createTransaction', data.payment_method, function(data) {
				// Parse the transaction response JSON and convert it to an object
				var transaction = $.parseJSON(data).transaction;
				if(transaction.success) {
					// Update the page to display the results
					$('form').children('.results').html(
						'<h3>Your purchase is complete!</h3><h4>'
						+ transaction.payment_method.payment_method.first_name 
						+ ' ' + transaction.payment_method.payment_method.last_name 
						+ ': $' + transaction.amount +' - ' + transaction.description + '</h4>'
					);
					Samurai.trigger('form', 'completed');
				} else {
					// Let the error handler scan the response object for errors,
					// then display these errors
					Samurai.PaymentErrorHandler.for(form).handleErrorsFromResponse(transaction);
				}
			});
		});
		/*]]>*/
	</script>
</head>
<body>
	<div id="payment-form" data-samurai-payment-form class="samurai-standard samurai-placeholders"></div>
</body>
</html>