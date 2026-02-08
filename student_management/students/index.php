<?php
session_start();
require __DIR__ . '/../includes/auth_check.php';
require __DIR__ . '/sort.php';
require "../config/db.php";


/* ===== FILTER ===== */
$keyword     = $_GET['keyword'] ?? '';
$majorClass  = $_GET['major_class'] ?? '';
$major       = $_GET['major'] ?? '';      // major_id
$dept        = $_GET['department'] ?? '';

/* ===== DEPARTMENTS ===== */
$departments = $conn->query("SELECT * FROM departments")->fetchAll(PDO::FETCH_ASSOC);

/* ===== MAJORS ===== */
$majors = $conn->query("SELECT id, name FROM majors ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

/* ===== STUDENTS ===== */
$sql = "
SELECT s.*,
       d.name AS department_name,
       m.name AS major_name
FROM students s
LEFT JOIN departments d ON s.department_id = d.id
LEFT JOIN majors m ON s.major_id = m.id
WHERE 1
";

$params = [];

if ($keyword !== '') {
    $sql .= " AND (s.student_code LIKE :kw OR s.full_name LIKE :kw)";
    $params[':kw'] = "%$keyword%";
}
if ($majorClass !== '') {
    $sql .= " AND s.major_class LIKE :majorClass";
    $params[':majorClass'] = "%$majorClass%";
}
if ($major !== '') {
    $sql .= " AND s.major_id = :major";
    $params[':major'] = $major;
}
if ($dept !== '') {
    $sql .= " AND s.department_id = :dept";
    $params[':dept'] = $dept;
}

$sql .= $orderBy;

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý sinh viên</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php include "../includes/navbar.php"; ?>

<div class="container mt-4">
<h2>DANH SÁCH SINH VIÊN</h2>

<!-- ADD + SEARCH -->
<div class="d-flex justify-content-between align-items-center mb-3">

<div class="d-flex gap-2 align-items-center">

<?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'staff'])): ?>
<button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
+ Thêm sinh viên
</button>
<?php endif; ?>

<!-- SORT BUTTONS -->
<div class="btn-group btn-group-sm">
<a class="btn btn-outline-secondary"
   href="?sort=student_code&order=<?= ($sort==='student_code' && $order==='ASC')?'desc':'asc' ?>">
MSSV
</a>

<a class="btn btn-outline-secondary"
   href="?sort=full_name&order=<?= ($sort==='full_name' && $order==='ASC')?'desc':'asc' ?>">
Họ tên
</a>

<a class="btn btn-outline-secondary"
   href="?sort=major_class&order=<?= ($sort==='major_class' && $order==='ASC')?'desc':'asc' ?>">
Lớp ngành
</a>
</div>

</div>


<form class="d-flex gap-2" method="get">
<input class="form-control form-control-sm" name="keyword" placeholder="MSSV / Họ tên" value="<?= htmlspecialchars($keyword) ?>">
<input class="form-control form-control-sm" name="major_class" placeholder="Lớp ngành" value="<?= htmlspecialchars($majorClass) ?>">

<select name="major" class="form-select form-select-sm">
<option value="">-- Ngành --</option>
<?php foreach ($majors as $m): ?>
<option value="<?= $m['id'] ?>" <?= $major==$m['id']?'selected':'' ?>>
<?= htmlspecialchars($m['name']) ?>
</option>
<?php endforeach; ?>
</select>

<select name="department" class="form-select form-select-sm">
<option value="">-- Khoa --</option>
<?php foreach ($departments as $d): ?>
<option value="<?= $d['id'] ?>" <?= $dept==$d['id']?'selected':'' ?>>
<?= htmlspecialchars($d['name']) ?>
</option>
<?php endforeach; ?>
</select>

<button class="btn btn-primary btn-sm">Lọc</button>
<a href="index.php" class="btn btn-secondary btn-sm">Reset</a>
</form>
</div>

<?php if (!empty($_GET['error'])): ?>
<div class="alert alert-danger">
    <?= htmlspecialchars($_GET['error']) ?>
</div>
<?php endif; ?>


<!-- TABLE -->
<table class="table table-bordered table-hover align-middle">
<thead>
<tr>
<th>STT</th>
<th>MSSV</th>
<th>Họ tên</th>
<th>Lớp ngành</th>
<th>Ngành</th>
<th>Khoa</th>
<th>Hành động</th>
</tr>
</thead>

