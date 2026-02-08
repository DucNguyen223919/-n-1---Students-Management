<?php
session_start();
require __DIR__ . '/../includes/auth_check.php';
require "../config/db.php";

/* ===== FILTER ===== */
$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT * FROM majors WHERE 1";
$params = [];

if ($keyword !== '') {
    $sql .= " AND (code LIKE :kw OR name LIKE :kw)";
    $params[':kw'] = "%$keyword%";
}

$sql .= " ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$majors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý ngành học</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php include "../includes/navbar.php"; ?>

<div class="container mt-4">
<h2>DANH SÁCH NGÀNH HỌC</h2>

<!-- ADD + SEARCH -->
<div class="d-flex justify-content-between align-items-center mb-3">

<?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'staff'])): ?>  
<button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addMajorModal">
+ Thêm ngành
</button>
<?php endif; ?>

<form class="d-flex gap-2" method="get">
<input class="form-control form-control-sm"
       name="keyword"
       placeholder="Mã / Tên ngành"
       value="<?= htmlspecialchars($keyword) ?>">
<button class="btn btn-primary btn-sm">Tìm</button>
<a href="index.php" class="btn btn-secondary btn-sm">Reset</a>
</form>
</div>

<!-- TABLE -->
<table class="table table-bordered table-hover align-middle">
<thead>
<tr>
<th>STT</th>
<th>Mã ngành</th>
<th>Tên ngành</th>
<th>Hành động</th>
</tr>
</thead>

<tbody>
<?php if (!$majors): ?>
<tr>
<td colspan="4" class="text-center text-muted">Chưa có ngành học</td>
</tr>
<?php else: $stt = 1; foreach ($majors as $m): ?>
<tr>
<td><?= $stt++ ?></td>
<td><?= htmlspecialchars($m['code']) ?></td>
<td><?= htmlspecialchars($m['name']) ?></td>

<?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'staff'])): ?>
<td>
<a href="view.php?id=<?= $m['id'] ?>" class="btn btn-info btn-sm">
  Chương trình học
</a>


<button class="btn btn-warning btn-sm editBtn"
data-id="<?= $m['id'] ?>"
data-code="<?= htmlspecialchars($m['code']) ?>"
data-name="<?= htmlspecialchars($m['name']) ?>"
data-bs-toggle="modal"
data-bs-target="#editMajorModal">
Sửa
</button>


<a href="delete.php?id=<?= $m['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Xóa ngành này?')">
Xóa
</a>
</td>
<?php endif; ?>

</tr>
<?php endforeach; endif; ?>
</tbody>
</table>
</div>

<!-- ================= MODAL ADD ================= -->
<div class="modal fade" id="addMajorModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

<div class="modal-header bg-success text-white">
<h5 class="modal-title">Thêm ngành học</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<form method="post" action="add.php">
<div class="modal-body">

<label class="form-label">Mã ngành</label>
<input name="code" class="form-control mb-2" required>

<label class="form-label">Tên ngành</label>
<input name="name" class="form-control mb-2" required>

</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
<button class="btn btn-success">Lưu</button>
</div>
</form>

</div>
</div>
</div>

<!-- ================= MODAL EDIT ================= -->
<div class="modal fade" id="editMajorModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

<div class="modal-header bg-warning">
<h5 class="modal-title">Cập nhật ngành học</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form method="post" action="edit.php">
<input type="hidden" name="id" id="edit-id">

<div class="modal-body">

<label class="form-label">Mã ngành</label>
<input name="code" id="edit-code" class="form-control mb-2" required>

<label class="form-label">Tên ngành</label>
<input name="name" id="edit-name" class="form-control mb-2" required>

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
    document.getElementById('edit-id').value   = btn.dataset.id;
    document.getElementById('edit-code').value = btn.dataset.code;
    document.getElementById('edit-name').value = btn.dataset.name;
  });
});
</script>

</body>
</html>
