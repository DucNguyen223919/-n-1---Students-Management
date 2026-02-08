<?php
require "../config/db.php";

$sql = "INSERT INTO lecturers
(lecturer_code, full_name, email, unit, position, department_id)
VALUES (?, ?, ?, ?, ?, ?)";

$conn->prepare($sql)->execute([
    $_POST['lecturer_code'],
    $_POST['full_name'],
    $_POST['email'],
    $_POST['unit'],
    $_POST['position'],
    $_POST['department_id']
]);

header("Location: index.php");
exit;
