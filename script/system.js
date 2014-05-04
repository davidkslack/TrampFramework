// JS Date format
var DATEFORMAT = 'DD/MM/YYYY HH:mm';

$(document).ready(function(){

	/*********************************************** TIME PICKER **********************************************************/
		// Add date-time to each 'datetime' class
	$(".datetime").datetimepicker({
		format: DATEFORMAT
	});
	/*********************************************** END TIME PICKER ******************************************************/

});