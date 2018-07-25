<?php

require_once 'parameters.php';

/*
	This script contains functions for common picture-related tasks.
*/

/*
	Helper function to validate input.
	$type must be a string and the name of a subdirectory to "uploads/"
	$id must be an integer >= 1.
	
	Returns true if all conditions are met, otherwise false.
*/
function validateInput( $type, $id ) {
	if( $type !== 'staff' && $type !== 'sponsors' ) {
		return false;
	}
	if( !is_numeric( $id ) || $id != intval( $id ) || $id < 1 ) {
		return false;
	}
	
	return true;
}


/* Function to get the file path to a picture given a type and an Id.
	
	Returns file path to the first picture found, or the relevant default picture if no picture is found.
	Returns false on invalid input.

	$type		(String) "staff" or "sponsors", i.e. same as the directories for pictures.
	$id			(int) Id number
	
*/
function getPicturePath( $type, $id ) {
	
	// Validate input.
	// ToDo: Maybe better to throw an Exception on error.
	if( !validateInput( $type, $id )) { return false; }
		
	$base_dir = '../../uploads/' . $type . '/';
	$picture_paths = glob( $base_dir . $id . '.*' );
		
	// If no pictures found, return default.
	if( empty( $picture_paths ) && $type === 'staff' ) {
		return "../../admin/img/avatar.png";
	}
	if( empty( $picture_paths ) && $type === 'sponsors' ) {
		return "../../admin/img/logo.png";
	}
	
	// If one or several pictures found, return path to the first one.
	// ToDo: Issue a warning if more than one picture found.
	// ToDo: Throw an Exception if first file found is not actually a picture.
	if( !empty( $picture_paths )) {
		return $picture_paths[ 0 ];
	}
}


/*
	Function to delete a picture given a type and an Id.
	
	Returns true on success, else false.
*/
function deletePicture( $type, $id ) {
	
	// Validate input.
	// ToDo: Maybe better to throw an Exception on error.
	if( !validateInput( $type, $id )) {
		return false;
	}
	
	$base_dir = '../../uploads/' . $type . '/';
	if( !($picture_paths = glob( $base_dir . $id . '.*' ))) {
		return false;
	}
		
	foreach( $picture_paths as $picture_path ) {
		if( !(unlink( $picture_path ))) {
			return false;
		}
	}
	
	return true;
}

?>