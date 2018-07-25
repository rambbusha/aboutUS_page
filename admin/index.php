<!DOCTYPE html>
    <html>
    <head>
    <meta charset = "utf-8" >
    <title > Globuzzer Admin Pages </title> 

    <link href = "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel = "stylesheet" integrity = "sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin = "anonymous" >

        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link rel = "stylesheet" type = "text/css" href = "css/style.css" >
    </head>
    <body>
    <div id = "top-header" >
    <img src = "img/logo.png"alt = "Logo" >
    <h3> Admin Pages </h3> </div>


<h2> Login </h2>
 <br >

    <div class="login-form" >
    <div id="msg"></div>
    <form action="include/login.inc.php" method="POST" >
    <div class ="form-group " >
    <input type ="text" class="form-control" placeholder="USERNAME" name="usr_name" tabindex="1" required autofocus >
    <i class="fa fa-user fa-lg" > </i> 
    </div> 
    <div class="form-group" >
    <input id="password" type="password" class="form-control" placeholder="PASSWORD" name="usr_psw" tabindex ="2"required >
    <i class="fa fa-lock fa-lg"> </i> 
    </div> 
    <button type="submit" class="log-btn" tabindex="3" > Login </button> </form> </div>

    <?php
    //var_dump($_SERVER['REQUEST_URI']);
    //var_dump($_SERVER['HTTP_REFERER']);
        //checks if http refers still to index.php - if yes, something is wrong
        if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "admin/index.php") !== FALSE) {
            ?>
            <div id="dialog-message" title="Wrong password" style="display:none">
                <p>
                    You may have used the wrong username or password.
                </p>
            </div>
            <script>
                    $('#dialog-message').show();
                    $(function () {
                        $("#dialog-message").dialog({
                            modal: true,
                            buttons: {
                                Ok: function () {
                                    $(this).dialog("close");
                                }
                            }
                        });
                    });
            </script>
            <?php
        }
    ?>
</body> 
</html>