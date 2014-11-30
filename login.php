<?php
require_once './constants.php';
session_start();
if (isset($_SESSION[LOGIN_ID]) && strlen($_SESSION[LOGIN_ID]) > 0) {
    $url = 'http://' . $_SERVER['HTTP_HOST'];            // Get the server
    $url .= rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); // Get the current directory
    $url .= '/index.php';                                // <-- Your relative path
    header('Location: ' . $url, true, 302);              // Use either 301 or 302
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>NISAM Election</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Loading Bootstrap -->
        <link href="css/vendor/bootstrap.min.css" rel="stylesheet">

        <!-- Loading Flat UI -->
        <link href="css/flat-ui.css" rel="stylesheet">

        <link rel="shortcut icon" href="img/favicon.ico">

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
        <!--[if lt IE 9]>
          <script src="js/vendor/html5shiv.js"></script>
          <script src="js/vendor/respond.min.js"></script>
        <![endif]-->

        <style>
            html, body {
                height: 100%;
            }

            html {
                display: table;
                margin: auto;
            }

            body {
                display: table-cell;
                vertical-align: middle;
                background-color: #48c9b0;
                padding: 20px;
            }

            .login-form{
                min-width: 500px;
            }
            .nisam-header{
                text-align: center;
            }
            #nisam-logo{
                max-width: 20%;
            }

            #footer a {color:gold;}      /* unvisited link */
            #footer a:visited {color:gold;}  /* visited link */
            #footer a:hover {color:gold;}  /* mouse over link */
            #footer a:active {color:gold;}  /* selected link */

            #footer{
                text-align: left;
                margin: 20px;
            }
        </style>
    </head>
    <body>

        <div>
            <div class="nisam-header">
                <img id="nisam-logo" src="img/login/icon.png" alt="Welcome to NISAM">
                <h4>NISAM 2014/2015 Election</h4>
            </div>

            <br />

            <form method="POST" action="index.php">

                <div class="login-form">
                    <div class="form-group">
                        <input type="email" class="form-control login-field" name="email" value="" placeholder="Enter your email" id="login-email">
                        <label class="login-field-icon fui-user" for="login-email"></label>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" class="form-control login-field" value="" placeholder="Password" id="login-pass">
                        <label class="login-field-icon fui-lock" for="login-pass"></label>
                    </div>

                    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Log in" />
                </div>
            </form>
        </div>

        <!-- jQuery (necessary for Flat UI's JavaScript plugins) -->
        <script src="js/vendor/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/flat-ui.min.js"></script>

        <div id="footer">
            <span style=" color: lightyellow; font-family:helvetica neue,helvetica,sans-serif;font-size:11px; line-height:18px;">Webmaster: <strong><a href="mailto:olayinka.sf@gmail.com" target="_blank">Olayinka SF</a></strong></span>
        </div>
    </body>

</html>
<?php exit; ?>