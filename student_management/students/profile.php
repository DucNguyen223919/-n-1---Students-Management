<?php
session_start();
require __DIR__ . '/../includes/auth_check.php';
require __DIR__ . '/../config/db.php';

if (!isset($_SESSION['username']) || ($_SESSION['role'] ?? '') !== 'viewer') {
    die("Không có quyền truy cập");
}

$student_code = $_SESSION['username'];

/* ===== LẤY THÔNG TIN SINH VIÊN ===== */
$stmt = $conn->prepare("
SELECT 
    s.student_code,
    s.full_name,
    s.birth_date,
    s.gender,
    s.email,
    s.phone,
    s.hometown,
    s.cohort,
    s.avatar,
    d.name AS department_name,
    m.name AS major_name
FROM students s
LEFT JOIN departments d ON s.department_id = d.id
LEFT JOIN majors m ON s.major_id = m.id
WHERE s.student_code = ?
");
$stmt->execute([$student_code]);
$sv = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sv) die("Không tìm thấy sinh viên");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thông tin sinh viên</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.profile-avatar {
    width: 150px;
    height: 200px;
    object-fit: cover;
    border: 1px solid #ccc;
}
.info-label {
    font-weight: 600;
    color: #555;
}
</style>
</head>

<body>
<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="container mt-4">

<h3 class="mb-4">👤 THÔNG TIN SINH VIÊN</h3>

<div class="row">
    <!-- AVATAR -->
    <?php
    $avatarPath = !empty($sv['avatar'])
     ? '/student_management/uploads/students/' . $sv['avatar']
     : '/student_management/assets/images/default_avatar.png';
    ?>

    <div class="col-md-3 text-center">
     <img 
       src="<?= $avatarPath ?>"
       class="profile-avatar mb-3"
       alt="Avatar sinh viên"
     >
    </div>


    <!-- THÔNG TIN -->
    <div class="col-md-9">
        <div class="row mb-2">
            <div class="col-md-4 info-label">MSSV</div>
            <div class="col-md-8"><?= htmlspecialchars($sv['student_code']) ?></div>
        </div>

        <div class="row mb-2">
            <div class="col-md-4 info-label">Họ và tên</div>
            <div class="col-md-8"><?= htmlspecialchars($sv['full_name']) ?></div>
        </div>

        <div class="row mb-2">
            <div class="col-md-4 info-label">Ngày sinh</div>
            <div class="col-md-8"><?= $sv['birth_date'] ?></div>
        </div>

        <div class="row mb-2">
            <div class="col-md-4 info-label">Giới tính</div>
            <div class="col-md-8"><?= $sv['gender'] ?></div>
        </div>

        <div class="row mb-2">
            <div class="col-md-4 info-label">Email</div>
            <div class="col-md-8"><?= htmlspecialchars($sv['email']) ?></div>
        </div>

        <div class="row mb-2">
            <div class="col-md-4 info-label">Số điện thoại</div>
            <div class="col-md-8"><?= htmlspecialchars($sv['phone']) ?></div>
        </div>

        <div class="row mb-2">
            <div class="col-md-4 info-label">Quê quán</div>
            <div class="col-md-8"><?= htmlspecialchars($sv['hometown']) ?></div>
        </div>

        <div class="row mb-2">
            <div class="col-md-4 info-label">Niên khóa</div>
            <div class="col-md-8"><?= htmlspecialchars($sv['cohort']) ?></div>
        </div>

        <div class="row mb-2">
            <div class="col-md-4 info-label">Khoa</div>
            <div class="col-md-8"><?= htmlspecialchars($sv['department_name']) ?></div>
        </div>

        <div class="row mb-2">
            <div class="col-md-4 info-label">Ngành</div>
            <div class="col-md-8"><?= htmlspecialchars($sv['major_name']) ?></div>
        </div>
    </div>
</div>

<hr class="my-4">

<h4 class="mb-3">📊 KẾT QUẢ HỌC TẬP</h4>

<iframe 
    src="program_view.php" 
    style="width:100%; height:520px; border:1px solid #ddd;">
</iframe>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