<tbody>
<?php $stt=1; foreach ($students as $s): ?>
<tr>
<td><?= $stt++ ?></td>
<td><?= htmlspecialchars($s['student_code']) ?></td>
<td><?= htmlspecialchars($s['full_name']) ?></td>
<td><?= htmlspecialchars($s['major_class']) ?></td>
<td><?= htmlspecialchars($s['major_name'] ?? '—') ?></td>
<td><?= htmlspecialchars($s['department_name']) ?></td>

<?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'staff'])): ?>
<td>
<button class="btn btn-info btn-sm viewBtn"
data-id="<?= $s['id'] ?>"
data-code="<?= htmlspecialchars($s['student_code']) ?>"
data-name="<?= htmlspecialchars($s['full_name']) ?>"
data-email="<?= htmlspecialchars($s['email']) ?>"
data-gender="<?= htmlspecialchars($s['gender']) ?>"
data-phone="<?= htmlspecialchars($s['phone']) ?>"
data-cohort="<?= htmlspecialchars($s['cohort']) ?>"
data-hometown="<?= htmlspecialchars($s['hometown']) ?>"
data-majorclass="<?= htmlspecialchars($s['major_class']) ?>"
data-major="<?= htmlspecialchars($s['major_name']) ?>"
data-dept="<?= htmlspecialchars($s['department_name']) ?>"
data-avatar="<?= htmlspecialchars($s['avatar']) ?>"
data-bs-toggle="modal"
data-bs-target="#viewStudentModal">
Chi tiết
</button>

<a href="program.php?id=<?= $s['id'] ?>" 
   class="btn btn-info btn-sm">
Chương trình học
</a>


<button class="btn btn-warning btn-sm editBtn"
data-id="<?= $s['id'] ?>"
data-code="<?= htmlspecialchars($s['student_code']) ?>"
data-name="<?= htmlspecialchars($s['full_name']) ?>"
data-email="<?= htmlspecialchars($s['email']) ?>"
data-majorclass="<?= htmlspecialchars($s['major_class']) ?>"
data-major="<?= $s['major_id'] ?>"
data-deptid="<?= $s['department_id'] ?>"
data-gender="<?= htmlspecialchars($s['gender']) ?>"
data-phone="<?= htmlspecialchars($s['phone']) ?>"
data-cohort="<?= htmlspecialchars($s['cohort']) ?>"
data-hometown="<?= htmlspecialchars($s['hometown']) ?>"
data-bs-toggle="modal"
data-bs-target="#editStudentModal">
Sửa
</button>

<a href="delete.php?id=<?= $s['id'] ?>" class="btn btn-danger btn-sm"
onclick="return confirm('Xóa sinh viên?')">Xóa</a>
</td>
<?php endif; ?>

</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<!-- ================= MODAL ADD ================= -->
<div class="modal fade" id="addStudentModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<div class="modal-header bg-success text-white">
<h5 class="modal-title">Thêm sinh viên</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<form method="post" action="insert.php" enctype="multipart/form-data">
<div class="modal-body">

<label>Ảnh sinh viên</label>
<input type="file" name="avatar" class="form-control mb-2">

<label>Mã số sinh viên</label>
<input name="student_code" class="form-control mb-2" required>

<label>Họ và tên</label>
<input name="full_name" class="form-control mb-2" required>

<label>Email</label>
<input type="email" name="email" class="form-control mb-2">

<label>Lớp ngành</label>
<input name="major_class" class="form-control mb-2">

<label>Ngành</label>
<select name="major_id" class="form-select mb-2">
<?php foreach ($majors as $m): ?>
<option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
<?php endforeach; ?>
</select>

<label>Khoa</label>
<select name="department_id" class="form-select mb-2">
<?php foreach ($departments as $d): ?>
<option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
<?php endforeach; ?>
</select>

<label>Giới tính</label>
<select name="gender" class="form-select mb-2">
<option value="">-- Chọn --</option>
<option>Nam</option>
<option>Nữ</option>
<option>Khác</option>
</select>

<label>SĐT</label>
<input name="phone" class="form-control mb-2">

<label>Niên khóa</label>
<input name="cohort" class="form-control mb-2">

<label>Quê quán</label>
<input name="hometown" class="form-control mb-2">

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
<div class="modal fade" id="editStudentModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<div class="modal-header bg-warning">
<h5 class="modal-title">Cập nhật sinh viên</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form method="post" action="update.php" enctype="multipart/form-data">
<input type="hidden" name="id" id="edit-id">

<div class="modal-body">

