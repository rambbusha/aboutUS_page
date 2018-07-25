<?php

require 'database.php';

session_start();
$conn = new mysqli($db_server_name, $db_user_name, $db_password, $db_default_schema);

if ($conn->connect_errno) {
    header("HTTP/1.1 502 Bad Gateway");

    printf("Failed to connect to MySQL. Mysqli error (%i) %s.\n", $conn->connect_errno, $conn->connect_error);

    exit();
}

if (!$conn->set_charset('utf8')) {
    header("HTTP/1.1 500 Internal Server Error");

    printf("Error setting character set to utf8: (%i) %s\n", $conn->connect_errno, $conn->connect_error);

    exit();
}

//update URL if id is set
if (isset($_SESSION['id'])) {
    $sql = "UPDATE login SET lastUrl = '" . $_SERVER[REQUEST_URI] . "' WHERE loginId = " . $_SESSION['id'];
    $conn->query($sql);
}
?>