<?php

  require 'session.php';
  require 'connection.php';
  require 'sanitise_input.php';
  require 'error_handling.inc.php';
  require 'parameters.php';
	
	// Validate input
	
  $name = $_POST[ 'name' ];
  $name = sanitise_input( $name );
  if( empty( $name )) {
  	$input_errors[ 'name' ] = 'Could not create sponsor: Name is empty.';
  } elseif( strlen( $name ) > MAX_NAME_LENGTH ) {
  	$input_errors[ 'name' ] = 'Could not create sponsor: Name is longer than. ' . MAX_NAME_LENGTH . ' characters.';
  }

  $description = $_POST[ 'description' ];
  $description = sanitise_input( $description );
  if( empty( $description )) {
  	$input_errors[ 'description' ] = 'Could not create sponsor: Description is empty.';
  }

  $address = $_POST[ 'address' ];
  $address = sanitise_input( $address );
  if( empty( $address )) {
  	$input_errors[ 'address' ] = 'Could not create sponsor: Address is empty.';
  }

  $latitude = $_POST[ 'latitude' ];
  $latitude = sanitise_input( $latitude );
  $latitude = str_replace( ',', '.', $latitude );
  if( empty( $latitude )) {
  	$input_errors[ 'latitude' ] = 'Could not create sponsor: Latitude is empty.';
  } else {
		if( $latitude > 90 ){ $latitude = 90; }
		if( $latitude < -90 ){ $latitude = -90; }
  }

  $longitude = $_POST[ 'longitude' ];
	$longitude = sanitise_input( $longitude );
  $longitude = str_replace( ',', '.', $longitude );
  if( empty( $longitude )) {
  	$input_errors[ 'longitude' ] = 'Could not create sponsor: Longitude is empty.';
  } else {
  	// Sanitise absurd values
		if( $longitude > 180 ){ $longitude = 180; }
		if( $longitude < -180 ){ $longitude = -180; }
	}
	
  $email = $_POST[ 'email' ];
  $email = sanitise_input( $email );
  if( empty( $email )) {
  	$input_errors[ 'email' ] = 'Could not create sponsor: Email is empty.';
  } elseif (!filter_var( $email, FILTER_VALIDATE_EMAIL )) {
    $input_errors[ 'email' ] = "Could not create sponsor: Invalid email format.";
  }

  $phone_numbers = $_POST[ 'phone_numbers' ];
  $phone_numbers = sanitise_input( $phone_numbers );
  if( empty( $phone_numbers )) {
  	$input_errors[ 'phone_numbers' ] = 'Could not create sponsor: Phone numbers is empty.';
  }

  $responsible = $_POST[ 'responsible' ];
	$responsible = sanitise_input( $responsible );
	if( empty( $responsible )) {
		$input_errors[ 'responsible' ] = 'Could not create sponsor: No responsible Staff ID given.';
	} elseif( intval( $responsible ) <= 0 ) {
		$input_errors[ 'responsible' ] = 'Could not create sponsor: Responsible Staff ID must be at least 1; was ' . intval( $responsible ) . '.';
	}

	// Check for invalid input
	if( !empty( $input_errors )) {
		header( "HTTP/1.1 400 Bad Request" );
		header( "Location: ../sponsorpages/main.php" );

  	$_SESSION[ 'input_errors' ] = $input_errors;

  	echo "<p>Error: ";
  	echo "<br/><br/>";
		echo var_dump( $_SESSION[ 'input_errors' ]);
		echo "<br/><br/>";
		echo "<a href='http://globuzzer.com/team/admin/sponsorpages/main.php'>Back</a>";

		exit();
	}
	
	// Input validation OK.
	
  // Split phonenumbers and encode in json
  $split_numbers = preg_split( "/[\s,]+/", $phone_numbers );
  $nums = array();
  $max = sizeof( $split_numbers );
  for( $i = 0; $i < $max;$i++ ) {
      $nums[ "Number " . $i ] = $split_numbers[ $i ];
  };
  $phone_numbers = json_encode( $nums );

  $sql = "
  	INSERT INTO sponsors (name, description, address, longitude, latitude, email, phone_numbers, responsable_id)
    VALUES ('$name', '$description', '$address', '$longitude', '$latitude', '$email', '$phone_numbers', '$responsible')
  ";
	
	// Execute query
	if( !$res = $conn->query( $sql )) {
		// Handle Sql errors
		
		header( "HTTP/1.1 502 Bad Gateway" );
		header( "Location: ../sponsorpages/main.php" );
		
		$backend_errors[ 'SQL_error' ] = $conn->error;
  	$_SESSION[ 'backend_errors' ] = $backend_errors;
		
		mysqli_close($conn);

		exit();
	}
	
	// Insertion successful
	
	mysqli_close( $conn );

	header( "HTTP/1.1 200 OK" );
  header( "Location: ../sponsorpages/main.php" );

	exit();
