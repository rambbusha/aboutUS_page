<?php
require '../include/session.php';
require_once '../include/database.php';
require '../include/connection.php';
require_once '../include/print_backend_errors.inc.php';
include_once '../include/pictures.inc.php';
require '../headers/header.php';

clearstatcache();
error_reporting( 0 );

$id = $_GET[ 'id' ];

// Get Sponsor info from DB
$sql = "
	SELECT sponsors.name, sponsors.description, sponsors.address,
		sponsors.latitude, sponsors.longitude, sponsors.email, sponsors.phone_numbers,
		sponsors.responsable_id
	FROM sponsors
	WHERE id = ?
";

$stmt = $conn->stmt_init();
$stmt = $conn->prepare( $sql );
$stmt->bind_param( "i", $id );
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

// Find search path to Sponsor's logo
$picture_path = getPicturePath( 'sponsors', $id );

?>

<div><h3> </h3>
  <h2>Edit Sponsor</h2>
</div>
<br><br>

<div class="errors-section">
	<?php print_errors(); ?>
</div>

<div id="sponsor-edit-form" class="input-section">
  <form id="edit-form" action="../include/edit_sponsor.inc.php" method="POST" onsubmit="return validateSponsor()">
  <!--form id="edit-form" action="../include/edit_sponsor.inc.php" method="POST" onsubmit="return true"-->
      <input type="hidden" name="id" value=<?php echo $id ?>><br>
      <label Class="label_style" for="sponsor_name">Name <span class="error_messages" id="name_error"><?php echo $_SESSION[ 'input_errors' ][ 'name' ] ?: ""; ?></span></label>
      <input type="text" id="sponsor_name" name="name" value="<?php echo $row[ 'name' ]?>"><br>
      <label Class="label_style" for="description_id">Description <span class="error_messages" id="description_error"><?php echo $_SESSION[ 'input_errors' ][ 'description' ] ?: ""; ?></span></label>
      <input type="text" id="description_id" name="description" value="<?php echo $row[ 'description' ]?>"><br>
      <label Class="label_style" for="location_input">Address <span class="error_messages" id="location_error"><?php echo $_SESSION[ 'input_errors' ][ 'address' ] ?: ""; ?></span></label>
      <input type="text" id="location_input" name="address" value="<?php echo $row[ 'address' ]?>"><br>
      <label Class="label_style" for="lat_output">Latitude <span class="error_messages" id="lat_error"><?php echo $_SESSION[ 'input_errors' ][ 'latitude' ] ?: ""; ?></span></label>
      <input type="text" id="lat_output" name="latitude" value="<?php echo $row[ 'latitude' ]?>" tabindex="-1" readonly><br>
      <label Class="label_style" for="long_output">Longitude <span class="error_messages" id="long_error"><?php echo $_SESSION[ 'input_errors' ][ 'longitude' ] ?: ""; ?></span></label>
      <input type="text" id="long_output" name="longitude" value="<?php echo $row[ 'longitude' ]?>" tabindex="-1" readonly><br>
      <label Class="label_style" for="email_id">Mail <span class="error_messages" id="email_error"><?php echo $_SESSION[ 'input_errors' ][ 'email' ] ?: ""; ?></span></label>
      <input type="text" id="email_id" name="email" value="<?php echo $row[ 'email' ]?>"><br>
      <label Class="label_style" for="phone_id">Phone number(s), separated by commas <span class="error_messages" id="phone_error"><?php echo $_SESSION[ 'input_errors' ][ 'phone_numbers' ] ?: ""; ?></span></label>
      <input type="text" id ="phone_id" name="phone_numbers" value="<?php echo implode(", ", json_decode( $row[ 'phone_numbers' ], true ));?>"><br>
      <label Class="label_style" for="responsable_id">Manager <span class="error_messages" id="responsible_error"><?php echo $_SESSION[ 'input_errors' ][ 'responsible' ] ?: ""; ?></span></label>
      <select id="responsable_id" name="responsible">
	      <option value='-1'>--Select manager--</option>
	      <?php
	      	// Generate dropdown options from Staff table

	      	$conn2 = new mysqli( $db_server_name, $db_user_name, $db_password, $db_default_schema );
	      	if( !$conn2->connect_errno ) {
	      		$query = "
	      			SELECT id, first_name, last_name
	      			FROM staff
	      		";

	      		if( $result = $conn2->query( $query )) {
	      			while( $row_staff = $result->fetch_assoc()) {
        				echo "<option value='". $row_staff[ 'id' ] . "'";
        				if( $row[ 'responsable_id' ] == $row_staff[ 'id' ]) {
        					echo ' selected';
        				}
        				echo ">" . $row_staff[ 'first_name' ] . " " . $row_staff[ 'last_name' ] . "</option>";
    					}
	      		}
		      	$conn2->close();
	      	}
	      ?>
      </select>
      <button type="submit">Save</button>
  </form>
</div>

<div id="get-back">
  <form id="get-back-form" action="main.php">
    <button>Cancel (Unsaved changes will be lost)</button>
  </form>
</div>

<div class="change-picture" id="changePic">

  <h2>Change Picture</h2>

  <h3>Current picture</h3>
  <img src="<?php echo $picture_path;?>" alt="Picture" width="600px"><br>
  <form id="upload-picture-form" action="upload.php?id=<?php echo $id ?>" method="post" enctype="multipart/form-data">
    <h3>Select new image to replace</h3><br>
		<label class="file_container">
      <div id="fileUploadDiv">File to upload</div>
      <input type="file" name="fileToUpload" id="name">
    </label>
    <input type="submit" value="Upload Image" name="submit">
    <i>Accepted file types: jpg, jpeg, png and gif. Maximum file size: 50 Mb</i>
  </form>

</div>
<script src="validate_input.js"></script>
<script>
$( 'input[type=file]' ).on( 'change', function() {
	$( "#fileUploadDiv").text( $( this ).val().replace( /([^\\]*\\)*/,'Choosen image: ' ));
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