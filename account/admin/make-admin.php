<?php

include '../global-assets/db.php';

$username = 'rivindu_admin';
$password = 'Rivindu@1234';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO website_administrators (username, password_hash) VALUES (?, ?)";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ss", $username, $hashed_password);
$stmt->execute();
$stmt->close();

?>