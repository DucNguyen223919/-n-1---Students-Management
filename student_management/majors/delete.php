<?php
require "../config/db.php";

$id = $_GET['id'] ?? null;
if ($id) {
    $conn->prepare("DELETE FROM majors WHERE id = ?")->execute([$id]);
}
header("Location: index.php");
