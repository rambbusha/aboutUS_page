<?php
	
	/*
		This script checks if there is already an active session. If not, the user is redirected to the login page.
		
		Every page and script which requires the user to be logged in must include this script before anything else.
	*/
	
	session_start();
	
	if( !isset( $_SESSION[ 'id' ])) {
		// Destroy session properly
		require 'logout.inc.php';
		
		// Redirect to login page
		header( 'HTTP/1.1 403 Forbidden' );
		header( 'Location: ../index.php' );
		
		exit();
	}
	
?>