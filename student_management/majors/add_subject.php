<?php
require "../config/db.php";

$major_id   = $_POST['major_id'];
$subject_id = $_POST['subject_id'];

$stmt = $conn->prepare(
  "INSERT IGNORE INTO major_subjects (major_id, subject_id) VALUES (?,?)"
);
$stmt->execute([$major_id, $subject_id]);

header("Location: view.php?id=".$major_id);
