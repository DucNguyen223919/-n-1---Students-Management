<?php
require_once '../config/db.php';

$student_id = $_GET['student_id'] ?? null;
$subject_id = $_GET['subject_id'] ?? null;

if (!$student_id || !$subject_id) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("
    SELECT
        ss.score,
        ss.created_at,
        c.code AS class_code,
        c.name AS class_name
    FROM student_subject_scores ss
    LEFT JOIN classes c ON ss.class_id = c.id
    WHERE
        ss.student_id = ?
        AND ss.subject_id = ?
    ORDER BY ss.created_at ASC
");

$stmt->execute([$student_id, $subject_id]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
