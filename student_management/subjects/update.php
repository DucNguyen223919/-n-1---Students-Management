<?php
require "../config/db.php";

$stmt = $conn->prepare(
  "UPDATE subjects SET code=?, name=?, credits=? WHERE id=?"
);
$stmt->execute([
  $_POST['code'],
  $_POST['name'],
  $_POST['credits'],
  $_POST['id']
]);

header("Location: index.php");
