$(document).ready(function() {

    //ajax add csrf token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    //custom password validation method
    $.validator.addMethod("pwcheck", function(value) {
        return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
            && /[a-z]/.test(value) // has a lowercase letter
            && /\d/.test(value) // has a digit
    }, 'Must be 8 characters long, must contain special character, letters and numbers');
    
    // connect it to a css class
    jQuery.validator.addClassRules({
        pwcheck : { pwcheck : true }    
    });

    
    //custom email validation method
    jQuery.validator.addMethod("validate_email", function(value, element, param) {
        return value.match(/^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
    },'Please enter a valid Email');
    
    // connect it to a css class
    jQuery.validator.addClassRules({
        validate_email : { validate_email : true }    
    });
    
    //custom phone no validation method
    jQuery.validator.addMethod("validate_phone", function (phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 9 &&
                // phone_number.match(/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/);
                phone_number.match(/^[0-9]{10}$/);
    }, "Please enter a valid phone number");
    
    // connect it to a css class
    jQuery.validator.addClassRules({
        validate_phone : { validate_phone : true }    
    }); 

    
    $('#account_sign_up').on('click', function(e){
        e.preventDefault();
		var form = $('#register_form');
		form.validate({
            rules: {
                password_confirmation: {
                    equalTo: "#password"
                },
            },
            messages: {
                password_confirmation: {
                    equalTo: "Please enter the same password again."
                }
            }
        });
		if (form.valid()) {
			$('#register_form').submit();
		}
    });

    $('#reset-password').on('click', function(e){
        e.preventDefault();
		var form = $('#resetPassword');
		form.validate({
            rules: {
                password_confirmation: {
                    equalTo: "#password"
                },
            },
            messages: {
                password_confirmation: {
                    equalTo: "Please enter the same password again."
                }
            }
        });
		if (form.valid()) {
			$('#resetPassword').submit();
		}
    });

});