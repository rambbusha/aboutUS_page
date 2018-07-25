<?php

/*
	This script contains a function which sanitises input from HTML forms.
*/

function sanitise_input( $input ) {
	
	global $conn;
	
	$input = trim( $input );
	$input = stripslashes( $input );
	$input = htmlspecialchars( $input );
	$input = $conn->real_escape_string( $input );
	
	return $input;
}

?>