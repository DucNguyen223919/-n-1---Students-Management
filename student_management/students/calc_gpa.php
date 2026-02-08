<?php
require "../config/db.php";

$student_id = $_GET['student_id'];
$major_id   = $_GET['major_id'];

$stmt = $conn->prepare("
SELECT 
  ROUND(
    SUM(ss.score * s.credits) / SUM(s.credits),
    2
  ) AS gpa
FROM major_subjects ms
JOIN subjects s ON ms.subject_id = s.id
JOIN student_subject_scores ss
  ON ss.subject_id = s.id
  AND ss.student_id = ?
WHERE ms.major_id = ?
");
$stmt->execute([$student_id, $major_id]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
  'gpa' => $result['gpa']
]);
