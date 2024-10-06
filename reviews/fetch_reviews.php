<?php

include '../signup-logins-users/db.php';

session_start();

$sql = "SELECT review_id, user_name, review, review_date FROM reviews ORDER BY review_date DESC";
$result = $connection->query($sql);

$reviews = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}

echo json_encode($reviews);
$connection->close();
?>