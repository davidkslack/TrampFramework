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

});