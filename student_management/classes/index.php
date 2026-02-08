<?php
session_start();
require __DIR__ . '/../includes/auth_check.php';
require "../config/db.php";

/* ===== LẤY GIẢNG VIÊN ===== */
$lecturers = $conn->query("
    SELECT id, full_name
    FROM lecturers
    ORDER BY full_name
")->fetchAll(PDO::FETCH_ASSOC);

/* ===== TÌM KIẾM ===== */
$keyword = $_GET['keyword'] ?? '';

$sql = "
SELECT c.*, l.full_name AS lecturer_name
FROM classes c
LEFT JOIN lecturers l ON c.lecturer_id = l.id
WHERE 1
";

$params = [];

if ($keyword !== '') {
    $sql .= " AND (c.code LIKE :kw OR c.name LIKE :kw OR c.course_code LIKE :kw)";
    $params[':kw'] = "%$keyword%";
}

$sql .= " ORDER BY c.id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý lớp</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php include "../includes/navbar.php"; ?>

<div class="container mt-4">
<h2>DANH SÁCH LỚP HỌC</h2>

<!-- ===== ADD + SEARCH (GIỐNG STUDENTS) ===== -->
<div class="d-flex justify-content-between align-items-center mb-3">

<?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'staff'])): ?>
<button class="btn btn-success btn-sm"
data-bs-toggle="modal"
data-bs-target="#addClassModal">
+ Thêm lớp
</button>
<?php endif; ?>

<form class="d-flex gap-2" method="get">
<input class="form-control form-control-sm"
name="keyword"
placeholder="Mã lớp / Tên lớp / Mã môn"
value="<?= htmlspecialchars($keyword) ?>">

<button class="btn btn-primary btn-sm">Tìm</button>
<a href="index.php" class="btn btn-secondary btn-sm">Reset</a>
</form>

</div>

<!-- ===== TABLE ===== -->
<table class="table table-bordered table-hover align-middle">
<thead>
<tr>
<th>STT</th>
<th>Mã lớp</th>
<th>Mã môn</th>
<th>Tên lớp</th>
<th>Giảng viên</th>
<th>Thời gian</th>
<th>Phòng</th>
<th>Hành động</th>
</tr>
</thead>

<tbody>
<?php if (count($classes) === 0): ?>
<tr>
<td colspan="8" class="text-center text-muted">Chưa có lớp</td>
</tr>
<?php else: ?>
<?php $stt = 1; foreach ($classes as $c): ?>
<tr>
<td><?= $stt++ ?></td>
<td><?= htmlspecialchars($c['code']) ?></td>
<td><?= htmlspecialchars($c['course_code']) ?></td>
<td><?= htmlspecialchars($c['name']) ?></td>
<td><?= htmlspecialchars($c['lecturer_name'] ?? '—') ?></td>
<td><?= htmlspecialchars($c['class_day']) ?> - <?= htmlspecialchars($c['class_time']) ?></td>
<td><?= htmlspecialchars($c['room']) ?></td>

<?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'staff'])): ?>
<td>
<a href="view.php?class_id=<?= $c['id'] ?>"
class="btn btn-info btn-sm">Xem</a>

<button class="btn btn-warning btn-sm editBtn"
data-id="<?= $c['id'] ?>"
data-code="<?= htmlspecialchars($c['code']) ?>"
data-course="<?= htmlspecialchars($c['course_code']) ?>"
data-name="<?= htmlspecialchars($c['name']) ?>"
data-lecturer="<?= $c['lecturer_id'] ?>"
data-day="<?= htmlspecialchars($c['class_day']) ?>"
data-time="<?= htmlspecialchars($c['class_time']) ?>"
data-room="<?= htmlspecialchars($c['room']) ?>"
data-bs-toggle="modal"
data-bs-target="#editClassModal">
Sửa
</button>

<a href="delete.php?id=<?= $c['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Xóa lớp?')">Xóa</a>
</td>
<?php endif; ?>

</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
</div>

<!-- ================= MODAL THÊM LỚP ================= -->
<div class="modal fade" id="addClassModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<form method="post" action="insert.php">
<div class="modal-header">
<h5 class="modal-title">Thêm lớp</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body row g-3">
<div class="col-md-6">
<label>Mã lớp</label>
<input name="code" class="form-control" required>
</div>

<div class="col-md-6">
<label>Mã môn</label>
<input name="course_code" class="form-control" required>
</div>

<div class="col-md-6">
<label>Tên lớp</label>
<input name="name" class="form-control" required>
</div>

<div class="col-md-6">
<label>Giảng viên</label>
<select name="lecturer_id" class="form-select">
<option value="">-- Chọn giảng viên --</option>
<?php foreach ($lecturers as $l): ?>
<option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['full_name']) ?></option>
<?php endforeach; ?>
</select>
</div>

<div class="col-md-4">
<label>Thứ</label>
<input name="class_day" class="form-control">
</div>

<div class="col-md-4">
<label>Thời gian</label>
<input name="class_time" class="form-control">
</div>

<div class="col-md-4">
<label>Phòng</label>
<input name="room" class="form-control">
</div>
</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
<button class="btn btn-success">Thêm</button>
</div>
</form>

</div>
</div>
</div>

<!-- ================= MODAL SỬA LỚP ================= -->
<div class="modal fade" id="editClassModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<form method="post" action="update.php">
<input type="hidden" name="id" id="edit-id">

<div class="modal-header">
<h5 class="modal-title">Sửa lớp</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body row g-3">
<div class="col-md-6">
<label>Mã lớp</label>
<input name="code" id="edit-code" class="form-control" required>
</div>

<div class="col-md-6">
<label>Mã môn</label>
<input name="course_code" id="edit-course" class="form-control" required>
</div>

<div class="col-md-6">
<label>Tên lớp</label>
<input name="name" id="edit-name" class="form-control" required>
</div>

<div class="col-md-6">
<label>Giảng viên</label>
<select name="lecturer_id" id="edit-lecturer" class="form-select">
<option value="">-- Chọn giảng viên --</option>
<?php foreach ($lecturers as $l): ?>
<option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['full_name']) ?></option>
<?php endforeach; ?>
</select>
</div>

<div class="col-md-4">
<label>Thứ</label>
<input name="class_day" id="edit-day" class="form-control">
</div>

<div class="col-md-4">
<label>Thời gian</label>
<input name="class_time" id="edit-time" class="form-control">
</div>

<div class="col-md-4">
<label>Phòng</label>
<input name="room" id="edit-room" class="form-control">
</div>
</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
<button class="btn btn-warning">Cập nhật</button>
</div>
</form>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const editButtons = document.querySelectorAll('.editBtn');
editButtons.forEach(btn => {
  btn.onclick = function () {
    document.getElementById('edit-id').value = this.dataset.id;
    document.getElementById('edit-code').value = this.dataset.code;
    document.getElementById('edit-course').value = this.dataset.course;
    document.getElementById('edit-name').value = this.dataset.name;
    document.getElementById('edit-lecturer').value = this.dataset.lecturer;
    document.getElementById('edit-day').value = this.dataset.day;
    document.getElementById('edit-time').value = this.dataset.time;
    document.getElementById('edit-room').value = this.dataset.room;
  };
});
</script>

</body>
</html>
