<?php
require "../config/db.php";

$stmt = $conn->prepare(
  "INSERT INTO subjects (code, name, credits) VALUES (?,?,?)"
);
$stmt->execute([
  $_POST['code'],
  $_POST['name'],
  $_POST['credits']
]);

header("Location: index.php");
