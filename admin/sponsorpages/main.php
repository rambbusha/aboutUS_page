<?php
require '../include/session.php';
require_once '../include/database.php';
require '../include/connection.php';
require_once '../include/print_backend_errors.inc.php';
include_once '../include/pictures.inc.php';
require '../headers/header.php';

error_reporting(0);
?>

<!-- The Modal Popup -->
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>WARNING</h2>
        <p id="delete_info_text">
            You're about to delete this information permanently.<br>
            Are you <b>totally</b> sure you want to do that?
        </p>
        <p class="modal_buttons">
            <a id="mytext">Delete</a>
            <a id="cancel_button" href="main.php"><b>Cancel</b></a>
        </p>
    </div><!-- END of Modal content -->

</div><!-- END of The Modal Popup -->

<div>
    <h2></h2>
    <h2 class="section-heading">Current Sponsors</h2>
</div>

<div class=“errors-section”>
    <?php print_errors(); ?>
</div>

<!-- Sponsors table -->
<div class="sql-table">
    <div class="addButtonClass">
        <div class="searchDiv">
            <i class="fa fa-search" id="searchIcon" aria-hidden="true"></i>
            <input class="search" id="search" type="text" placeholder="Type to search&hellip;">
        </div>
        <button type="button" class="addBtn" data-toggle="modal" data-target="#inputModal">+ Add new sponsor</button>
    </div>

    <table id="table">
        <thead>
        <tr class="table_header">
            <th>Logo</th>
            <th>Name</th>
            <th>Location</th>
            <!--th>Lat<br>Long</th-->
            <th>Mail & Phone</th>
            <!--th>Manager</th-->
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php

        $sql = "
      	SELECT sponsors.id, sponsors.name, sponsors.address, sponsors.description,
      		sponsors.latitude, sponsors.longitude, sponsors.email, sponsors.phone_numbers,
      		sponsors.responsable_id,
      		staff.first_name, staff.last_name
      	FROM sponsors INNER JOIN staff ON sponsors.responsable_id = staff.id
      ";

        $res = $conn->query($sql);
        while ($row = $res->fetch_assoc()) {

            $id = $row['id'];
            $picture_path = getPicturePath('sponsors', $id);
            $picture_alt = 'Logo for ' . $row['name'];

            echo '<tr>' .
                '<td>' .
                "<a href='../../map/index.html' target='_blank'><img class='picture' src='" . $picture_path . "' alt='" . $picture_alt . "'>" .
                '</td>' .
                '<td>' .
                $row['name'] .
                '</td>' .
                '<td>' .
                $row['address'] .
                '</td>' .
                '<!--td>' . $row['latitude'] .
                '<br>' . $row['longitude'] .
                '</td-->' .
                '<td>' .
                $row['email'] .
                '<br>' . implode(", ", json_decode($row['phone_numbers'], true)) .
                '</td>' .
                '<!--td>' .
                $row['first_name'] . ' ' . $row['last_name'] .
                '</td-->' .
                "<td><a href='edit.php?id=$id'><img class='icon' src='../icons/editIcon_25px.png' alt='Edit'></a>" .
                "<a href='#myBtn' id='myBtn' onclick='modal_sponsors($id)'><img class='icon' src='../icons/deleteIcon_25px.png' alt='Delete'></a>
                </td>" .
                '</tr>';
        }
        ?>
        </tbody>
    </table>
</div><!-- END of sponsors table-->

