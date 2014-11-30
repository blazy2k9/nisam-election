<?php
session_start();
require_once './constants.php';
include './DB_Connect.php';

function convert($timestamp) {
    $schedule_date = new DateTime($timestamp, new DateTimeZone(date_default_timezone_get()));
    $schedule_date->setTimeZone(new DateTimeZone('UTC'));
    $triggerOn = $schedule_date->format('Y-m-d H:i:s');

    echo $triggerOn;
}

if (isset($_GET["show"]) && $_GET["show"] === EXCLUSIVE) {
    $db_conn = new DB_Connect();
    $sql = "SELECT u.full_name as voter, v.full_name as candidate, votes.time as time_of_vote " .
            "from votes " .
            "INNER JOIN users u " .
            "ON u.id=votes.u_id " .
            "INNER JOIN users v " .
            "ON v.id=votes.v_id";
    $result = $db_conn->conn->query($sql);
    if ($result->num_rows > 0) {
        $rank = array();
        ?>
        <table cellpadding="5">
            <tr><th>Voter</th><th>Candidate</th><th>Time of vote</th></tr>
            <?php
            while ($row = $result->fetch_assoc()) {
                if (isset($rank[$row["candidate"]])) {
                    $rank[$row["candidate"]] = $rank[$row["candidate"]] + 1;
                } else {
                    $rank[$row["candidate"]] = 1;
                }
                ?>            
            <tr> <td><?php echo $row["voter"]; ?></td><td><?php echo $row["candidate"]; ?></td><td><?php convert($row["time_of_vote"]); ?></td></tr>
            <?php } ?>
        </table>
        <hr />
        <?php
        foreach ($rank as $key => $value) {
            echo $key . " :- " . $value . " votes." . "<br />";
        }
    } else {
        echo "0 results";
    }
    mysqli_close($db_conn->conn);
} else {
    echo date_default_timezone_get();
    die("you have no power here!");
}
exit;