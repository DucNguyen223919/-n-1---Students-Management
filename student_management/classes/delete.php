<?php
require "../config/db.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM classes WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit;
