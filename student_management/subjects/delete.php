<?php
require "../config/db.php";

$stmt = $conn->prepare("DELETE FROM subjects WHERE id=?");
$stmt->execute([$_GET['id']]);

header("Location: index.php");
