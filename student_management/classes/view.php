<?php
session_start();
require __DIR__ . '/../includes/auth_check.php';
require "../config/db.php";

/* ===== KIỂM TRA class_id ===== */
if (!isset($_GET['class_id'])) {
    die("Thiếu class_id");
}
$class_id = $_GET['class_id'];

/* ===== LẤY THÔNG TIN LỚP ===== */
$stmt = $conn->prepare("
    SELECT 
        c.code,
        c.course_code,
        c.name,
        c.class_day,
        c.class_time,
        c.room,
        l.full_name AS lecturer_name
    FROM classes c
    LEFT JOIN lecturers l ON c.lecturer_id = l.id
    WHERE c.id = ?
");
$stmt->execute([$class_id]);
$class = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$class) {
    die('Lớp không tồn tại');
}

/* ===== SINH VIÊN TRONG LỚP ===== */
$stmt = $conn->prepare("
    SELECT
        cs.id AS cs_id,
        s.student_code,
        s.full_name,
        cs.attendance,
        cs.qt,
        cs.ck,
        cs.total
    FROM class_students cs
    JOIN students s ON cs.student_id = s.id
    WHERE cs.class_id = ?
    ORDER BY s.full_name
");
$stmt->execute([$class_id]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===== SINH VIÊN CHƯA CÓ TRONG LỚP ===== */
$stmt = $conn->prepare("
    SELECT id, student_code, full_name
    FROM students
    WHERE id NOT IN (
        SELECT student_id FROM class_students WHERE class_id = ?
    )
    ORDER BY full_name
");
$stmt->execute([$class_id]);
$available_students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Xem lớp</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php include "../includes/navbar.php"; ?>

<div class="container mt-4">

<h2>CHI TIẾT LỚP HỌC</h2>

<table class="table table-bordered w-75">
<tr><th>Mã lớp</th><td><?= htmlspecialchars($class['code']) ?></td></tr>
<tr><th>Mã môn</th><td><?= htmlspecialchars($class['course_code']) ?></td></tr>
<tr><th>Tên lớp</th><td><?= htmlspecialchars($class['name']) ?></td></tr>
<tr><th>Giảng viên</th><td><?= htmlspecialchars($class['lecturer_name'] ?? '—') ?></td></tr>
<tr><th>Thời gian</th><td><?= htmlspecialchars($class['class_day']) ?> - <?= htmlspecialchars($class['class_time']) ?></td></tr>
<tr><th>Phòng</th><td><?= htmlspecialchars($class['room']) ?></td></tr>
</table>

<hr>

<div class="d-flex justify-content-between align-items-center mb-2">
<h4>DANH SÁCH SINH VIÊN</h4>
<button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
➕ Thêm sinh viên
</button>
</div>

<table class="table table-bordered table-hover align-middle">
<thead>
<tr>
<th>STT</th>
<th>MSSV</th>
<th>Họ và tên</th>
<th>Điểm danh</th>
<th>QT</th>
<th>CK</th>
<th>Điểm</th>
</tr>
</thead>

<tbody>
<?php if (!$students): ?>
<tr><td colspan="7" class="text-center text-muted">Chưa có sinh viên</td></tr>
<?php else: $stt=1; foreach ($students as $s): ?>
<tr>
<td><?= $stt++ ?></td>
<td><?= htmlspecialchars($s['student_code']) ?></td>
<td><?= htmlspecialchars($s['full_name']) ?></td>

<td class="text-center">
<button class="btn btn-sm btn-outline-secondary minus" data-id="<?= $s['cs_id'] ?>">−</button>
<span id="att-<?= $s['cs_id'] ?>" class="mx-2"><?= $s['attendance'] ?></span>
<button class="btn btn-sm btn-outline-secondary plus" data-id="<?= $s['cs_id'] ?>">+</button>
</td>

<td><input type="number" step="0.1" class="form-control form-control-sm qt"
data-id="<?= $s['cs_id'] ?>" value="<?= $s['qt'] ?>"></td>

<td><input type="number" step="0.1" class="form-control form-control-sm ck"
data-id="<?= $s['cs_id'] ?>" value="<?= $s['ck'] ?>"></td>

<td id="total-<?= $s['cs_id'] ?>"><?= $s['total'] ?></td>
</tr>
<?php endforeach; endif; ?>
</tbody>
</table>

<a href="index.php" class="btn btn-secondary mt-3">← Quay lại</a>
</div>

<!-- ===== MODAL ADD STUDENT ===== -->
<div class="modal fade" id="addStudentModal">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<form method="post" action="add_student.php">
<input type="hidden" name="class_id" value="<?= $class_id ?>">
<div class="modal-header"><h5>Thêm sinh viên</h5></div>
<div class="modal-body">
<?php foreach ($available_students as $s): ?>
<div class="form-check">
<input class="form-check-input" type="checkbox" name="student_ids[]" value="<?= $s['id'] ?>">
<label class="form-check-label">
<?= $s['student_code'] ?> - <?= $s['full_name'] ?>
</label>
</div>
<?php endforeach; ?>
</div>
<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
<button class="btn btn-success">Thêm</button>
</div>
</form>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function post(url, data, cb){
  fetch(url,{
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:new URLSearchParams(data)
  }).then(r=>r.text()).then(cb);
}

document.querySelectorAll('.plus').forEach(b=>{
  b.onclick=()=>post('update_attendance.php',{id:b.dataset.id,step:'up'},
  v=>document.getElementById('att-'+b.dataset.id).innerText=v);
});

document.querySelectorAll('.minus').forEach(b=>{
  b.onclick=()=>post('update_attendance.php',{id:b.dataset.id,step:'down'},
  v=>document.getElementById('att-'+b.dataset.id).innerText=v);
});

document.querySelectorAll('.qt,.ck').forEach(i=>{
  i.onchange=()=>{
    post('update_score.php',
    {id:i.dataset.id,qt:document.querySelector('.qt[data-id="'+i.dataset.id+'"]').value,
     ck:document.querySelector('.ck[data-id="'+i.dataset.id+'"]').value},
    v=>document.getElementById('total-'+i.dataset.id).innerText=v);
  };
});
</script>

</body>
</html>
