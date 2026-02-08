<?php
/*************************************************
 * students/insert.php
 * - Thêm sinh viên mới
 * - Chặn trùng MSSV (student_code)
 *************************************************/

session_start();
require "../config/db.php";
require "../includes/auth_check.php";

/* ===== LẤY DỮ LIỆU TỪ FORM ===== */
$student_code   = trim($_POST['student_code'] ?? '');
$full_name      = trim($_POST['full_name'] ?? '');
$email          = trim($_POST['email'] ?? '');
$major_class    = trim($_POST['major_class'] ?? '');
$major_id       = $_POST['major_id'] ?? null;
$department_id  = $_POST['department_id'] ?? null;
$gender         = $_POST['gender'] ?? null;
$phone          = trim($_POST['phone'] ?? '');
$cohort         = trim($_POST['cohort'] ?? '');
$hometown       = trim($_POST['hometown'] ?? '');

/* ===== KIỂM TRA BẮT BUỘC ===== */
if ($student_code === '' || $full_name === '') {
    header("Location: index.php?error=Thiếu MSSV hoặc họ tên");
    exit;
}

/* ===== KIỂM TRA TRÙNG MSSV ===== */
$check = $conn->prepare("SELECT id FROM students WHERE student_code = ?");
$check->execute([$student_code]);

if ($check->fetch()) {
    header("Location: index.php?error=MSSV đã tồn tại");
    exit;
}

/* ===== UPLOAD AVATAR ===== */
$avatar = null;
if (!empty($_FILES['avatar']['name'])) {
    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $avatar = time() . "_" . rand(1000,9999) . "." . $ext;
    move_uploaded_file(
        $_FILES['avatar']['tmp_name'],
        "../uploads/students/" . $avatar
    );
}

/* ===== INSERT SINH VIÊN ===== */
try {
    $sql = "
        INSERT INTO students
        (student_code, full_name, email, major_class, major_id, department_id,
         gender, phone, cohort, hometown, avatar)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)
    ";

    $conn->prepare($sql)->execute([
        $student_code,
        $full_name,
        $email,
        $major_class,
        $major_id,
        $department_id,
        $gender,
        $phone,
        $cohort,
        $hometown,
        $avatar
    ]);

} catch (PDOException $e) {
    // Phòng trường hợp DB vẫn báo trùng
    if ($e->getCode() == 23000) {
        header("Location: index.php?error=MSSV đã tồn tại");
        exit;
    }
    throw $e;
}

/* ===== THÀNH CÔNG ===== */
header("Location: index.php?success=1");
exit;
