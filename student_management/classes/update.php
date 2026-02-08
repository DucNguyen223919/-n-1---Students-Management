<?php
require "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$id          = $_POST['id'] ?? null;
$code        = $_POST['code'] ?? '';
$course_code = $_POST['course_code'] ?? '';
$name        = $_POST['name'] ?? '';
$lecturer_id = $_POST['lecturer_id'] ?? null;
$class_day   = $_POST['class_day'] ?? '';
$class_time  = $_POST['class_time'] ?? '';
$room        = $_POST['room'] ?? '';

if (!$id) {
    header("Location: index.php");
    exit;
}

$sql = "
UPDATE classes
SET
    code = :code,
    course_code = :course_code,
    name = :name,
    lecturer_id = :lecturer_id,
    class_day = :class_day,
    class_time = :class_time,
    room = :room
WHERE id = :id
";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':code'        => $code,
    ':course_code' => $course_code,
    ':name'        => $name,
    ':lecturer_id' => $lecturer_id !== '' ? $lecturer_id : null,
    ':class_day'   => $class_day,
    ':class_time'  => $class_time,
    ':room'        => $room,
    ':id'          => $id
]);

header("Location: index.php");
exit;
