<?php
require "../config/db.php";

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

/* ===== LẤY AVATAR ĐỂ XÓA FILE ===== */
$stmt = $conn->prepare("SELECT avatar FROM students WHERE id=?");
$stmt->execute([$id]);
$avatar = $stmt->fetchColumn();

if ($avatar && file_exists("../uploads/students/" . $avatar)) {
    unlink("../uploads/students/" . $avatar);
}

/* ===== DELETE STUDENT ===== */
$conn->prepare("DELETE FROM students WHERE id=?")->execute([$id]);

header("Location: index.php");
exit;
