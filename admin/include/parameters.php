<?php
	
	/*
		A list of common parameters to be shared between various scripts.
	*/	
	
	
	// Maximum allowed file size for logos, in bytes.
	// This includes Sponsor logos and Staff profile pictures.
	// ToDo: What happens if this is bigger than upload_max_filesize in php.ini? Possibly throw an Exception in that case.
	const MAX_FILE_SIZE_LOGOS = 300000;
	
	
	// Maximum allowed number of characters in Staff and Sponsor names.
	const MAX_NAME_LENGTH = 50;
	
	
?>