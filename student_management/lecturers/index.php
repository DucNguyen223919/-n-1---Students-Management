<?php
session_start();
require __DIR__ . '/../includes/auth_check.php';
require "../config/db.php";

/* ================== FILTER ================== */
$keyword = $_GET['keyword'] ?? '';
$unit    = $_GET['unit'] ?? '';
$dept    = $_GET['department'] ?? '';

/* ================== DEPARTMENTS ================== */
$deptStmt = $conn->query("SELECT * FROM departments");
$departments = $deptStmt->fetchAll(PDO::FETCH_ASSOC);

/* ================== LECTURERS ================== */
$sql = "
SELECT l.*, d.name AS department_name
FROM lecturers l
LEFT JOIN departments d ON l.department_id = d.id
WHERE 1
";

$params = [];

if ($keyword !== '') {
    $sql .= " AND (l.lecturer_code LIKE :kw OR l.full_name LIKE :kw)";
    $params[':kw'] = "%$keyword%";
}

if ($unit !== '') {
    $sql .= " AND l.unit LIKE :unit";
    $params[':unit'] = "%$unit%";
}

if ($dept !== '') {
    $sql .= " AND l.department_id = :dept";
    $params[':dept'] = $dept;
}

$sql .= " ORDER BY l.id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$lecturers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý giảng viên</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php include "../includes/navbar.php"; ?>

<div class="container mt-4">
<h2>DANH SÁCH GIẢNG VIÊN</h2>

<!-- ========== ADD + SEARCH + FILTER (GIỐNG STUDENTS) ========== -->
<div class="d-flex justify-content-between align-items-center mb-3">

<?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'staff'])): ?>
<button class="btn btn-success btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#addLecturerModal">
    + Thêm giảng viên
</button>
<?php endif; ?>

<form class="d-flex gap-2" method="get">

<input class="form-control form-control-sm"
       name="keyword"
       placeholder="Mã GV / Họ tên"
       value="<?= htmlspecialchars($keyword) ?>">

<input class="form-control form-control-sm"
       name="unit"
       placeholder="Đơn vị"
       value="<?= htmlspecialchars($unit) ?>">

<select name="department" class="form-select form-select-sm">
<option value="">-- Khoa --</option>
<?php foreach ($departments as $d): ?>
<option value="<?= $d['id'] ?>" <?= $dept==$d['id']?'selected':'' ?>>
    <?= $d['name'] ?>
</option>
<?php endforeach; ?>
</select>

<button class="btn btn-primary btn-sm">Lọc</button>
<a href="index.php" class="btn btn-secondary btn-sm">Reset</a>
</form>

</div>

<!-- ================== TABLE ================== -->
<table class="table table-bordered table-hover align-middle">
<thead>
<tr>
<th>STT</th>
<th>Mã GV</th>
<th>Họ tên</th>
<th>Đơn vị</th>
<th>Chức vụ</th>
<th>Khoa</th>
<th>Email</th>
<th>Hành động</th>
</tr>
</thead>

<tbody>
<?php $stt = 1; foreach ($lecturers as $l): ?>
<tr>
<td><?= $stt++ ?></td>
<td><?= htmlspecialchars($l['lecturer_code']) ?></td>
<td><?= htmlspecialchars($l['full_name']) ?></td>
<td><?= htmlspecialchars($l['unit']) ?></td>
<td><?= htmlspecialchars($l['position']) ?></td>
<td><?= htmlspecialchars($l['department_name']) ?></td>
<td><?= htmlspecialchars($l['email']) ?></td>

<?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'staff'])): ?>
<td>
<button class="btn btn-warning btn-sm editBtn"
    data-id="<?= $l['id'] ?>"
    data-code="<?= htmlspecialchars($l['lecturer_code']) ?>"
    data-name="<?= htmlspecialchars($l['full_name']) ?>"
    data-email="<?= htmlspecialchars($l['email']) ?>"
    data-unit="<?= htmlspecialchars($l['unit']) ?>"
    data-position="<?= htmlspecialchars($l['position']) ?>"
    data-dept="<?= $l['department_id'] ?>"
    data-bs-toggle="modal"
    data-bs-target="#editLecturerModal">
    Sửa
</button>

<a href="delete.php?id=<?= $l['id'] ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Xóa giảng viên?')">
   Xóa
</a>
</td>
<?php endif; ?>

</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<!-- ================== MODAL ADD ================== -->
<div class="modal fade" id="addLecturerModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<div class="modal-header bg-success text-white">
<h5 class="modal-title">Thêm giảng viên</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<form method="post" action="insert.php">
<div class="modal-body">

<div class="row">
<div class="col-md-6 mb-3">
<label>Mã giảng viên</label>
<input name="lecturer_code" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Họ tên</label>
<input name="full_name" class="form-control" required>
</div>
</div>

<div class="row">
<div class="col-md-6 mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Đơn vị</label>
<input name="unit" class="form-control">
</div>
</div>

<div class="row">
<div class="col-md-6 mb-3">
<label>Chức vụ</label>
<input name="position" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Khoa</label>
<select name="department_id" class="form-select">
<option value="">-- Chọn khoa --</option>
<?php foreach ($departments as $d): ?>
<option value="<?= $d['id'] ?>"><?= $d['name'] ?></option>
<?php endforeach; ?>
</select>
</div>
</div>

</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
<button class="btn btn-success">Lưu</button>
</div>
</form>

</div>
</div>
</div>

<!-- ================== MODAL EDIT ================== -->
<div class="modal fade" id="editLecturerModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<div class="modal-header bg-warning">
<h5 class="modal-title">Cập nhật giảng viên</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form method="post" action="update.php">
<input type="hidden" name="id" id="edit-id">

<div class="modal-body">

<div class="row">
<div class="col-md-6 mb-3">
<label>Mã giảng viên</label>
<input name="lecturer_code" id="edit-code" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Họ tên</label>
<input name="full_name" id="edit-name" class="form-control" required>
</div>
</div>

<div class="row">
<div class="col-md-6 mb-3">
<label>Email</label>
<input name="email" id="edit-email" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Đơn vị</label>
<input name="unit" id="edit-unit" class="form-control">
</div>
</div>

<div class="row">
<div class="col-md-6 mb-3">
<label>Chức vụ</label>
<input name="position" id="edit-position" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Khoa</label>
<select name="department_id" id="edit-dept" class="form-select">
<?php foreach ($departments as $d): ?>
<option value="<?= $d['id'] ?>"><?= $d['name'] ?></option>
<?php endforeach; ?>
</select>
</div>
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
document.querySelectorAll('.editBtn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('edit-id').value       = btn.dataset.id;
    document.getElementById('edit-code').value     = btn.dataset.code;
    document.getElementById('edit-name').value     = btn.dataset.name;
    document.getElementById('edit-email').value    = btn.dataset.email;
    document.getElementById('edit-unit').value     = btn.dataset.unit;
    document.getElementById('edit-position').value = btn.dataset.position;
    document.getElementById('edit-dept').value     = btn.dataset.dept;
  });
});
</script>

</body>
</html>
