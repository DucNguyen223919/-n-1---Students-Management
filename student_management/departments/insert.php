<?php
require "../config/db.php";

$sql = "INSERT INTO departments (name, info) VALUES (?, ?)";
$conn->prepare($sql)->execute([
    $_POST['name'],
    $_POST['info']
]);

header("Location: index.php");
exit;
