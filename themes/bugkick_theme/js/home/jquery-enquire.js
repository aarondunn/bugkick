// JavaScript Document

$(document).ready(function(){
	//global vars
	var form = $("#form-enquire");
	var en_name = $("#fullname");
	var en_email = $("#email");
	var en_subject = $("#subject");
	var en_message = $("#message");
	
	//On blur
	en_name.blur(validateEN_Name);
	en_email.blur(validateEN_Email);
	en_subject.blur(validateEN_Subject);
	en_message.blur(validateEN_Message);
	//On key press
	en_name.keyup(validateEN_Name);
	en_subject.keyup(validateEN_Subject);
	en_message.keyup(validateEN_Message);
	//On Submitting
	form.submit(function(){
		if(validateEN_Email() & validateEN_Name() & validateEN_Subject() & validateEN_Message())
			return true
		else
			return false;
	});
	
	//validation functions
	function validateEN_Email(){
		//testing regular expression
		var a = $("#email").val();
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
		//if it's valid email
		if(filter.test(a)){
			en_email.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			en_email.addClass("error");
			return false;
		}
	}
	
	function validateEN_Name(){
		//if it's NOT valid
		if(en_name.val().length < 1 || en_name.val()=='Full Name' ){
			en_name.addClass("error");
			return false;
		}
		//if it's valid
		else{
			en_name.removeClass("error");
			return true;
		}
	}
	
	
	function validateEN_Subject(){
		//if it's NOT valid
		if(en_subject.val().length < 1 || en_subject.val()=='Subject Line' ){
			en_subject.addClass("error");
			return false;
		}
		//if it's valid
		else{
			en_subject.removeClass("error");
			return true;
		}
	}

	function validateEN_Message(){
		//if it's NOT valid
		if(en_message.val().length < 1 ){
			en_message.addClass("error");
			return false;
		}
		//if it's valid
		else{
			en_message.removeClass("error");
			return true;
		}
	}


});

$(document).ready(function(){
	$('#form-enquire').ajaxForm(function(data) {
		if (data==201){alert('Your enquiry has been submitted. We will get back to your shortly.'); 
		$('#form-enquire').resetForm();}
	});
});