$(document).ready(function(){

	$(".ie").load("js/ie.html"); // Load the IE warning into a div with a class of ie
	
	// Contact form
	$("form#contactform").submit(function(e) {
		
		// Setup any needed variables
		var input_name = $('#name').val(),
		input_email    = $('#email').val(),
		input_subject  = $('#subject').val(),
		input_message  = $('#message').val(),
		response_text  = $('#response');
		
		// Hide any previous response text
		response_text.hide();

		// Change response text to 'loading...'
		response_text.html('Loading...').show();

		// Make AJAX request
		$.post('sendmail.php', {name: input_name, email: input_email, subject: input_subject, message: input_message}, function(data){
			response_text.html(data);
		});

		// Cancel default action
		return false;
		
	});

	
});