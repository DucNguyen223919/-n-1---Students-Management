<?php
require "../config/db.php";

$id   = $_POST['id'] ?? null;
$step = $_POST['step'] ?? null;

if (!$id || !in_array($step, ['up', 'down'])) {
    http_response_code(400);
    exit;
}

if ($step === 'up') {
    $sql = "UPDATE class_students
            SET attendance = attendance + 1
            WHERE id = ?";
} else {
    $sql = "UPDATE class_students
            SET attendance = GREATEST(attendance - 1, 0)
            WHERE id = ?";
}

$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

$stmt = $conn->prepare("
    SELECT attendance
    FROM class_students
    WHERE id = ?
");
$stmt->execute([$id]);

echo $stmt->fetchColumn();
