<?php

include 'logout.inc.php';
include 'connection.php';

//update URL to index before logging out
if(isset($_SESSION['id'])) {
    $sql = "UPDATE login SET lastUrl = '../staffpages/main.php' WHERE loginId = ".$_SESSION['id'];
    $conn->query($sql);
}

header( "HTTP/1.1 200 OK" );
header('Location: ../index.php');
exit;

?>