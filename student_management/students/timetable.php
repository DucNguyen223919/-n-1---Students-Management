<?php
session_start();
require __DIR__ . '/../includes/auth_check.php';
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/permission.php';

requireRole(['viewer']);

$student_code = $_SESSION['username'];

/* ===== LẤY ID SINH VIÊN ===== */
$stmt = $conn->prepare("
    SELECT id, full_name 
    FROM students 
    WHERE student_code = ?
");
$stmt->execute([$student_code]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Không tìm thấy sinh viên");
}

$student_id = $student['id'];

/* ===== LẤY TKB TỪ class_students ===== */
$stmt = $conn->prepare("
    SELECT 
        c.code,
        c.course_code,
        c.name,
        c.class_day,
        c.class_time,
        c.room,
        l.full_name AS lecturer_name
    FROM class_students cs
    JOIN classes c ON cs.class_id = c.id
    LEFT JOIN lecturers l ON c.lecturer_id = l.id
    WHERE cs.student_id = ?
    ORDER BY 
        FIELD(c.class_day,'Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','CN'),
        c.class_time
");
$stmt->execute([$student_id]);
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thời khóa biểu</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="container mt-4">

<h4 class="mb-3">
    📅 Thời khóa biểu — 
    <span class="text-danger"><?= htmlspecialchars($student['full_name']) ?></span>
</h4>

<div class="card">
    <div class="card-header bg-danger text-white fw-bold">
        Các lớp đang tham gia
    </div>

    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>STT</th>
                        <th>Mã lớp</th>
                        <th>Mã môn</th>
                        <th>Tên lớp</th>
                        <th>Thứ</th>
                        <th>Thời gian</th>
                        <th>Phòng</th>
                        <th>Giảng viên</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($classes)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Sinh viên chưa được xếp lớp
                        </td>
                    </tr>
                <?php else: $i = 1; foreach ($classes as $c): ?>
                    <tr class="text-center">
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($c['code']) ?></td>
                        <td><?= htmlspecialchars($c['course_code']) ?></td>
                        <td class="text-start"><?= htmlspecialchars($c['name']) ?></td>
                        <td><?= htmlspecialchars($c['class_day']) ?></td>
                        <td><?= htmlspecialchars($c['class_time']) ?></td>
                        <td><?= htmlspecialchars($c['room']) ?></td>
                        <td><?= htmlspecialchars($c['lecturer_name'] ?? '—') ?></td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
