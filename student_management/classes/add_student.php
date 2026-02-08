<?php
require "../config/db.php";

if (!isset($_POST['class_id'], $_POST['student_ids'])) {
    header("Location: view.php?class_id=" . $_POST['class_id']);
    exit;
}

$class_id = $_POST['class_id'];
$student_ids = $_POST['student_ids'];

$stmt = $conn->prepare("
    INSERT IGNORE INTO class_students (class_id, student_id)
    VALUES (?, ?)
");

foreach ($student_ids as $sid) {
    $stmt->execute([$class_id, $sid]);
}

header("Location: view.php?class_id=" . $class_id);
exit;
