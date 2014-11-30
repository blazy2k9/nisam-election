<?php
require_once './constants.php';
include './DB_Connect.php';
session_start();
$date = START_DATE;
$exp_date = strtotime($date);
$now = time();
if (!(isset($_SESSION[LOGIN_ID]) && strlen($_SESSION[LOGIN_ID]) > 0) || $now < $exp_date) {
    $url = 'http://' . $_SERVER['HTTP_HOST'];            // Get the server
    $url .= rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); // Get the current directory
    $url .= '/index.php';                                // <-- Your relative path
    header('Location: ' . $url, true, 302);              // Use either 301 or 302
} else {
    $db_conn = new DB_Connect();
    $stmt = $db_conn->conn->prepare("SELECT DISTINCT u_id FROM votes where u_id = ?");
    $stmt->bind_param('i', $_SESSION[LOGIN_ID]);
    $stmt->execute();
    $stmt->bind_result($id);
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

            <?php
            if ($stmt->fetch() || isset($_POST['pres'])) {
                $status = true;
                if (isset($_POST['pres'])) {
                    $office = 'pres';
                    $stmt->close();
                    $stmt = $db_conn->conn->prepare("INSERT INTO votes (u_id, office, v_id) VALUES (?, ?, ?)");
                    $stmt->bind_param('isi', $_SESSION[LOGIN_ID], $office, $_POST['pres']);
                    $stmt->execute();
                    if ($stmt->affected_rows != 1) {
                        $status = false;
                    }
                }

                if ($status) {
                    ?>                    
                    <div class="center">

                        <small id="hide">Stay tuned for the result!</small>
                        <br /><br /><br />

                        <div class="demo-browser-action">
                            <a class="btn btn-danger btn-lg btn-block" id="countdown" href="#">
                                <?php
                                $date = STOP_DATE;
                                $exp_date = strtotime($date);
                                if ($now < $exp_date) {
                                    echo "COUNTDOWN";
                                } else {
                                    echo "View Result!";
                                }
                                ?>
                            </a>
                        </div>
                    </div>
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
                        <?php
                    }
                } else {
                    $url = 'http://' . $_SERVER['HTTP_HOST'];            // Get the server
                    $url .= rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); // Get the current directory
                    $url .= '/index.php';                                // <-- Your relative path
                    header('Location: ' . $url, true, 302);              // Use either 301 or 302
                }
            } else {
                ?>
                <form class="ballot" method="POST">
                    <h3>President</h3>
                    <hr/>
                    <div class="to-fade">
                        <?php
                        $stmt = $db_conn->conn->prepare("SELECT id, full_name FROM users where office = ?");
                        $office = 'pres';
                        $stmt->bind_param('s', $office);
                        $stmt->execute();
                        $stmt->bind_result($id, $full_name);
                        while ($stmt->fetch()) {
                            ?>
                            <label class="radio">
                                <input type="radio" name="pres" id="optionsRadios1" value="<?php echo $id; ?>" data-toggle="radio" class="custom-radio"><span class="icons"><span class="icon-unchecked"></span><span class="icon-checked"></span></span>
                                <?php echo $full_name; ?>
                            </label>
                            <?php
                        }
                        mysqli_close($db_conn->conn);
                        ?>
                    </div>
                    <input id="nextButton" type="submit" class="next btn btn-primary"/>
                </form>
            <?php }
            ?>

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