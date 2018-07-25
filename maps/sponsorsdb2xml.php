<?php
require("phpsqlajax_dbinfo.php");

function parseToXML($htmlStr) {
	$xmlStr=str_replace('<','&lt;',$htmlStr);
	$xmlStr=str_replace('>','&gt;',$xmlStr);
	$xmlStr=str_replace('"','&quot;',$xmlStr);
	$xmlStr=str_replace("'",'&#39;',$xmlStr);
	$xmlStr=str_replace("&",'&amp;',$xmlStr);

	return $xmlStr;
}

// Opens a connection to a MySQL server
$connection=mysqli_connect ($host, $username, $password);
if (!$connection) {
  die('Not connected : ' . $connection->connect_error);
}

// Set the active MySQL database
$db_selected = mysqli_select_db($connection, $database);
if (!$db_selected) {
  die ('Can\'t use db : ' . $connection->connect_error);
}

// Select all the rows in the markers table
$query = "SELECT * FROM sponsors";
$result = $connection->query($query);
if (!$result) {
  die('Invalid query: ' . $connection->connect_error);
}

header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($row = @mysqli_fetch_assoc($result)){
  // Add to XML document node
  echo '<marker> ';
  echo '<id>' . $row['id'] . '</id>';
  echo '<name>' . parseToXML($row['name']) . '</name>';
  echo '<description>' . parseToXML($row['description']) . '</description>';
  echo '<address>' . parseToXML($row['address']) . '</address>';
  echo '<email>' . parseToXML($row['email']) . '</email>';
  echo '<logo>' . parseToXML($row['logo']) . '</logo>';
  echo '<responsible_id>' . parseToXML($row['responsable_id']) . '</responsible_id>';
  echo '<phone_numbers>' . implode(", ", json_decode($row['phone_numbers'], true)) . '</phone_numbers>';
  echo '<lat>' . $row['latitude'] . '</lat>';
  echo '<lng>' . $row['longitude'] . '</lng>';
  echo '</marker>';
}

// End XML file
echo '</markers>';

?>

