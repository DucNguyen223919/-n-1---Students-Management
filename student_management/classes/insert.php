<?php
require "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$code        = $_POST['code'] ?? '';
$course_code = $_POST['course_code'] ?? '';
$name        = $_POST['name'] ?? '';
$lecturer_id = $_POST['lecturer_id'] ?? null;
$class_day   = $_POST['class_day'] ?? '';
$class_time  = $_POST['class_time'] ?? '';
$room        = $_POST['room'] ?? '';

$sql = "
INSERT INTO classes
(code, course_code, name, lecturer_id, class_day, class_time, room)
VALUES
(:code, :course_code, :name, :lecturer_id, :class_day, :class_time, :room)
";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':code'        => $code,
    ':course_code' => $course_code,
    ':name'        => $name,
    ':lecturer_id' => $lecturer_id !== '' ? $lecturer_id : null,
    ':class_day'   => $class_day,
    ':class_time'  => $class_time,
    ':room'        => $room
]);

header("Location: index.php");
exit;
