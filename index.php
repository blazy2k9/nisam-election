<?php
require_once './constants.php';
include './DB_Connect.php';
session_start();

if (isset($_POST['email'])) {
    $email = strtolower(trim($_POST['email']));
}
if (isset($_POST['password'])) {
    $password = $_POST['password'];
    $password = hash("sha512", $password . $email);
}

if (isset($email) && isset($password)) {
    $db_conn = new DB_Connect();

    $stmt = $db_conn->conn->prepare('SELECT id, full_name FROM users where email = ? AND password = ?');
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $stmt->bind_result($id, $full_name);
    if ($stmt->fetch()) {
        $_SESSION[LOGIN_ID] = $id;
        $_SESSION[LOGIN_NAME] = $full_name;
    }

    mysqli_close($db_conn->conn);
}

if (!( isset($_SESSION[LOGIN_ID]) && strlen($_SESSION[LOGIN_ID]) > 0)) {
    $url = 'http://' . $_SERVER['HTTP_HOST'];            // Get the server
    $url .= rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); // Get the current directory
    $url .= '/login.php';                                // <-- Your relative path
    header('Location: ' . $url, true, 302);              // Use either 301 or 302
} else {
    $full_name = $_SESSION[LOGIN_NAME];
}

$date = START_DATE;
$exp_date = strtotime($date);
$now = time();
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
        <link href="css/demo.css" rel="stylesheet">

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
                text-align: center;
                padding: 20px;
            }

            .login-form{
                min-width: 500px;
            }
            .nisam-header{
                text-align: center;
            }

            div{
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
        </style>
    </head>
    <body>

        <div>
            <div class="nisam-header">
                <h4>NISAM 2014/2015 Election</h4>
            </div>

            <br />


            <div class="demo-browser-side">
                <div class="demo-browser-author"></div>
                <div class="demo-browser-action">
                    <a class="btn btn-danger btn-lg btn-block" id="countdown" href="poll.php">
                        <?php
                        if ($now < $exp_date) {
                            echo "COUNTDOWN";
                        } else {
                            echo "Vote now!";
                        }
                        ?>
                    </a>
                </div>
                <h5>Registered voter</h5>
                <h4><?php echo $full_name; ?></h4>
            </div>
        </div>


        <!-- jQuery (necessary for Flat UI's JavaScript plugins) -->
        <script src="js/vendor/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/flat-ui.min.js"></script>

        <?php if ($now < $exp_date) { ?>

            <script>

                // Count down milliseconds = server_end - server_now = client_end - client_now
                var server_end = <?php echo $exp_date; ?> * 1000;
                var server_now = <?php echo time(); ?> * 1000;
                var client_now = new Date().getTime();
                var end = server_end - server_now + client_now; // this is the real end time

                var _second = 1000;
                var _minute = _second * 60;
                var _hour = _minute * 60;
                var _day = _hour * 24
                var timer;

                function showRemaining() {
                    var now = new Date();
                    var distance = end - now;
                    if (distance < 0) {
                        clearInterval(timer);
                        location.reload();
                        return;
                    }
                    var days = Math.floor(distance / _day);
                    var hours = Math.floor((distance % _day) / _hour);
                    var minutes = Math.floor((distance % _hour) / _minute);
                    var seconds = Math.floor((distance % _minute) / _second);

                    document.getElementById('countdown').innerHTML = two(hours) + " : " + two(minutes) + " : " + two(seconds);

                }

                function two(x) {
                    if (x < 10)
                        return "0" + x;
                    else
                        return x;
                }

                timer = setInterval(showRemaining, 1000);
            </script>
        <?php } ?>
        <div id="footer">
            <span style=" color: lightyellow; font-family:helvetica neue,helvetica,sans-serif;font-size:11px; line-height:18px;">Webmaster: <strong><a href="mailto:olayinka.sf@gmail.com" target="_blank">Olayinka SF</a></strong></span>
        </div>
    </body>
</html>
<?php exit; ?>