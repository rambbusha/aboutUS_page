<?php
/*
	This script contains a function which outputs any error messages found in $_SESSION[ 'backend_errors' ], and subsequently clears this array.
*/

function print_errors() {
		
	// Loop through and print error messages	
	foreach( $_SESSION[ 'backend_errors' ] as $error => $description) {
		echo '<p class="error_messages">Error: ' . $description . '</p>';
	}
	
	// Clear error messages
	$_SESSION[ 'backend_errors' ] = array();
}

?>