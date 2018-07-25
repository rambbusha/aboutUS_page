<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Globuzzer Admin Pages</title>

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="../css/style.css">

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQVbx68W3ffwHZ52iJM-1loWORBJoTK6k&v=3.exp&libraries=places"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <script src="../form.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


  </head>
  <body>
    <div id="top-header">
      <form action="../include/logout.php">
        <button type="submit" value="Submit">Log out</button>
      </form>
      <img src="../img/logo.png" alt="Globuzzer logo">
      <h3>Admin Pages</h3>
    </div>

    <ul id="navBar">
      <li id="staffPagesMenuItem" class="menuBarItem"><a class="menuBarLink" href="../staffpages/main.php" style="text-decoration:none;">Staff</a></li>
      <li id="sponsorPagesMenuItem" class="menuBarItem"><a class="menuBarLink" href="../sponsorpages/main.php" style="text-decoration:none;">Sponsors</a></li>
    </ul>
