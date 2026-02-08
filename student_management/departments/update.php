<?php
require "../config/db.php";

$sql = "UPDATE departments SET name = ?, info = ? WHERE id = ?";
$conn->prepare($sql)->execute([
    $_POST['name'],
    $_POST['info'],
    $_POST['id']
]);

header("Location: index.php");
exit;
