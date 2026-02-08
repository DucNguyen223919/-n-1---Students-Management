<?php
require "../config/db.php";

$student_id = $_GET['student_id'];
$major_id   = $_GET['major_id'];

$stmt = $conn->prepare("
  SELECT 
    s.id, s.code, s.name, s.credits,
    ss.score
  FROM major_subjects ms
  JOIN subjects s ON ms.subject_id = s.id
  LEFT JOIN (
        SELECT
            student_id,
            subject_id,
            MAX(score) AS score
        FROM student_subject_scores
        WHERE student_id = ?
        GROUP BY student_id, subject_id
  ) ss
    ON ss.subject_id = s.id
  WHERE ms.major_id = ?
  ORDER BY s.code
");
$stmt->execute([$student_id, $major_id]);


echo json_encode([
  'subjects' => $stmt->fetchAll(PDO::FETCH_ASSOC)
]);
