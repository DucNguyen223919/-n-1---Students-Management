<?php
session_start();
require __DIR__ . '/../config/db.php';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    header("Location: login.php?error=1");
    exit;
}

/* ===== 1. KIỂM TRA USER ĐÃ TỒN TẠI ===== */
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // User đã tồn tại → kiểm tra password
    if (!password_verify($password, $user['password'])) {
        header("Location: login.php?error=1");
        exit;
    }

} else {
    /* ===== 2. USER CHƯA TỒN TẠI ===== */

    /* ---- 2.1. KIỂM TRA SINH VIÊN ---- */
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_code = ?");
    $stmt->execute([$username]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        // AUTO CREATE USER CHO SINH VIÊN
        $hashedPassword = password_hash($username, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO users (username, password, full_name)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            $username,
            $hashedPassword,
            $student['full_name']
        ]);

        $user = [
            'username'  => $username,
            'full_name' => $student['full_name']
        ];

    } else {
        /* ---- 2.2. KIỂM TRA GIẢNG VIÊN ---- */
        $stmt = $conn->prepare("SELECT * FROM lecturers WHERE lecturer_code = ?");
        $stmt->execute([$username]);
        $lecturer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($lecturer) {
            // AUTO CREATE USER CHO GIẢNG VIÊN
            $hashedPassword = password_hash($username, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                INSERT INTO users (username, password, full_name)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([
                $username,
                $hashedPassword,
                $lecturer['full_name']
            ]);

            $user = [
                'username'  => $username,
                'full_name' => $lecturer['full_name']
            ];

        } else {
            // Không phải SV cũng không phải GV
            header("Location: login.php?error=1");
            exit;
        }
    }
}

/* ===== 3. ĐĂNG NHẬP THÀNH CÔNG ===== */
$_SESSION['username']  = $user['username'];
$_SESSION['full_name'] = $user['full_name'];

// PHÂN QUYỀN LOGIC
if ($user['username'] === 'admin') {
    $_SESSION['role'] = 'admin';
} else {
    // Kiểm tra có phải giảng viên không
    $stmt = $conn->prepare("SELECT 1 FROM lecturers WHERE lecturer_code = ?");
    $stmt->execute([$user['username']]);

    if ($stmt->fetch()) {
        $_SESSION['role'] = 'staff'; // giảng viên
    } else {
        $_SESSION['role'] = 'viewer'; // sinh viên
    }
}


header("Location: ../dashboard.php");
exit;
