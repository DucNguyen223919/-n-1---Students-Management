<?php
require "../config/db.php";

$sql = "INSERT INTO majors (code, name) VALUES (?, ?)";
$conn->prepare($sql)->execute([
    $_POST['code'],
    $_POST['name']
]);

header("Location: index.php");
