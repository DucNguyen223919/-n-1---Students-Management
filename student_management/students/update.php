<?php
require "../config/db.php";

/* ===== LẤY AVATAR CŨ ===== */
$stmt = $conn->prepare("SELECT avatar FROM students WHERE id=?");
$stmt->execute([$_POST['id']]);
$oldAvatar = $stmt->fetchColumn();

/* ===== UPLOAD AVATAR MỚI (NẾU CÓ) ===== */
$avatar = $oldAvatar;
if (!empty($_FILES['avatar']['name'])) {
    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $avatar = time() . "_" . rand(1000,9999) . "." . $ext;
    move_uploaded_file(
        $_FILES['avatar']['tmp_name'],
        "../uploads/students/" . $avatar
    );
}

/* ===== UPDATE ===== */
$sql = "
UPDATE students SET
student_code   = ?,
full_name      = ?,
email          = ?,
major_class    = ?,
major_id       = ?,
department_id  = ?,
gender         = ?,
phone          = ?,
cohort         = ?,
hometown       = ?,
avatar         = ?
WHERE id = ?
";

$conn->prepare($sql)->execute([
    $_POST['student_code'],
    $_POST['full_name'],
    $_POST['email'],
    $_POST['major_class'],
    $_POST['major_id'],
    $_POST['department_id'],
    $_POST['gender'],
    $_POST['phone'],
    $_POST['cohort'],
    $_POST['hometown'],
    $avatar,
    $_POST['id']
]);

header("Location: index.php");
exit;
