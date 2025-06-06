$(document).ready(function() {

    //ajax add csrf token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    //custom password validation method
    /*$.validator.addMethod("pwcheck", function(value) {
        return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
            && /[a-z]/.test(value) // has a lowercase letter
            && /\d/.test(value) // has a digit
			&& /[=!\-@._*\$\#\%\^\&\(\)\~\`\<\>\/\?\\\|\{\}\[\]\;\:\'\"\,\+]/.test(value)
    }, 'Must be 8 characters long, must contain special character, letters and numbers');*/
	
	$.validator.addMethod("pwcheck", function (value, element) {
		let password = value;
		if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%&])(.{8,20}$)/.test(password))) {
			return false;
		}
		return true;
	}, function (value, element) {
		let password = $(element).val();
		if (!(/^(.{8,20}$)/.test(password))) {
			return 'Password must be between 8 to 20 characters long.';
		}
		else if (!(/^(?=.*[A-Z])/.test(password))) {
			return 'Password must contain at least one uppercase.';
		}
		else if (!(/^(?=.*[a-z])/.test(password))) {
			return 'Password must contain at least one lowercase.';
		}
		else if (!(/^(?=.*[0-9])/.test(password))) {
			return 'Password must contain at least one digit.';
		}
		else if (!(/^(?=.*[!@#$%&])/.test(password))) {
			return "Password must contain special characters from !@#$%&.";
		}
		return false;
	});

	$.validator.addMethod("atLeastOneChecked", function(value, element, params) {
		return $('.medicine_check_box:checked').length > 0;
	}, "At least one medicine must be selected.");
    
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
	
	//custom number validation method
    jQuery.validator.addMethod("integer_no", function (value, element) {
		//var integerPattern = /^[0-9]+$/;
		//var integerPattern = /^\d+$/;
        //return this.optional(element) || integerPattern.test(value);
		return value.match(/^[1-9]\d*$/);
    }, "Please enter a valid number");
    
    // connect it to a css class
    jQuery.validator.addClassRules({
        integer_no : { integer_no : true }    
    }); 

    // Add a custom price validation method
    $.validator.addMethod('valid_price', function (value, element) {
		// Customize the regex pattern based on your requirements
		var pricePattern = /^\$?([1-9]\d{0,2}(,\d{3})*|[1-9]\d*)(\.\d{1,2})?$/;
		var isValidFormat = pricePattern.test(value);
		var numericValue = parseFloat(value.replace(/[$,]/g, ''));
		return this.optional(element) || (isValidFormat && numericValue > 0);
	}, 'Please enter a valid price.');

    // connect it to a css class
    $.validator.addClassRules({
        valid_price : { valid_price : true }    
    }); 
	$.validator.addMethod('valid_doc', function (value, element) {
		// Check if any files are selected
		if (element.files.length === 0) {
			return true; // No files selected, validation fails
		}
		
		// Get the file name from the input element
		var fileName = element.files[0].name;
	
		// Define regex pattern to match file extensions (pdf, doc, or txt)
		var docpattern = /\.(pdf|doc|txt|docx)$/i; // i flag for case-insensitive matching
	
		// Test if the file name matches the pattern
		return docpattern.test(fileName);
	}, "Please select a valid document file (PDF, DOC, DOCX, or TXT).");
	$.validator.addMethod('valid_fax', function (value, element) {
		
	
		// Define regex pattern to match file extensions (pdf, doc, or txt)
		var faxPattern  = /^[+]?[\d\s()-]+$/; // i flag for case-insensitive matching
	
		// Test if the file name matches the pattern
		return faxPattern .test(value);
	}, "Please enter a valid fax number.");

	
	//Jquery validator default setting
	jQuery.validator.setDefaults({
		/*onfocusout: function (e) {
			this.element(e);
		},
		onkeyup: false,*/
		onfocusout: false,
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {                    
				validator.errorList[0].element.focus();
			}
		}, 
		highlight: function (element) {
			jQuery(element).closest('.form-control').addClass('is-invalid');
		},
		unhighlight: function (element) {
			jQuery(element).closest('.form-control').removeClass('is-invalid');
			jQuery(element).closest('.form-control').addClass('is-valid');
		},

		errorElement: 'div',
		errorClass: 'invalid-feedback',
		errorPlacement: function (error, element) {
			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "div" ) );
			}else if ( element.prop( "type" ) === "radio" ) {
				error.insertAfter( element.parent( "div" ) );
			} else {
				error.insertAfter( element );
			}
		},
	});
	
	jQuery.fn.reset = function(fn) {
		return fn ? this.bind("reset", fn) : this.trigger("reset");
	};

    $('#update-password').on('click', function(e){
        e.preventDefault();
		var form = $('#updatePassword');
		form.validate({
            rules: {
                password_confirmation: {
                    equalTo: "#update_password_password"
                },
            },
            messages: {
                password_confirmation: {
                    equalTo: "Please enter the same password again."
                }
            }
        });
		if (form.valid()) {
			form[0].submit();
		}
    });
	
		//validate login form
    $('#loginForm').on('click',function(e){
        e.preventDefault();
        var form = $('#login_form');
		form.validate();
		if (form.valid()) {
			form[0].submit();
		}
    })
	
	//validate Register form
    $('#registerForm').on('click',function(e){
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
			form[0].submit();
		}
    })
	
	//validate forgot password form
    $('#forgetPassword').on('click',function(e){
        e.preventDefault();
        var form = $('#forget_password');
		form.validate();
		if (form.valid()) {
			form[0].submit();
		}
    })
	
	//validate reset password form
    $('#resetPassword').on('click',function(e){
        e.preventDefault();
        var form = $('#reset_password');
		form.validate();
		if (form.valid()) {
			form[0].submit();
		}
    })
	
	 //validate admin user form
    $('#userAdminForm').on('click',function(e){
        e.preventDefault();
        var form = $('#userAdmin_Form');
		form.validate({
			errorPlacement: function(error, element) {
				if (element.hasClass('select2-hidden-accessible')) {
					error.insertAfter(element.next('.select2-container'));
				} else {
					error.insertAfter(element);
				}
			},
		});
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })

    //validate subsription form
    $('#subscription-form').on('click',function(e){
        e.preventDefault();
        var form = $('#subscriptionForm');
		form.validate({
            // rules: {
            //     price: {
            //         min: 1,   // Minimum value
            //     },
            // },
        });
		if (form.valid()) {
			form[0].submit();
		}
    })
	
	//validate subsription form
    $('#careHome_Form').on('click',function(e){
        e.preventDefault();
        var form = $('#careHomeForm');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })
	
	//validate activity form
    $('#activity_Form').on('click',function(e){
        e.preventDefault();
        var form = $('#activityForm');
		
		// form.validate();

		$.validator.addMethod("checkboxRequired", function(value, element) {
			return $('input[name="shift_id[]"]:checked').length > 0;
		}, "Please select at least one shift.");

		
		form.validate({
			rules: {
				// Your validation rules
				'shift_id[]': {
					required: true,
				},
				name: {
					required: true,
				},
				type: {
					required: true,
				},
				duration: {
					required: true,
				},
				frequency: {
					required: true,
				},
				recurrence: {
					required: true,
				},
				description: {
					required: true,
				},
			},
			messages: {
				// Custom error messages if needed
				type: {
					required: "Please select a type.",
				},
			},
			errorPlacement: function(error, element) {
				if (element.hasClass("select2-hidden-accessible")) {
					error.insertAfter(element.next('.select2-container'));
				} else if (element.attr("name") == "shift_id[]") {
					// Place error after the last checkbox in the group
					error.insertAfter(element.closest('.form-group').find('label.checkbox-inline').last());
				} else {
					error.insertAfter(element);
				}
			},
			ignore: [] // Ensure that hidden elements like Select2 are validated
		});

		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })
	
	//validate pages form
    $('#page_Form').on('click',function(e){
        e.preventDefault();
        var form = $('#pageForm');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })
	
	//validate faq form
    $('#faq_Form').on('click',function(e){
        e.preventDefault();
        var form = $('#faqForm');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })
	//validate contact user
	$('#save_contact_us').on('click',function(e){
        e.preventDefault();
		var submit_url = $(this).data('submit-url');
        var form = $('#contactUsForm');
		validator = form.validate();
		if (form.valid()) {
			$('#submit_button_div').hide();
			$('#loader_button_div').show();
			var formData = new FormData(form[0]);
			$.ajax({
				url: submit_url,
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function(res) {
					$('#submit_button_div').show();
					$('#loader_button_div').hide();
					if (res.status == 'success') {
						//reste form data
						$("#contactUsForm").get(0).reset(); 
						clearValidation('contactUsForm');
						$('#alert_message').removeClass('alert-warning').addClass('alert-success');
						$('#messsage_text').text(res.message);
						$('#alert_message').show();
					}else{
						$('#alert_message').removeClass('alert-success').addClass('alert-warning');
						$('#messsage_text').text(res.message);
						$('#alert_message').show();
					}
				},
				error: function(err) {
					
				},
			});
		}
    })

	//validate subsription form
    $('#patientForm').on('click',function(e){
        e.preventDefault();
        var form = $('#patient_Form');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })
	
	//validate Patient Medicine form
    $('#patientMedicineForm').on('click', function(e) {
        e.preventDefault();
        var form = $('#patient_medicine_Form');
		form.validate();
        // Checkbox validation
        var checkboxes = $('.time-checkbox');
        var isChecked = false;

        checkboxes.each(function() {
            if ($(this).is(':checked')) {
                isChecked = true;
                return false; // Exit loop once we find a checked checkbox
            }
        });

        if (!isChecked) {
			$('#check_time_data').text('Please check at least one checkbox.')
            //alert('Please check at least one checkbox.');
            return; // Exit the function if no checkbox is checked
        }else{
			$('#check_time_data').text('')
		}

        // Form validation
       
        if (form.valid()) {
            ShowFormLoading();
            form[0].submit();
        }
    });
    $('#patientDoctorForm').on('click',function(e){
        e.preventDefault();
        var form = $('#patient_doctor_Form');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })
    $('#submit_daily_activity').on('click',function(e){
        e.preventDefault();
        var form = $('#daily-activity-form');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })

	$('#send_push_notification').on('click',function(e){
        e.preventDefault();
        var form = $('#send-push-notification-form');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })

	

	$('#save-med-report').on('click',function(e){
        e.preventDefault();
        var form = $('#daily-medicine-form');

		if($('.medicine_check_box:checked').length == 0){
			$('#medicine_data').text('At least one medicine must be selected.')
			return false;
			
		}else{
			$('#medicine_data').text('')
		}
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })
	$('#save-staff-note').on('click',function(e){
        e.preventDefault();
        var form = $('#staff-note-form');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })

	$('#save-discontinue-medicine-note').on('click',function(e){
        e.preventDefault();
        var form = $('#medicine-discontinue-form');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })
	
	//validate Patient document form
    $('#patientDocumentForm').on('click',function(e){
        e.preventDefault();
        var form = $('#patient_document_Form');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })
    $('#hpmeDocumentForm').on('click',function(e){
        e.preventDefault();
        var form = $('#home_document_Form');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })

	$('#patient_schedule_form').on('click',function(e){
        e.preventDefault();
        var form = $('#schedule_form');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })

	 //validate  assign training form
	 $('#assignTrainingForm').on('click',function(e){
        e.preventDefault();
        var form = $('#assignTraining_Form');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })

	 //validate  training course form
	 $('#trainingCourseForm').on('click',function(e){
        e.preventDefault();
        var form = $('#trainingCourse_Form');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })

	//validate  training course form
	$('#taskForm').on('click',function(e){
        e.preventDefault();
        var form = $('#task_Form');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })

	//validate Upload Training Certificate form
    // $('#uploadCertificateForm').on('click',function(e){
    //     e.preventDefault();
    //     var form = $('#uploadCertificate_Form');
	// 	form.validate();
	// 	if (form.valid()) {
	// 		ShowFormLoading();
	// 		form[0].submit();
	// 	}
    // })
	
	//validate contact user
	$('#submit_expense_form').on('click', function(e) {
		e.preventDefault(); // Prevent the default form submission
		var submit_url = $(this).data('submit-url');
		var form = $('#expenseForm');
	
		// Initialize form validation
		form.validate({
			rules: {
				home_name: {
					required: true,
				},
				type: {
					required: true,
				},
				patient_id: {
					required: true,
				},
				user_id: {
					required: true,
				},
				expense_type: {
					required: true,
				},
				amount: {
					required: true,
				},
				description: {
					required: true,
				},
			},
			messages: {
				type: {
					required: "Please select a type.",
				},
			},
			errorPlacement: function (error, element) {
				if (element.hasClass("select2-hidden-accessible")) {
					error.insertAfter(element.next('.select2-container'));  // Place error after the Select2 container
				} else {
					error.insertAfter(element);  // Default behavior
				}
			},
			// ignore: []
		});
	
		// Manually trigger validation on Select2 value change
		$('#home_name').on('change', function() {
			$(this).valid();  // Trigger validation
		});
		
		// If form is valid, submit it via AJAX
		if (form.valid()) {
			$('#submit_button_div').hide();
			$('#loader_button_div').show();
	
			var formData = new FormData(form[0]);
	
			$.ajax({
				url: submit_url,
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function(res) {
					$('#submit_button_div').show();
					$('#loader_button_div').hide();
	
					if (res.type == 'success') {
						toastAlert(res.type, res.message);
						// Reset form data
						$("#expenseForm").data('validator').resetForm();
						$('#expenseForm')[0].reset();
						$('#home_name').val(null).trigger('change'); // Reset Select2 fields
						$('#home_name').select2('val', ''); // Reset Select2 fields
						// Reload the page
						window.location.reload(); 

						getData(); // Call your function to refresh data
					}
				},
				error: function(err) {
					// Handle the error
					$('#submit_button_div').show();
					$('#loader_button_div').hide();
				}
			});
		}
	});
	
	//validate Patient document form
    
	
	//validate Coupon form
    $('#coupon_Form').on('click',function(e){
        e.preventDefault();
        var form = $('#couponForm');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })
	
	//validate Discount form
    $('#discount_Form').on('click',function(e){
        e.preventDefault();
        var form = $('#discountForm');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })

	//validate Patient Assign Activity Form
    $('#patientActivityForm').on('click',function(e){
        e.preventDefault();
        var form = $('#patient_assign_activity_Form');
		form.validate();
		if (form.valid()) {
			ShowFormLoading();
			form[0].submit();
		}
    })
	
});

