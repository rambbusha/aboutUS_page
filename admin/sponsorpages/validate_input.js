function validateSponsor() {
	document.getElementById( 'location_input' ).blur(); 
		
  if (validateSponsorName() && validateDescription() && validateEmail()
  			&& validateLocation() && validateLatitude() && validateLongitude()
  			&& validatePhoneNumber() && validateResponsible()) {
          return true;
  }
  return false;

}

function validateSponsorName() {
  var letters = /^[A-Za-z_ ]+$/;
  var sponsorName = document.getElementById('sponsor_name');
  if (sponsorName.value.length < 1 || sponsorName.value.length > 50) {
    document.getElementById('name_error').innerHTML='Must have at least 1 character and at most 50.';
    sponsorName.focus();
    return false;
  }
  if(sponsorName.value.startsWith(" ") || !sponsorName.value.match(letters))  {
    document.getElementById('name_error').innerHTML='Sponsor name can not start with space and can not contain special characters';
    sponsorName.focus();
    return false;
  }
  document.getElementById('name_error').innerHTML='';
  return true;
}

function validateDescription() {
  var description = document.getElementById('description_id');
  if (description.value.length <2 || description.value.length >= 100) {
    document.getElementById('description_error').innerHTML='Description name must have more then 1 characters and less then 100';
    description.focus();
    return false;
  }
  if(description.value.startsWith(" "))  {
    document.getElementById('description_error').innerHTML='Description name can not start with space and can not contain special characters';
    description.focus();
    return false;
  }
  document.getElementById('description_error').innerHTML='';
  return true;
}

function validateLocation() {
  var location = document.getElementById( 'location_input' );
  if( location.value.trim().length === 0 ) {
    document.getElementById( 'location_error' ).innerHTML = "Required.";
    location.focus();
    return false;
  }
  document.getElementById( 'location_error' ).innerHTML = "";
  return true;
}

function validateLatitude() {
	var latitude = document.getElementById( 'lat_output' );
	if( latitude.value.trim() == '' ) {
		document.getElementById( 'lat_error' ).innerHTML = "Required.";
		latitude.focus();
		return false;
	}
	document.getElementById( 'lat_error' ).innerHTML = "";
	return true;
}

function validateLongitude() {
	var latitude = document.getElementById( 'long_output' );
	if( latitude.value.trim() == '' ) {
		document.getElementById( 'long_error' ).innerHTML = "Required.";
		latitude.focus();
		return false;
	}
	document.getElementById( 'long_error' ).innerHTML = "";
	return true;
}

function validateEmail() {
  var email = document.getElementById("email_id");
  var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  if(!email.value.match(mailformat)){
    document.getElementById('email_error').innerHTML='You have entered an invalid email address!';
    email.focus();
    return false;
  }
  document.getElementById('email_error').innerHTML='';
  return true;
}

function validatePhoneNumber() {
  var phoneNumber = document.getElementById("phone_id");
  if (phoneNumber.value.length <2 || phoneNumber.value.length >= 100) {
    document.getElementById('phone_error').innerHTML='Phone number name must have more then 1 characters and less then 100';
    phoneNumber.focus();
    return false;
  }
  if(phoneNumber.value.startsWith(" "))  {
    document.getElementById('phone_error').innerHTML='Description name can not start with space';
    phoneNumber.focus();
    return false;
  }
  document.getElementById('phone_error').innerHTML='';
  return true;
}

function validateResponsible() {
  var responsibleOption = document.getElementById("responsable_id");
  if (responsibleOption.value <= 0) {
    document.getElementById('responsible_error').innerHTML='Responsible person must be choosen';
    responsibleOption.focus();
    return false;
  }
  document.getElementById('responsible_error').innerHTML='';
  return true;
}
