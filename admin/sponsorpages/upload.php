<?php

/*
	This script uploads sponsor pictures to the server.
*/

require '../include/session.php';
require '../include/parameters.php';
require '../include/error_handling.inc.php';
require_once '../include/pictures.inc.php';

$id = $_GET[ 'id' ];

// Where to relocate on error
$location_on_error = "edit.php?id=" . $id;

// Where to relocate on success
$location_on_success = "main.php";

$target_dir = "../../uploads/sponsors/";
$target_file = $target_dir . basename( $_FILES[ "fileToUpload" ][ "name" ]);
$imageFileType = pathinfo( $target_file, PATHINFO_EXTENSION );
$imageFileType = strtolower( $imageFileType );

// ToDo: Check for empty input, give specific error message

// Check file extension for permitted formats
if( $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
	header( "HTTP/1.1 400 Bad Request" );
	header( "Location: " . $location_on_error );
  
  $_SESSION[ 'backend_errors' ][ 'file_format' ] = "Only JPG, JPEG, PNG & GIF files are allowed.";
	    
	exit();
}

// Check that uploaded file is an actual image
if( isset( $_POST[ "submit" ])) {
	$check = getimagesize( $_FILES[ "fileToUpload" ][ "tmp_name" ]);
	if( !$check ) {
		header( "HTTP/1.1 400 Bad Request" );
		header( "Location: " . $location_on_error );
		
		$_SESSION[ 'backend_errors' ][ 'not_an_image' ] = "File is not an image.";
			    
		exit();
	}
}

// Check file is not too large
if( $_FILES[ "fileToUpload" ][ "size" ] > MAX_FILE_SIZE_LOGOS ) {
	header( "HTTP/1.1 400 Bad Request" );
	header( "Location: " . $location_on_error );
  
  $_SESSION[ 'backend_errors' ][ 'file_size' ] = "File is larger than " . (int)floor( MAX_FILE_SIZE_LOGOS / 1000 ) . " kB.";
	
	exit();
}

// Input validation OK.

// Remove old instance of file
if( !(deletePicture( 'sponsors', $id ))) {
	$_SESSION[ 'backend_errors' ][ 'deletion_failed' ] = "Warning: File upload succeeded, but deletion of old file failed. Please verify that the correct picture is displayed.";
}

// Upload file
if( !move_uploaded_file( $_FILES[ "fileToUpload" ][ "tmp_name" ], $target_dir . $id . '.' . $imageFileType )) {
	header( "HTTP/1.1 500 Internal Server Error" );
	header( "Location: " . $location_on_error );
  
  $_SESSION[ 'backend_errors' ][ 'upload_error' ] = "There was an error uploading your file.";
		
	exit();
}

// Set the picture path in database
require '../include/connection.php';
$path = "uploads/sponsors/" . $id . '.' . $imageFileType;

$sql = "
	UPDATE staff
	SET picture = '$path'
	WHERE id = ?
";

$stmt = $conn->prepare( $sql );
$stmt->bind_param( "i", $id );
$stmt->execute();

if( $stmt->errno ) {
	// If this query fails, the picture was uploaded but the DB update failed. The picture is on the server and thus visible on the admin pages, but the path used to display map markers may be wrong.
	$_SESSION[ 'backend_errors' ][ 'DB_update_warning' ] = "Warning: file upload was successful, but DB update was not. Please verify that the uploaded picture is visible on the map. If not, please re-upload. Error: (" . $stmt->errno . ") " . $stmt->error . ".";
}

$conn->close();
       
// Redirect to main page
header( "HTTP/1.1 200 OK" );
header( "Location: " . $location_on_success );
       
exit();

