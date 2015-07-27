$(document).ready(function(){
    $("#regForm input.txt-big-fld").on('focusin', function(){
		$(this).closest('.form-it').removeClass('error-fld').removeClass('success-fld');
		$(this).siblings('.tooltip-cont').hide();
        //$(this).siblings('.tooltip-cont').fadeIn(150);
    });

    $("#regForm input.txt-big-fld").on('focusout', function(){
		var field_id = $(this).attr('id');
		var field_value = $(this).val();
		if(CheckRegistrationForm(field_id)) {
			if(field_value) {
				xajax_CheckRegistration(field_id, field_value);
			}
		}
    });
	
	$('#reg_process').click(function() {
		if(CheckRegistrationForm()) {
			var full_name = $('#full_name').val();
			var emailaddress = $('#emailaddress').val();
			var password = $('#password').val();
			var subdomain = $('#subdomain').val();
			xajax_Registration(full_name, emailaddress, password, subdomain);
		}
		return false;
	});
});


function CheckRegistrationForm(field) {
	var fields_arr = new Array;
	var errors_arr = new Array;
	if(field) {
		fields_arr.push(field);
	} else {
		fields_arr.push('full_name');
		fields_arr.push('emailaddress');
		fields_arr.push('password');
		fields_arr.push('subdomain');
	}
	var form_correct = true;
	for(i=0;i<fields_arr.length;i++) {
		check_field = fields_arr[i];
		check_value = $('#'+check_field).val();
		if(check_field=='full_name') {
			if(check_value.length<3) {
				form_correct = false;
				errors_arr.push('full_name_error');
			}
		} else if(check_field=='emailaddress') {
			if(check_value.length==0) {
				form_correct = false;
				errors_arr.push('emailaddress_error_empty');
			} else if(!validateEmail(check_value)) {
				form_correct = false;
				errors_arr.push('emailaddress_error_incorrect');
			}
		} else if(check_field=='password') {
			if(check_value.length<6) {
				form_correct = false;
				errors_arr.push('password_error');
			}
		} else if(check_field=='subdomain') {
			if(check_value.length==0) {
				form_correct = false;
				errors_arr.push('subdomain_error_empty');
			}
		}
	}
	if(!form_correct) {
		for(i=0;i<errors_arr.length;i++) {
			error_id = errors_arr[i];
			MarkFieldError(error_id);
		}
	}
	return form_correct;
}

function MarkFieldSuccess(field) {
	$('#'+field).siblings('.tooltip-cont').hide();
	$('#'+field).closest('.form-it').removeClass('error-fld').addClass('success-fld');
}

function MarkFieldError(field) {
	$('#'+field).show();
	$('#'+field).closest('.form-it').removeClass('success-fld').addClass('error-fld');
}