
// Add functionality to the address input field
$( document ).ready( function() {
	
	// Handles for the location input & output fields
	var location_field = document.getElementById( 'location_input' );
	var lat_field = document.getElementById( 'lat_output' );
	var long_field = document.getElementById( 'long_output' );
	
	// Add Google's autocomplete to location input field
	var localAutocomplete = new google.maps.places.Autocomplete( location_field );
	
	// Function to update the Lat and Long fields from a given result
	function UpdateLatLong( result ) {
		lat_field.value = result.geometry.location.lat();
		long_field.value = result.geometry.location.lng();
	}
	
	
	function getLatitudeLongitude( callback, address ) {
		if( !address.trim()) {
			lat_field.value = '';
			long_field.value = '';
		} else {
			geocoder = new google.maps.Geocoder();
			if( geocoder ) {
				geocoder.geocode(
					// GeocoderRequest
					{ 'address': address },
					
					// Callback
					function( results, status ) {
						document.getElementById( 'location_error' ).innerHTML = "";
						if( status == google.maps.GeocoderStatus.OK ) {
							callback( results[ 0 ]);
						} else if( status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT ) {
							document.getElementById( 'location_error' ).innerHTML = "Geocoder request limit reached; try again later.";
							lat_field.value = '';
							long_field.value = '';
						} else if( status == google.maps.GeocoderStatus.ZERO_RESULTS ) {
							document.getElementById( 'location_error' ).innerHTML = "Location not found.";
							lat_field.value = '';
							long_field.value = '';
						} else {
							document.getElementById( 'location_error' ).innerHTML = "Error in google.maps.Geocoder.";
							lat_field.value = '';
							long_field.value = '';
						}
					}
				);
			}
		}
	}
	
	// Add event listeners to the location field
	
	// Update Lat and Long when location field loses focus
	// ToDo: If possible, update LatLong before blurring the location field
	location_field.addEventListener( 'blur', function() {
		console.log( "Location field blur" );
		getLatitudeLongitude( UpdateLatLong, location_field.value.trim());
	});
	
	// ToDo: Update LatLong when user presses Enter key on location input
	location_field.addEventListener( 'keyup', function() {
		if ( event.keyCode == 13 ) {
			console.log( "Enter key pressed in Location field." );
			getLatitudeLongitude( UpdateLatLong, location_field.value.trim());
		}
	});
	
	// Prevent form submission by pressing Enter button
	$( document ).on( "keypress", ":input:not( textarea ):not([ type=submit ])", function( event ) {
		if ( event.keyCode == 13 ) {
			event.preventDefault();
		}
	});
	
});

