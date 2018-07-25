<?php

include 'connection.php';

// Destroy any previous session
include 'logout.inc.php';

// Import and sanitise input
$uid = mysqli_real_escape_string($conn, $_POST['usr_name']);
$uid = trim($uid);

$psw = mysqli_real_escape_string($conn, $_POST['usr_psw']);

//$pswHash = password_hash($psw, PASSWORD_DEFAULT);

// ToDo: Can a password contain whitespace? If not, trim() the password.

// Sql query
$sql = "
  	SELECT * FROM login
    WHERE username = '$uid';";

// Execute the query
if (!$res = $conn->query($sql)) {
    // Handle database errors

    header("500 Internal Server Error");
    header("Location: ../index.php");

    mysqli_close($conn);
    exit();
}

if($row = mysqli_fetch_assoc($res)) {
    //$sql = "UPDATE login SET password = '$pswHash' WHERE username = '$uid';";
    //$res = $conn->query($sql);

    //verifies the hashed password
    $pswCheck = password_verify($psw, $row['password']);

    if(!$pswCheck) {
        header("403 Forbidden");
        header("Location: ../index.php?wrong_password");

        mysqli_free_result($res);
        mysqli_close($conn);
        exit();
    }
}
// Handle login failure
elseif(!$row = mysqli_fetch_assoc($res)) {
    header("403 Forbidden");
    header("Location: ../index.php?login_error");

    mysqli_free_result($res);
    mysqli_close($conn);
    exit();
}

// Handle success
session_start();
$_SESSION['id'] = $row['loginid'];
$_SESSION['start_time'] = time();

mysqli_free_result($res);
mysqli_close($conn);


header("HTTP/1.1 200 OK");
// redirect to previous page
// database: $_SERVER[REQUEST_URI]
if (isset($row['lastUrl']) && $row['lastUrl'] != '/globuzzer/admin/index.php' && $row['lastUrl'] != '/team/admin/include/login.inc.php') {
    header("Location: " . $row['lastUrl']);
} else {
    header("Location: ../staffpages/main.php");
}
exit();