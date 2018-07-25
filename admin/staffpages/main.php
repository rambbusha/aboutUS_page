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
    <h2 class="section-heading">Current Staff</h2>
</div>

<div class="errors-section">
    <?php print_errors(); ?>
</div>

<!-- Staff table -->
<div class="sql-table">
    <div class="addButtonClass">
        <div class="searchDiv">
            <i class="fa fa-search" id="searchIcon" aria-hidden="true"></i>
            <input class="search" id="search" type="text" placeholder="Type to search&hellip;">
        </div>
        <button class="addBtn" type="button" data-toggle="modal" data-target="#inputModal">+ Add new staff</button>
    </div>


    <table id="table">
        <thead>
        <tr class="table_header">
            <th>Picture</th>
            <th>Name & E-mail</th>
            <th>Location</th>
            <th>Role</th>
            <!--th>Lat<br>Long</th-->
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php


        function picturePath($id)
        {
            $picture_type = glob('../../uploads/staff/' . $id . '.*');
            if ($picture_type[0] == "") {
                throw new Exception('Picture not found');
            }
            $picture_name = $id . '.' . pathinfo($picture_type[0])['extension'];
            $picture_path = '../../uploads/staff/' . $picture_name;
            return $picture_path;
        }

        $sql = "
				SELECT *
				FROM staff;
			";
        $res = $conn->query($sql);

        while ($row = $res->fetch_assoc()) {

            $id = $row['id'];
            $picture_path = getPicturePath('staff', $id);
            $picture_alt = 'Staff picture for ' . $row['first_name'] . ' ' . $row['last_name'];

            echo '<tr>' .
                '<td>' . "<a href=../../map/index.html target='_blank'><img class='picture' src='" . $picture_path . "' alt='" . $picture_alt . "' aria-hidden='true'></a>" .
                '</td>' .
                '<td>' . $row['first_name'] . ' ' . $row['last_name'] .
                '<br>' . $row['email'] .
                '</td>' .
                '<td>' .
                $row['country'] .
                '</td>' .
                '<td>' .
                $row['role'] .
                '</td>' .
                "<td><a href='edit.php?id=$id'><img class='icon' src='../icons/editIcon_25px.png' alt='Edit'></a>" .
                "<a href='#myBtn' id='myBtn' onclick='modal_staff($id)'><img class='icon' src='../icons/deleteIcon_25px.png' alt='Delete'></a>
								</td>" .
                '</tr>';
        }

        ?>
        </tbody>
    </table>

</div><!-- END of Staff table-->

<div class="modal fade" id="inputModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="close" style="color:#313131; float:right; font-size: 28px; font-weight:bold;"
                  data-dismiss="modal">&times;</span>
            <div class="input-section" id="inputs">
                <div class="modal-header">
                    <h2 class="addHeader">Add new staff member</h2>
                </div>
                <form id="edit-form" action="../include/insert_staff.inc.php?id" method="POST"
                      onsubmit="return validateStaff()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <!--form name="form" action="../include/insert_staff.inc.php?id" method="POST" onsubmit="return true"-->
                                <label class="label_style" for="first_name_input">First name <span class="error_messages"
                                                                                                   id="first_name_error"><?php echo $_SESSION['input_errors']['first_name'] ?: ""; ?></span></label>
                                <input type="text" id="first_name_input" name="first_name"
                                       placeholder="Firstname [Example: Jerry]"><br>
                                <label class="label_style" for="last_name_input">Last name <span class="error_messages"
                                                                                                 id="last_name_error"><?php echo $_SESSION['input_errors']['last_name'] ?: ""; ?></span></label>
                                <input type="text" id="last_name_input" name="last_name"
                                       placeholder="Lastname [Example: Smith]"><br>
                                <label class="label_style" for="email_input">Email <span class="error_messages"
                                                                                         id="email_error"><?php echo $_SESSION['input_errors']['email'] ?: ""; ?></span></label>
                                <input type="text" id="email_input" name="email" placeholder="Email [Example: jSmith@mail.com]"><br>
                                <label class="label_style" for="phone_input">Phone number(s), separated by commas <span
                                            class="error_messages"
                                            id="phone_error"><?php echo $_SESSION['input_errors']['phone_numbers'] ?: ""; ?></span></label>
                                <input type="text" id="phone_input" name="phone_numbers"
                                       placeholder="Phone numbers [Example: +3407312345, +46703215476]"><br>
                            </div>
                            <div class="col-sm-6">
                                <label class="label_style" for="role_input">Role <span class="error_messages"
                                                                                       id="role_error"><?php echo $_SESSION['input_errors']['role'] ?: ""; ?></span></label>
                                <input type="text" id="role_input" name="role" placeholder="Role [Example: Photographer]"><br>
                                <label class="label_style" for="location_input">Location <span class="error_messages"
                                                                                               id="location_error"><?php echo $_SESSION['input_errors']['country'] ?: ""; ?></span></label>
                                <input type="text" id="location_input" name="country"
                                       placeholder="Location [Example: Stockholm, Sweden]"><br>
                                <label class="label_style" for="lat_output">Latitude <span class="error_messages"
                                                                                           id="latitude_error"><?php echo $_SESSION['input_errors']['latitude'] ?: ""; ?></span></label>
                                <input type="text" id="lat_output" name="latitude" placeholder="Latitude" tabindex="-1"
                                       readonly><br>
                                <label class="label_style" for="long_output">Longitude <span class="error_messages"
                                                                                             id="longitude_error"><?php echo $_SESSION['input_errors']['longitude'] ?: ""; ?></span></label>
                                <input type="text" id="long_output" name="longitude" placeholder="Longitude" tabindex="-1"
                                       readonly><br>
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
            </div><!-- END of Input new Staff section-->
        </div>
    </div>
</div>
<!-- Input new Staff section-->

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
$res->close;
$conn->close;

$_SESSION['input_errors'] = array();
?>
</body>
</html>
