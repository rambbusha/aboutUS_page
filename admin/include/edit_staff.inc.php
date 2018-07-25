<?php
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);
	//error_reporting(E_ALL);
	
	require 'session.php';
  require 'connection.php';
	require 'sanitise_input.php';
	require 'error_handling.inc.php';
  require 'parameters.php';
	
	$id = $_POST[ 'id' ];
	
	// Validate input
	
  $first_name = $_POST[ 'first_name' ];
  $first_name = sanitise_input( $first_name );
	if( empty( $first_name )) {
		$input_errors[ 'first_name'] = 'Could not update staff: First name is empty.';
	} elseif( strlen( $first_name ) > MAX_NAME_LENGTH ) {
		$input_errors[ 'first_name'] = 'Could not update staff: First name is longer than ' . MAX_NAME_LENGTH . ' characters.';
	} elseif ( preg_match( '/[0-9]/', $first_name )) {
		$input_errors[ 'first_name'] = 'Could not update staff: First name contains a number [0-9].';
	}

	$last_name = $_POST[ 'last_name' ];
  $last_name = sanitise_input( $last_name );
	if( empty( $last_name )) {
		$input_errors[ 'last_name' ] = 'Could not update staff: Last name is empty.';
	} elseif( strlen( $last_name ) > MAX_NAME_LENGTH ) {
		$input_errors[ 'last_name'] = 'Could not update staff: Last name is longer than ' . MAX_NAME_LENGTH . ' characters.';
	} elseif ( preg_match( '/[0-9]/', $last_name )) {
		$input_errors[ 'last_name' ] = 'Could not update staff: Last name contains a number [0-9].';
	}

	$email = $_POST[ 'email' ];
  $email = sanitise_input( $email );
  if( empty( $email )) {
  	$input_errors[ 'email' ] = 'Could not update staff: Email is empty.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $input_errors[ 'email' ] = "Could not update staff: Invalid email format.";
  }
  
  $phone_numbers = $_POST[ 'phone_numbers' ];
  $phone_numbers = sanitise_input( $phone_numbers );
	if( empty( $phone_numbers )) {
  	$input_errors[ 'phone_numbers' ] = 'Could not create staff: Phone numbers is empty.';
  }

	$role = $_POST[ 'role' ];
  $role = sanitise_input( $role );
	if( empty( $role )) {
		$input_errors[ 'role'] = 'Could not update staff: Role is empty.';
	}

	$country = $_POST[ 'country' ];
  $country = sanitise_input( $country );
	if( empty( $country )) {
		$input_errors[ 'country'] = 'Could not update staff: Location is empty.';
	}

	$latitude = $_POST[ 'latitude' ];
  $latitude = sanitise_input( $latitude );
  $latitude = str_replace( ',', '.', $latitude );
  if( empty( $latitude )) {
  	$input_errors[ 'latitude' ] = 'Could not update staff: Latitude is empty.';
  } else {
		if( $latitude > 90 ){ $latitude = 90; }
		if( $latitude < -90 ){ $latitude = -90; }
  }

  $longitude = $_POST[ 'longitude' ];
	$longitude = sanitise_input( $longitude );
  $longitude = str_replace( ',', '.', $longitude );
  if( empty( $longitude )) {
  	$input_errors[ 'longitude' ] = 'Could not update staff: Longitude is empty.';
  } else {
  	// Sanitise absurd values
		if( $longitude > 180 ){ $longitude = 180; }
		if( $longitude < -180 ){ $longitude = -180; }
	}

	// Check for invalid input
	if( !empty( $input_errors )) {
		header( "HTTP/1.1 400 Bad Request" );
  	header( "Location: ../staffpages/edit.php?id=" . $id );
		
  	$_SESSION[ 'input_errors' ] = $input_errors;
		
		exit();
	}
	
	// Input validation OK.
	
  $sql = "
  	UPDATE staff
  	SET first_name = '$first_name',
  		last_name = '$last_name',
  		email = '$email',
  		phone_numbers = '$phone_numbers',
  		role = '$role',
  		country = '$country',
  		latitude = '$latitude',
  		longitude = '$longitude' 
  	WHERE id = $id
  ";
  
	// Execute Sql query
  if ( !$conn->query( $sql )) {
  	// Handle Sql errors
		header( "HTTP/1.1 502 Bad Gateway" );
		header( "Location: ../staffpages/edit.php?id=" . $id );
		
		$backend_errors[ 'SQL_error' ] = $conn->error;
  	$_SESSION[ 'backend_errors' ] = $backend_errors;
		
		mysqli_close($conn);

		exit();
  }

	//Handle success

  mysqli_close($conn);

  header( "HTTP/1.1 200 OK" );
  header( "Location: ../staffpages/main.php" );

  exit();
