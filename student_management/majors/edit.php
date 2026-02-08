<?php
require "../config/db.php";

$sql = "UPDATE majors SET code = ?, name = ? WHERE id = ?";
$conn->prepare($sql)->execute([
    $_POST['code'],
    $_POST['name'],
    $_POST['id']
]);

header("Location: index.php");
