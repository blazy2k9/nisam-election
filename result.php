<?php
session_start();
require_once './constants.php';
include './DB_Connect.php';

$date = STOP_DATE;
$exp_date = strtotime($date);
$now = time();

function convert($timestamp) {
    $schedule_date = new DateTime($timestamp, new DateTimeZone(date_default_timezone_get()));
    $schedule_date->setTimeZone(new DateTimeZone('UTC'));
    $triggerOn = $schedule_date->format('Y-m-d H:i:s');
    echo $triggerOn;
}

if ($now < $exp_date || !(isset($_SESSION[LOGIN_ID]) && strlen($_SESSION[LOGIN_ID]) > 0)) {
    $url = 'http://' . $_SERVER['HTTP_HOST'];            // Get the server
    $url .= rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); // Get the current directory
    $url .= '/poll.php';                                // <-- Your relative path
    header('Location: ' . $url, true, 302);
} else {

    $db_conn = new DB_Connect();
    $sql = "SELECT u.full_name as voter, v.full_name as candidate, votes.time as time_of_vote " .
            "from votes " .
            "INNER JOIN users u " .
            "ON u.id=votes.u_id " .
            "INNER JOIN users v " .
            "ON v.id=votes.v_id";
    $result = $db_conn->conn->query($sql);

    $table = array();
    $rank = array();

    while ($row = $result->fetch_assoc()) {
        $table[] = $row;
        if (isset($rank[$row["candidate"]])) {
            $rank[$row["candidate"]] = $rank[$row["candidate"]] + 1;
        } else {
            $rank[$row["candidate"]] = 1;
        }
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
                .japhet {
                    border-collapse: collapse;
                }
                table{
                    color: #333333;
                    margin: auto;
                }
                #japhet{
                    border-radius: 3px;
                    background: whitesmoke;
                    padding: 50px;
                }
                .japhet, .japhet th,.japhet td {
                    padding: 10px;
                    font-size: 14px;
                    border-collapse: collapse;
                }
                .head{
                    background: #333333;
                    color: whitesmoke;
                }
                .japhet td {
                    border: 1px #333333 solid;
                }

                .t-last{
                    border-right:   0 !important;
                }
                .t-first{
                    border-left: 0 !important;
                }
                th, .t-first{
                    text-align: center;
                }

                .right{
                    text-align: right;
                }
                .winner{
                    padding: 20px;
                }
                #cand1{
                    font-weight: bold;
                }
                .winner td{
                    padding: 15px;
                }
                hr{
                    border-top: 1px #333333 solid;
                    width: 100%;
                }
            </style>
        </head>
        <body>

            <div>
                <div class="nisam-header">
                    <img id="nisam-logo" src="img/login/icon.png" alt="Welcome to NISAM">
                    <h4>NISAM 2014/2015 Election Result</h4>
                </div>

                <br />
                <div id="japhet">
                    <?php
                    if ($_SESSION[LOGIN_ID] == "23") {
                        if ($result->num_rows > 0) {
                            ?>
                            <table class="japhet">
                                <tr class="head"><th>Voter</th><th>Candidate</th><th>Time of vote</th></tr>
                                <?php foreach ($table as $key => $row) { ?>            
                                    <tr> <td class="t-first"><?php echo $row["voter"]; ?></td><td><?php echo $row["candidate"]; ?></td><td class="t-last"><?php convert($row["time_of_vote"]); ?></td></tr>
                                <?php } ?>
                            </table>
                            <hr />
                            <?php
                        }
                    }
                    arsort($rank, SORT_DESC);
                    ?>
                    <table class="winner">
                        <?php
                        $i = 0;
                        foreach ($rank as $key => $value) {
                            $i++;
                            ?>
                            <tr id="cand<?php echo $i; ?>"><td class="right"><?php echo $key; ?></td><td>-</td><td><?php echo $value; ?>&nbsp;&nbsp;Votes</td></tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>

                <div id="footer">
                    <span style=" color: lightyellow; font-family:helvetica neue,helvetica,sans-serif;font-size:11px; line-height:18px;">Webmaster: <strong><a href="mailto:olayinka.sf@gmail.com" target="_blank">Olayinka SF</a></strong></span>
                </div>

                <!-- jQuery (necessary for Flat UI's JavaScript plugins) -->
                <script src="js/vendor/jquery.min.js"></script>
                <!-- Include all compiled plugins (below), or include individual files as needed -->
                <script src="js/flat-ui.min.js"></script>
        </body>

    </html>
    <?php
    mysqli_close($db_conn->conn);
}
exit;