<div class="modal fade" id="inputModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="close" style="color:#313131; float:right; font-size: 28px; font-weight:bold;"
                  data-dismiss="modal">&times;</span>
            <!-- Input new Sponsor section-->
            <div class="input-section" id="inputs">
                <div class="modal-header">
                    <h2 class="addHeader">Add new sponsor</h2>
                </div>

                <form id="edit-form" class="form" action="../include/insert_sponsor.inc.php" method="POST"
                      onsubmit="return validateSponsor()">
                    <!--form class="form" action="../include/insert_sponsor.inc.php" method="POST" onsubmit="return true"-->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <!-- Name -->
                                <label class="label_style" for="sponsor_name">Name <span class="error_messages"
                                                                                         id="name_error"><?php echo $_SESSION['input_errors']['name'] ?: ""; ?></span></label>
                                <input type="text" id="sponsor_name" name="name"
                                       placeholder="Name [Example: Foo Company]"><br>
                                <!-- Description -->
                                <label class="label_style" for="description_id">Description <span class="error_messages"
                                                                                                  id="description_error"><?php echo $_SESSION['input_errors']['description'] ?: ""; ?></span></label>
                                <input type="text" id="description_id" name="description"
                                       placeholder="Description [Example: Supplies us with candy]"><br>
                                <!-- E-Mail -->
                                <label class="label_style" for="email_id">Email <span class="error_messages"
                                                                                      id="email_error"><?php echo $_SESSION['input_errors']['email'] ?: ""; ?></span></label>
                                <input type="text" id="email_id" name="email"
                                       placeholder="Email [Example: info@foo.co]"><br>
                                <!-- Phone numbers -->
                                <label class="label_style" for="phone_id">Phone number(s), separated by commas <span
                                            class="error_messages"
                                            id="phone_error"><?php echo $_SESSION['input_errors']['phone_numbers'] ?: ""; ?></span></label>
                                <input type="text" id="phone_id" name="phone_numbers"
                                       placeholder="Phone numbers [Example: +3407312345, +46703215476]"><br>
                            </div>
                            <div class="col-sm-6">
                                <!-- Location -->
                                <label class="label_style" for="location_input">Location <span class="error_messages"
                                                                                               id="location_error"><?php echo $_SESSION['input_errors']['description'] ?: ""; ?></span></label>
                                <input type="text" id="location_input" name="address"
                                       placeholder="Address [Example: 71 Pilgrim Avenue 20815]"><br>
                                <!-- Latitude -->
                                <label class="label_style" for="lat_output">Latitude <span class="error_messages"
                                                                                           id="latitude_error"><?php echo $_SESSION['input_errors']['latitude'] ?: ""; ?></span></label>
                                <input type="text" id="lat_output" name="latitude" placeholder="Latitude" tabindex="-1"
                                       readonly><br>
                                <!-- Longitude -->
                                <label class="label_style" for="long_output">Longitude <span class="error_messages"
                                                                                             id="longitude_error"><?php echo $_SESSION['input_errors']['longitude'] ?: ""; ?></span></label>
                                <input type="text" id="long_output" name="longitude" placeholder="Longitude"
                                       tabindex="-1"
                                       readonly><br>
                                <!-- Manager -->
                                <label class="label_style" for="responsable_id">Manager <span class="error_messages"
                                                                                              id="responsible_error"><?php echo $_SESSION['input_errors']['responsible'] ?: ""; ?></span></label>
                                <select type="text" id="responsable_id" name="responsible">
                                    <option value='-1'>Select manager</option>
                                    <?php
                                    $sql = "SELECT id, first_name, last_name FROM staff";
                                    $res = mysqli_query($conn, $sql);
                                    while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                                        $id = $row['id'];
                                        echo "<option value='$id'>" . $row['first_name'] . ' ' . $row['last_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="button-row">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit">Save</button>
                        </div>
                    </div>

                </form>

            </div><!-- END of Input new Sponsor section-->
        </div>
    </div>
</div>

<script src="../functions.js"></script>
<script src="validate_input.js"></script>
<script>
    $(document).ready(function () {
        $('.addBtn').on('click', function () {
            $('.modal-overlay').css('display', 'block');
        });

        $('.modal-overlay').on('click', function () {
            $('.modal-overlay').css('display', '');
        });
        $('.close').on('click', function () {
            $('.modal-overlay').css('display', '');
        });
        $('.modal-window').on('click', function (evt) {
            evt.stopPropagation();
        });
    });
</script>
<?php
$conn->close();

$_SESSION['input_errors'] = array();

?>
<script type="text/javascript">
    $(document).ready(function () {
        $("img").bind("contextmenu", function (e) {
            return false;
        });
    });
</script>
</body>
</html>
