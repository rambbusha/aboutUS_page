function validateStaff() {

  if (validateFirstName() && validateLastName() && ValidateEmail()
  		&& ValidatePhoneNumbers() && validateRole() && validateLocation()
  		&& validateLatitude() && validateLongitude()) {
    return true;
  }
  return false;

}

function validateFirstName() {
  var letters = /^[A-Za-z_ ]+$/;
  if (document.form.first_name.value.length < 1 || document.form.first_name.value.length > 50) {
    document.getElementById('first_name_error').innerHTML= 'Must have at least 1 character and at most 50.';
    document.form.first_name.focus();
    return false;
  }
  if(document.form.first_name.value.startsWith(" ") || !document.form.first_name.value.match(letters))  {
    document.getElementById('first_name_error').innerHTML='First name can not start with space and can not contain special characters';
    document.form.first_name.focus();
    return false;
  }
  document.getElementById('first_name_error').innerHTML='';
  return true;
}

function validateLastName() {
  var letters = /^[A-Za-z_ ]+$/;
  if (document.form.last_name.value.length < 1 || document.form.last_name.value.length > 50) {
    document.getElementById('last_name_error').innerHTML= 'Must have at least 1 character and at most 50.';
    document.form.last_name.focus();
    return false;
  }
  if(document.form.last_name.value.startsWith(" ") || !document.form.last_name.value.match(letters))  {
    document.getElementById('last_name_error').innerHTML='Last name can not start with space and can not contain special characters';
    document.form.last_name.focus();
    return false;
  }
  document.getElementById('last_name_error').innerHTML='';
  return true;
}

function ValidateEmail() {
  var email = document.getElementById("email_input");
  var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  if(!email.value.match(mailformat)){
    document.getElementById('email_error').innerHTML='You have entered an invalid email address!';
    email.focus();
    return false;
  }
  document.getElementById('email_error').innerHTML='';
  return true;
}

function ValidatePhoneNumbers() {
	var phone_numbers_input = document.getElementById( 'phone_input' );
	var phone_numbers = phone_numbers_input.value.trim();
	if( !phone_numbers ) {
		document.getElementById( 'phone_error' ).innerHTML= 'Required.';
		phone_numbers_input.focus();
		return false;
	}
	document.getElementById( 'phone_error' ).innerHTML= '';
	return true;
}

function validateRole() {
  var letters = /^[A-Za-z_ ]+$/;
  var role = document.getElementById('role_input');
  if (role.value.length <4 || role.value.length >= 200) {
    document.getElementById('role_error').innerHTML='Role must have more then 3 characters and less then 200';
    role.focus();
    return false;
  }
  if(role.value.startsWith(" ") || !role.value.match(letters))  {
    document.getElementById('role_error').innerHTML='Role must have alphabet characters only';
    role.focus();
    return false;
  }
  document.getElementById('role_error').innerHTML='';
  return true;
}

function validateLocation() {
  var location = document.getElementById("location_input");
  if (location.value.length === 0) {
    document.getElementById('location_error').innerHTML='Location can not be empty';
    location.focus();
    return false;
  }
  document.getElementById('location_error').innerHTML='';
  return true;
}

function validateLatitude() {
  var numbers = /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/;
  if (!document.form.latitude.value.match(numbers)) {
    document.getElementById('latitude_error').innerHTML='Latitude must be numbers and between -90 and 90 degrees inclusive.';
    document.form.latitude.focus();
    return false;
  }
  document.getElementById('latitude_error').innerHTML='';
  return true;
}

function validateLongitude() {
  var numbers = /^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d{1,6}/;
  if (!document.form.longitude.value.match(numbers)) {
    document.getElementById('longitude_error').innerHTML='Longitude must be numbers between -180 and 180 degrees inclusive.';
    document.form.longitude.focus();
    return false;
  }
  document.getElementById('longitude_error').innerHTML='';
  return true;
}
