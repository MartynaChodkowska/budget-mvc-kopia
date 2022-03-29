/**
 * Add jQuery Validation plugin method for a valid password
 *
 * Valid passwords contain at least one letter and one number.
 */
$.validator.addMethod('validPassword',
	function(value, element, param) {
		if (value != ''){
			if (value.match(/.*[a-z]+.*/i) == null){
				return false;
			}
			if (value.match(/.*\d+.*/) == null){
				return false;
			}
		}
	
		return true;
	},
	'Must contain at least one letter and one number'
		
);


/**
 *  Add a current date into input typ date
 */
 function getDate() {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
  
	if(dd<10) {
		dd = '0'+dd
	} 
  
	if(mm<10) {
		mm = '0'+mm
	} 
  
	today = yyyy + '/' + mm + '/' + dd;
	console.log(today);
	document.getElementById("datePicker").value = today;
  }
  
  
  window.onload = function() {
	getDate();
  };