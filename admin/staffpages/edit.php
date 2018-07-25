<?php

require '../include/session.php';
require '../include/connection.php';
require_once '../include/print_backend_errors.inc.php';
include_once '../include/pictures.inc.php';
require '../headers/header.php';

clearstatcache();
error_reporting(0);

$id = $_GET[ 'id' ];

// Query to retrieve details for the given Staff
$sql = "
	SELECT staff.first_name, staff.last_name, staff.email, staff.phone_numbers,
		staff.role, staff.country, staff.latitude, staff.longitude
	FROM staff
	WHERE id = ?
";

// Prepare query
$stmt = $conn->stmt_init();
if( !( $stmt = $conn->prepare( $sql ))) {
	header( "HTTP/1.1 500 Internal Server Error" );
	header( "Location: edit.php?id=" . $id );
	
	$_SESSION[ 'backend_errors' ][ 'mysqli_prepare' ] = "Failed to prepare mysqli statement: (". $mysqli->errno . ") " . $mysqli->error;
	
	exit();
}

// Bind query
if ( !$stmt->bind_param( "i", $id )) {
	header( "HTTP/1.1 500 Internal Server Error" );
	header( "Location: edit.php?id=" . $id );
	
	$_SESSION[ 'backend_errors' ][ 'mysqli_bind' ] = "Failed to bind mysqli parameters: (" . $mysqli->errno . ") " . $mysqli->error;
	
	$conn->close();
	
	exit();
}

// Execute query
if ( !$stmt->execute()) {
	header( "HTTP/1.1 502 Bad Gateway" );
	header( "Location: edit.php?id=" . $id );
	
	$_SESSION[ 'backend_errors' ][ 'mysqli_execute' ] = "Failed to execute mysqli: (" . $stmt->errno . ") " . $stmt->error;
	
	$stmt->close();
	$conn->close();
	
	exit();
}

// Get result
if( !( $res = $stmt->get_result())) {
	header( "HTTP/1.1 500 Internal Server Error" );
	header( "Location: edit.php?id=" . $id );
	
	$_SESSION[ 'backend_errors' ][ 'mysqli_get_result' ] = "Failed to get mysqli result: (" . $mysqli->errno . ") " . $mysqli->error;
	
	$stmt->close();
	$conn->close();
	
	exit();
}

// Result is expected to have precisely one row.
$result_size = $res->num_rows;
if( $result_size != 1 ) {
	header( "HTTP/1.1 500 Internal Server Error" );
	header( "Location: edit.php?id=" . $id );
	
	$_SESSION[ 'backend_errors' ][ 'size_of_mysqli_result' ] = "Size of mysqli result should be 1, was " . $result_size . ".";
	
	$res->close();
	$stmt->close();
	$conn->close();
	
	exit();
}

$row = $res->fetch_assoc();

// Find search path to Staff's logo
$picture_path = getPicturePath( 'staff', $id );

?>

<div><h3> </h3>
  <h2>Edit Staff</h2>
</div>
<br><br>

<div class="errors-section">
	<?php print_errors(); ?>
</div>

<div class="input-section">
  <form id="edit-form" action="../include/edit_staff.inc.php" method="POST" onsubmit="return validateStaff()">
  <!--form name="form" action="../include/edit_staff.inc.php?id" method="POST" onsubmit="return true"-->
		<input type="hidden" name="id" value="<?php echo $id ?>"><br>
		<label class="label_style" for="first_name_input">First name <span class="error_messages" id="first_name_error"><?php echo $_SESSION[ 'input_errors' ][ 'first_name' ] ?: ""; ?></span></label>
		<input type="text" id="first_name_input" name="first_name" value="<?php echo $row[ 'first_name' ] ?>"><br>
		<label class="label_style" for="last_name_input">Last name <span class="error_messages" id="last_name_error"><?php echo $_SESSION[ 'input_errors' ][ 'last_name' ] ?: ""; ?></span></label>
		<input type="text" id="last_name_input" name="last_name" value="<?php echo $row[ 'last_name' ] ?>"><br>
		<label class="label_style" for="email_input">Mail <span class="error_messages" id="email_error"><?php echo $_SESSION[ 'input_errors' ][ 'email' ] ?: ""; ?></span></label>
		<input type="text" id="email_input" name="email" value="<?php echo $row[ 'email' ] ?>"><br>
  	<label class="label_style" for="phone_input">Phone number(s), separated by commas <span class="error_messages" id="phone_error"><?php echo $_SESSION[ 'input_errors' ][ 'phone_numbers' ] ?: ""; ?></span></label>
		<input type="text" id="phone_input" name="phone_numbers" value="<?php echo $row[ 'phone_numbers' ] ?>"><br>
		<label class="label_style" for="role_input">Role <span class="error_messages" id="role_error"><?php echo $_SESSION[ 'input_errors' ][ 'role' ] ?: ""; ?></span></label>
		<input type="text" id="role_input" name="role" value="<?php echo $row[ 'role' ] ?>"><br>
		<label class="label_style" for="location_input">Location <span class="error_messages" id="location_error"><?php echo $_SESSION[ 'input_errors' ][ 'country' ] ?: ""; ?></span></label>
		<input type="text" id="location_input" name="country" value="<?php echo $row[ 'country' ] ?>"><br>
		<label class="label_style" for="lat_output">Latitude <span class="error_messages" id="latitude_error"><?php echo $_SESSION[ 'input_errors' ][ 'latitude' ] ?: ""; ?></span></label>
		<input type="text" id="lat_output" name="latitude" value="<?php echo $row[ 'latitude' ] ?>" tabindex="-1" readonly><br>
		<label class="label_style" for="long_output">Longitude <span class="error_messages" id="longitude_error"><?php echo $_SESSION[ 'input_errors' ][ 'longitude' ] ?: ""; ?></span></label>
		<input type="text" id="long_output" name="longitude" value="<?php echo $row[ 'longitude' ] ?>" tabindex="-1" readonly><br>
		<button type="submit">Save</button>
  </form>
</div>

<div id="get-back">
  <form action="main.php">
    <button>Cancel (Unsaved changes will be lost)</button>
  </form>
</div>

<div class="change-picture" id="changePic">

  <h2>Change Picture</h2>

  <h3>Current picture</h3>

  <img src="<?php echo $picture_path;?>" alt="Picture" width="600px"><br>
  <form action="upload.php?id=<?php echo $id?>" method="post" enctype="multipart/form-data">
    <h3>Select new image to replace</h3><br>
    <label class="file_container">
      <div id="fileUploadDiv">File to upload</div>
      <input type="file" name="fileToUpload" id="name">
    </label>
    <input type="submit" value="Upload Image" name="submit">
    <br>
    <i>Accepted file types: JPG, JPEG, PNG and GIF. Maximum file size: 50 Mb</i>
  </form>

</div>
<script src="validate_input.js"></script>
<script>
$( 'input[ type=file ]').on( 'change', function() {
	$( "#fileUploadDiv" ).text( $( this ).val().replace( /([^\\]*\\)*/, 'Choosen image: ' ));
});
</script>
<?php
	$res->close();
	$stmt->close();
	$conn->close();

	$_SESSION[ 'input_errors' ] = array();
?>
</body>
</html>