<label>Ảnh sinh viên</label>
<input type="file" name="avatar" class="form-control mb-2">

<label>Mã số sinh viên</label>
<input name="student_code" id="edit-code" class="form-control mb-2" required>

<label>Họ và tên</label>
<input name="full_name" id="edit-name" class="form-control mb-2" required>

<label>Email</label>
<input type="email" name="email" id="edit-email" class="form-control mb-2">

<label>Lớp ngành</label>
<input name="major_class" id="edit-majorclass" class="form-control mb-2">

<label>Ngành</label>
<select name="major_id" id="edit-major" class="form-select mb-2">
<?php foreach ($majors as $m): ?>
<option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
<?php endforeach; ?>
</select>

<label>Khoa</label>
<select name="department_id" id="edit-deptid" class="form-select mb-2">
<?php foreach ($departments as $d): ?>
<option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
<?php endforeach; ?>
</select>

<label>Giới tính</label>
<select name="gender" id="edit-gender" class="form-select mb-2">
<option value="">-- Chọn --</option>
<option>Nam</option>
<option>Nữ</option>
<option>Khác</option>
</select>

<label>SĐT</label>
<input name="phone" id="edit-phone" class="form-control mb-2">

<label>Niên khóa</label>
<input name="cohort" id="edit-cohort" class="form-control mb-2">

<label>Quê quán</label>
<input name="hometown" id="edit-hometown" class="form-control mb-2">

</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
<button class="btn btn-warning">Cập nhật</button>
</div>
</form>

</div>
</div>
</div>

<!-- ================= MODAL VIEW ================= -->
<div class="modal fade" id="viewStudentModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<div class="modal-header bg-info text-white">
<h5 class="modal-title">Chi tiết sinh viên</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<div class="text-center mb-3">
<img id="v-avatar" src="../uploads/students/default.png"
width="120" height="160" style="object-fit:cover">
</div>

<p><b>MSSV:</b> <span id="v-code"></span></p>
<p><b>Họ tên:</b> <span id="v-name"></span></p>
<p><b>Email:</b> <span id="v-email"></span></p>
<p><b>Giới tính:</b> <span id="v-gender"></span></p>
<p><b>SĐT:</b> <span id="v-phone"></span></p>
<p><b>Niên khóa:</b> <span id="v-cohort"></span></p>
<p><b>Quê quán:</b> <span id="v-hometown"></span></p>
<p><b>Lớp ngành:</b> <span id="v-majorclass"></span></p>
<p><b>Ngành:</b> <span id="v-major"></span></p>
<p><b>Khoa:</b> <span id="v-dept"></span></p>

<hr>

</div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* EDIT */
document.querySelectorAll('.editBtn').forEach(btn => {
  btn.onclick = () => {
    document.getElementById('edit-id').value = btn.dataset.id;
    document.getElementById('edit-code').value = btn.dataset.code;
    document.getElementById('edit-name').value = btn.dataset.name;
    document.getElementById('edit-email').value = btn.dataset.email;
    document.getElementById('edit-majorclass').value = btn.dataset.majorclass;
    document.getElementById('edit-major').value = btn.dataset.major;
    document.getElementById('edit-deptid').value = btn.dataset.deptid;
    document.getElementById('edit-gender').value = btn.dataset.gender;
    document.getElementById('edit-phone').value = btn.dataset.phone;
    document.getElementById('edit-cohort').value = btn.dataset.cohort;
    document.getElementById('edit-hometown').value = btn.dataset.hometown;
  };
});

/* VIEW */
document.querySelectorAll('.viewBtn').forEach(btn => {
  btn.onclick = () => {
    document.getElementById('v-avatar').src =
      btn.dataset.avatar ? `../uploads/students/${btn.dataset.avatar}` : `../uploads/students/default.png`;
    document.getElementById('v-code').innerText = btn.dataset.code;
    document.getElementById('v-name').innerText = btn.dataset.name;
    document.getElementById('v-email').innerText = btn.dataset.email;
    document.getElementById('v-gender').innerText = btn.dataset.gender;
    document.getElementById('v-phone').innerText = btn.dataset.phone;
    document.getElementById('v-cohort').innerText = btn.dataset.cohort;
    document.getElementById('v-hometown').innerText = btn.dataset.hometown;
    document.getElementById('v-majorclass').innerText = btn.dataset.majorclass;
    document.getElementById('v-major').innerText = btn.dataset.major;
    document.getElementById('v-dept').innerText = btn.dataset.dept;
  };
});
</script>

</body>
</html>