function clearValidation(form_id)
{
	$('#'+form_id).find('.is-valid').removeClass('is-valid');
	$('#'+form_id).find('.is-invalid').removeClass('is-invalid');
	$('#'+form_id).find('.invalid-feedback').html('');
	$('#'+form_id).find('.invalid-feedback').remove();
}

function IsEmail(email) {
	var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(!regex.test(email)) {
	   return false;
	}else{
	   return true;
	}
}

function IsValidZipCode(zip) {
	var isValid = /^[0-9]{5}(?:-[0-9]{4})?$/.test(zip);
	if (!isValid) {
		return false;
	} else {
		return true;
	}
}

function IsValidPhoneNumber(phone_no) {
	var isValid = /^[0-9]{10}$/.test(phone_no);
	if (!isValid) {
		return false;
	} else {
		return true;
	}
}
function validDocumentMime(filename)
{
	
	var docpattern = /\.(pdf|doc|txt|docx)$/i; // i flag for case-insensitive matching
	
		// Test if the file name matches the pattern
		var isValid = docpattern.test(filename);
		if (!isValid) {
			return false;
		} else {
			return true;
		}

}
function isValidFaxNumber(faxNumber) {
    // Regular expression pattern for validating fax numbers
    var faxPattern = /^[+]?[\d\s()-]+$/;
    var isValid = faxPattern.test(faxNumber);
	if (!isValid) {
		return false;
	} else {
		return true;
	}
}

function isValidNumber(numberValue) {
    // Regular expression pattern for validating numbers with decimals
    var numberPattern = /^[1-9]\d*(\.\d{1,2})?$/;
    var isValid = numberPattern.test(numberValue);
	if (!isValid) {
		return false;
	} else {
		return true;
	}
}
function isValidIntger(numberValue) {
    // Regular expression pattern for validating numbers with decimals
    var numberPattern = /^[1-9]\d*$/;
    var isValid = numberPattern.test(numberValue);
	if (!isValid) {
		return false;
	} else {
		return true;
	}
}