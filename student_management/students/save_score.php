<?php
require "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $conn->prepare("
INSERT INTO student_subject_scores (student_id, subject_id, score)
VALUES (?,?,?)
ON DUPLICATE KEY UPDATE score=VALUES(score)
");
$stmt->execute([
  $data['student_id'],
  $data['subject_id'],
  $data['score']
]);
