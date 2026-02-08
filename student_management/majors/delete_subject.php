<?php
require "../config/db.php";

$major_id   = $_GET['major_id'];
$subject_id = $_GET['subject_id'];

$stmt = $conn->prepare(
  "DELETE FROM major_subjects WHERE major_id=? AND subject_id=?"
);
$stmt->execute([$major_id, $subject_id]);

header("Location: view.php?id=".$major_id);
