<?php
	
	/*
		This script declares variables common to all insert and edit scripts.
	*/
	
	
  // Array for error messages relating to particular input fields.
  // Error messages must be indexed as in $_POST[], i.e. same as the 'name' attributes in the HTML <input> tags.
  $_SESSION[ 'input_errors' ] = array();
	
	// Array for backend/server error messages, or any other error messages not particular to any single input field.
	// Error messages must be indexed differently from $_POST[]
	$_SESSION[ 'backend_errors' ] = array();
		
?>