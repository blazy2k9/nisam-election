<?php
require_once './constants.php';
include './DB_Connect.php';
session_start();
$date = START_DATE;
$exp_date = strtotime($date);
$now = time();
if (!(isset($_SESSION[LOGIN_ID]) && strlen($_SESSION[LOGIN_ID]) > 0)) {
    $url = 'http://' . $_SERVER['HTTP_HOST'];            // Get the server
    $url .= rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); // Get the current directory
    $url .= '/index.php';                                // <-- Your relative path
    header('Location: ' . $url, true, 302);              // Use either 301 or 302
} else {
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
                    margin: 0;
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

                .ballot{
                    width: 500px;
                    min-height: 50%;
                    background: white;
                    border-radius: 3px;
                    padding: 20px;
                    padding-left: 40px;
                    position: relative;
                }
                .ballot .next{
                    position: absolute;
                    bottom: 0;
                    right: 0;
                    margin-bottom: 30px;
                    margin-right: 30px;
                }

                .radio{
                    cursor: pointer;
                    font-size: 18px;
                    line-height: 20px;
                    margin-bottom: 25px;
                }
                .nisam-header, .center{
                    text-align: center;
                }
                #countdown{
                    font-weight: bolder;
                    font-size: 25px;
                }

                #footer a {color:gold;}      /* unvisited link */
                #footer a:visited {color:gold;}  /* visited link */
                #footer a:hover {color:gold;}  /* mouse over link */
                #footer a:active {color:gold;}  /* selected link */

                #footer{
                    text-align: left;
                    margin: 20px;
                }

                #nisam-logo{
                    max-width: 20%;
                }
            </style>
        </head>
        <body>
            <div class="nisam-header">
                <img id="nisam-logo" src="img/login/icon.png" alt="Welcome to NISAM">
                <h4>NISAM 2014/2015 Election</h4>
            </div>

            <br />

            <div class="center">

                <small id="hide">Polling is over!<br />Click below to view result!</small>
                <br /><br /><br />

                <div class="demo-browser-action">
                    <a class="btn btn-danger btn-lg btn-block" id="countdown" href="result.php">View Result!</a>
                </div>
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
    <?php
}
exit;
