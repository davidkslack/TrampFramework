// JS Date format
//var DATEFORMAT-UK = 'DD/MM/YYYY HH:mm';
//var DATEFORMAT-US = 'MM/DD/YYYY HH:mm';
var DATEFORMAT = 'YYYY-MM-DD HH:mm';

$(document).ready(function(){

/*********************************************** TIME PICKER **********************************************************/

	// Add date-time to each 'datetime' class
	$(".datetime").datetimepicker({
		format: DATEFORMAT
	});

/*********************************************** END TIME PICKER ******************************************************/

/*********************************************** JQUERY VALIDATE ******************************************************/

	// Set up the validator defaults to work with bootstrap
	$.validator.setDefaults({
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function(error, element) {
			if(element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});

	// Add jQuery validate to each form with the validate class
	$("form.validate").validate();

/*********************************************** END JQUERY VALIDATE **************************************************/

});