<?php

require 'session.php';
require 'connection.php';
require 'error_handling.inc.php';

$tuple_id = $_GET[ 'id' ];

// Prepare a statement
if( !($stmt = $conn->prepare( "DELETE FROM sponsors WHERE id = ?" ))) {
	header( "HTTP/1.1 502 Bad Gateway" );
	header( "Location: ../sponsorpages/main.php" );
	
	$_SESSION[ 'backend_errors' ][ 'DB' ] = "Failed to prepare statement: " . htmlspecialchars( $conn->error );
	
	$conn->close();
	
	exit();
}

// Bind variables
if( !$stmt->bind_param( "i", $tuple_id )) {
	header( "HTTP/1.1 502 Bad Gateway" );
	header( "Location: ../sponsorpages/main.php" );
	
	$_SESSION[ 'backend_errors' ][ 'DB' ] = "Failed to bind statement: " . htmlspecialchars( $conn->error );
	
	$conn->close();
	
	exit();
}

// Execute statement
if( !$stmt->execute()) {
	header( "HTTP/1.1 502 Bad Gateway" );
	header( "Location: ../sponsorpages/main.php" );
	
	$_SESSION[ 'backend_errors' ][ 'DB' ] = "Failed to execute statement: " . $conn->errno . ", " . htmlspecialchars( $conn->error );
	
	$conn->close();
	
	exit();
}

// Deletion successful

// Delete profile picture
foreach( glob( '../../uploads/sponsors/' . $_GET[ 'id' ] . '.*' ) as $deleteMe ) {
	if( !unlink( $deleteMe )) {
		$_SESSION[ 'backend_errors' ][ 'delete_sponsor_logo_warning' ] = "Warning: Sponsor deletion was successful, but image deletion failed for Sponsor with ID " . $tuple_id . ". Please notify support.";
	}
}

// Handle success
$conn->close();

header( "HTTP/1.1 200 OK" );
header( "Location: ../sponsorpages/main.php" );

exit();

