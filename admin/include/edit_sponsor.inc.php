<?php
	/*
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	*/
	
	require 'session.php';
  require 'connection.php';
  require 'sanitise_input.php';
  require 'error_handling.inc.php';
  require 'parameters.php';
	
  $id = $_POST[ 'id' ];
  
	// Validate input
	
  $name = $_POST[ 'name' ];
  $name = sanitise_input( $name );
  if( empty( $name )) {
  	$input_errors[ 'name' ] = 'Could not update sponsor: Name is empty.';
  } elseif( strlen( $name ) > MAX_NAME_LENGTH ) {
  	$input_errors[ 'name' ] = 'Could not update sponsor: Name is longer than ' . MAX_NAME_LENGTH . ' characters.';
  }
  
  $description = $_POST[ 'description' ];
  $description = sanitise_input( $description );
  if( empty( $description )) {
  	$input_errors[ 'description' ] = 'Could not update sponsor: Description is empty.';
  }

  $address = $_POST[ 'address' ];
  $address = sanitise_input( $address );
  if( empty( $address )) {
  	$input_errors[ 'address' ] = 'Could not update sponsor: Address is empty.';
  }

  $latitude = $_POST[ 'latitude' ];
  $latitude = sanitise_input( $latitude );
  $latitude = str_replace( ',', '.', $latitude );
	if( empty( $latitude )) {
  	$input_errors[ 'latitude' ] = 'Could not update sponsor: Latitude is empty.';
  } else {
		if( $latitude > 90 ){ $latitude = 90; }
		if( $latitude < -90 ){ $latitude = -90; }
  }

  $longitude = $_POST[ 'longitude' ];
  $longitude = sanitise_input( $longitude );
  $longitude = str_replace( ',', '.', $longitude );
	if( empty( $longitude )) {
  	$input_errors[ 'longitude' ] = 'Could not update sponsor: Longitude is empty.';
  } else {
  	// Sanitise absurd values
		if( $longitude > 180 ){ $longitude = 180; }
		if( $longitude < -180 ){ $longitude = -180; }
	}  
	
  $email = $_POST[ 'email' ];
  $email = sanitise_input( $email );
  if( empty( $email )) {
  	$input_errors[ 'email' ] = 'Could not update sponsor: Email is empty.';
  } elseif (!filter_var( $email, FILTER_VALIDATE_EMAIL )) {
    $input_errors[ 'email' ] = "Could not update sponsor: Invalid email format.";
  }
  
  $phone_numbers = $_POST[ 'phone_numbers' ];
  $phone_numbers = sanitise_input( $phone_numbers );
  if( empty( $phone_numbers )) {
  	$input_errors[ 'phone_numbers' ] = 'Could not update sponsor: Phone numbers is empty.';
  }
	
  $responsible = $_POST[ 'responsible' ];
	$responsible = sanitise_input( $responsible );
	if( empty( $responsible )) {
		$input_errors[ 'responsible' ] = 'Could not update sponsor: No responsible Staff ID given.';
	} elseif( intval( $responsible ) <= 0 ) {
		$input_errors[ 'responsible' ] = 'Could not update sponsor: Responsible Staff ID must be at least 1; was ' . intval( $responsible ) . '.';
	}
	
	// Check for invalid input
	if( !empty( $input_errors )) {
		header( "HTTP/1.1 400 Bad Request" );
  	header( "Location: ../sponsorpages/edit.php?id=" . $id );
		
  	$_SESSION[ 'input_errors' ] = $input_errors;
		
		exit();
	}
	
	// Input validation OK.
		
  // Split phonenumbers and encode in json
  $split_numbers = preg_split( "/[\s,]+/", $phone_numbers );
  $nums = array();
  $max = sizeof( $split_numbers );
  for($i = 0; $i < $max;$i++) {
      $nums[ "Number " . $i ] = $split_numbers[ $i ];
  };
  $phone_numbers = json_encode( $nums );

  $sql = "
  	UPDATE sponsors
    SET name = '$name',
			description = '$description',
			address = '$address',
			latitude = '$latitude',
			longitude = '$longitude',
			email = '$email',
			phone_numbers = '$phone_numbers',
			responsable_id = '$responsible'
		WHERE id = $id
	";
	
	// Execute query
  if ( !$conn->query( $sql )) {
  	//Handle SQL errors
  	
  	header( "HTTP/1.1 502 Bad Gateway" );
  	header( "Location: ../sponsorpages/edit.php?id=" . $id );
  	  	
  	$backend_errors[ 'SQL_error' ] = $conn->error;
  	$_SESSION[ 'backend_errors' ] = $backend_errors;
  			
		mysqli_close($conn);
  	exit();
  }
	
	// Edit successful
	
  mysqli_close($conn);
	
	header( "HTTP/1.1 200 OK" );
  header( "Location: ../sponsorpages/main.php" );
    		
  exit();
  
  