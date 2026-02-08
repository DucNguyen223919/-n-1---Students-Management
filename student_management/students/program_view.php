<?php
session_start();
require __DIR__ . '/../includes/auth_check.php';
require __DIR__ . '/../config/db.php';

/* ===== CHỈ CHO SINH VIÊN XEM ===== */
if (!isset($_SESSION['username']) || ($_SESSION['role'] ?? '') !== 'viewer') {
    die("Không có quyền truy cập");
}

$student_code = $_SESSION['username'];

/* ===== LẤY THÔNG TIN SINH VIÊN ===== */
$stmt = $conn->prepare("
    SELECT id, major_id
    FROM students
    WHERE student_code = ?
");
$stmt->execute([$student_code]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Không tìm thấy sinh viên");
}

/* ===== LẤY DANH SÁCH MÔN + ĐIỂM ===== */
$stmt = $conn->prepare("
    SELECT 
        sub.code,
        sub.name,
        sub.credits,
        ss.score
    FROM major_subjects ms
    JOIN subjects sub ON ms.subject_id = sub.id
    LEFT JOIN student_subject_scores ss 
        ON ss.subject_id = sub.id
        AND ss.student_id = ?
    WHERE ms.major_id = ?
    ORDER BY sub.code
");
$stmt->execute([$student['id'], $student['major_id']]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* ===== KIỂM TRA HOÀN THÀNH CTRINH ===== */

$totalSubjects = count($subjects);
$scoredSubjects = 0;

foreach ($subjects as $s) {
    if ($s['score'] !== null) {
        $scoredSubjects++;
    }
}

$isCompleted = ($totalSubjects > 0 && $scoredSubjects === $totalSubjects);

/* ===== TÍNH GPA ===== */
$totalCredits = 0;
$totalScore   = 0;

foreach ($subjects as $s) {
    if ($s['score'] !== null) {
        $totalCredits += $s['credits'];
        $totalScore   += $s['score'] * $s['credits'];
    }
}

$gpa = $totalCredits > 0 ? round($totalScore / $totalCredits, 2) : null;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Kết quả học tập</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid p-0">

<div class="card m-3">
    <div class="card-header bg-danger text-white fw-bold">
        📊 KẾT QUẢ HỌC TẬP
    </div>

    <div class="card-body">

        <!-- GPA -->
        <div class="mb-3">
            <span class="badge bg-primary fs-6">
                GPA: <?= $gpa !== null ? $gpa : 'Chưa đủ dữ liệu' ?>
            </span>
        </div>

        <!-- CTRINH HOC -->
        <div class="mb-3">
            <?php if ($isCompleted): ?>
                <span class="badge bg-success fs-6">
                    ✔ Đã hoàn thành chương trình học
                </span>
            <?php else: ?>
                <span class="badge bg-warning text-dark fs-6">
                    ✘ Chưa hoàn thành chương trình học
                </span>
            <?php endif; ?>
        </div>


        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="width:60px">STT</th>
                        <th style="width:120px">Mã môn</th>
                        <th>Tên môn</th>
                        <th style="width:90px">Tín chỉ</th>
                        <th style="width:90px">Điểm</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($subjects)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Chưa có môn học
                        </td>
                    </tr>
                <?php else: $i = 1; foreach ($subjects as $s): ?>
                    <tr class="text-center">
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($s['code']) ?></td>
                        <td class="text-start"><?= htmlspecialchars($s['name']) ?></td>
                        <td><?= $s['credits'] ?></td>
                        <td>
                            <?= $s['score'] !== null
                                ? '<span class="fw-bold">'.$s['score'].'</span>'
                                : '<span class="text-muted">—</span>' ?>
                        </td>
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
