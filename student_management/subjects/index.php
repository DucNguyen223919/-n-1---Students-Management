<?php
session_start();
require __DIR__ . '/../includes/auth_check.php';
require "../config/db.php";

/* ================== FILTER ================== */
$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT * FROM subjects WHERE 1";
$params = [];

if ($keyword !== '') {
    $sql .= " AND (code LIKE :kw OR name LIKE :kw)";
    $params[':kw'] = "%$keyword%";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý môn học</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php include "../includes/navbar.php"; ?>

<div class="container mt-4">
<h2>DANH SÁCH MÔN HỌC</h2>

<!-- ========== ADD + SEARCH (ĐỒNG BỘ) ========== -->
<div class="d-flex justify-content-between align-items-center mb-3">

<?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'staff'])): ?>
<button class="btn btn-success btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#addSubjectModal">
    + Thêm môn học
</button>
<?php endif; ?>

<form class="d-flex gap-2" method="get">
<input class="form-control form-control-sm"
       name="keyword"
       placeholder="Mã môn / Tên môn"
       value="<?= htmlspecialchars($keyword) ?>">
<button class="btn btn-primary btn-sm">Tìm</button>
<a href="index.php" class="btn btn-secondary btn-sm">Reset</a>
</form>

</div>

<!-- ================== TABLE ================== -->
<table class="table table-bordered table-hover align-middle">
<thead>
<tr>
<th>STT</th>
<th>Mã môn</th>
<th>Tên môn</th>
<th>Tín chỉ</th>
<th>Hành động</th>
</tr>
</thead>

<tbody>
<?php if (!$subjects): ?>
<tr>
<td colspan="5" class="text-center text-muted">Chưa có môn học</td>
</tr>
<?php else: $stt = 1; foreach ($subjects as $s): ?>
<tr>
<td><?= $stt++ ?></td>
<td><?= htmlspecialchars($s['code']) ?></td>
<td><?= htmlspecialchars($s['name']) ?></td>
<td><?= $s['credits'] ?></td>

<?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'staff'])): ?>
<td>
<button class="btn btn-warning btn-sm editBtn"
    data-id="<?= $s['id'] ?>"
    data-code="<?= htmlspecialchars($s['code']) ?>"
    data-name="<?= htmlspecialchars($s['name']) ?>"
    data-credits="<?= $s['credits'] ?>"
    data-bs-toggle="modal"
    data-bs-target="#editSubjectModal">
    Sửa
</button>

<a href="delete.php?id=<?= $s['id'] ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Xóa môn học?')">
   Xóa
</a>
</td>
<?php endif; ?>

</tr>
<?php endforeach; endif; ?>
</tbody>
</table>
</div>

<!-- ================== MODAL ADD ================== -->
<div class="modal fade" id="addSubjectModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<div class="modal-header bg-success text-white">
<h5 class="modal-title">Thêm môn học</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<form method="post" action="insert.php">
<div class="modal-body">

<div class="row">
<div class="col-md-4 mb-3">
<label>Mã môn</label>
<input name="code" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Tên môn</label>
<input name="name" class="form-control" required>
</div>

<div class="col-md-2 mb-3">
<label>Tín chỉ</label>
<input type="number" name="credits" class="form-control" min="1" required>
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
<div class="modal fade" id="editSubjectModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<div class="modal-header bg-warning">
<h5 class="modal-title">Cập nhật môn học</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form method="post" action="update.php">
<input type="hidden" name="id" id="edit-id">

<div class="modal-body">

<div class="row">
<div class="col-md-4 mb-3">
<label>Mã môn</label>
<input name="code" id="edit-code" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Tên môn</label>
<input name="name" id="edit-name" class="form-control" required>
</div>

<div class="col-md-2 mb-3">
<label>Tín chỉ</label>
<input type="number" name="credits" id="edit-credits" class="form-control" min="1" required>
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
    document.getElementById('edit-id').value      = btn.dataset.id;
    document.getElementById('edit-code').value    = btn.dataset.code;
    document.getElementById('edit-name').value    = btn.dataset.name;
    document.getElementById('edit-credits').value = btn.dataset.credits;
  });
});
</script>

</body>
</html>
