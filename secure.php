<?php

include './DB_Connect.php';


$db_conn = new DB_Connect();

$stmt = $db_conn->conn->prepare('INSERT INTO users (id, full_name, email, password, office) VALUES (?, ?, ?, ?, ?)');
$stmt->bind_param('sssss', $id, $full_name, $email, $password, $office);

$file = fopen("./nisam-voters.csv", "r");
if ($file) {
    $id = 0;
    while (($line = fgets($file)) !== false) {
        $id++;
        $user = explode(",", $line, 4);
        if (strlen(trim($user[1])) > 0) {
            $full_name = trim($user[0]);
            $email = strtolower(trim($user[1]));
            $password = hash("sha512", trim($user[2]) . $email);
            $office = trim($user[3]);

            $stmt->execute();
        }
    }
}
fclose($file);

$stmt->close();
mysqli_close($db_conn->conn);
