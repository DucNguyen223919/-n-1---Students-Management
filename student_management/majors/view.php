<?php
session_start();
require "../config/db.php";

$major_id = $_GET['id'] ?? 0;

/* ===== LẤY NGÀNH ===== */
$stmt = $conn->prepare("SELECT * FROM majors WHERE id=?");
$stmt->execute([$major_id]);
$major = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$major) die("Ngành không tồn tại");

/* ===== MÔN TRONG CHƯƠNG TRÌNH ===== */
$stmt = $conn->prepare("
  SELECT s.id, s.code, s.name, s.credits
  FROM major_subjects ms
  JOIN subjects s ON ms.subject_id = s.id
  WHERE ms.major_id = ?
  ORDER BY s.code
");
$stmt->execute([$major_id]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===== MÔN CHƯA CÓ ===== */
$stmt = $conn->prepare("
  SELECT * FROM subjects
  WHERE id NOT IN (
    SELECT subject_id FROM major_subjects WHERE major_id=?
  )
  ORDER BY code
");
$stmt->execute([$major_id]);
$availableSubjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Chương trình học</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php include "../includes/navbar.php"; ?>

<div class="container mt-4">

<h2>DANH SÁCH MÔN HỌC – <?= htmlspecialchars($major['name']) ?></h2>

<!-- ADD -->
<div class="d-flex justify-content-between align-items-center mb-3">

<button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
+ Thêm môn
</button>

<a href="index.php" class="btn btn-secondary btn-sm">← Quay lại</a>

</div>

<!-- TABLE -->
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
<td colspan="5" class="text-center text-muted">Chưa có môn trong chương trình</td>
</tr>
<?php else: $stt = 1; foreach ($subjects as $s): ?>
<tr>
<td><?= $stt++ ?></td>
<td><?= htmlspecialchars($s['code']) ?></td>
<td><?= htmlspecialchars($s['name']) ?></td>
<td><?= $s['credits'] ?></td>
<td>
<a href="delete_subject.php?major_id=<?= $major_id ?>&subject_id=<?= $s['id'] ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Xóa môn khỏi chương trình?')">
Xóa
</a>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody>
</table>

</div>

<!-- ================= MODAL ADD SUBJECT ================= -->
<div class="modal fade" id="addSubjectModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

<div class="modal-header bg-success text-white">
<h5 class="modal-title">Thêm môn vào chương trình</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<form method="post" action="add_subject.php">
<input type="hidden" name="major_id" value="<?= $major_id ?>">

<div class="modal-body">

<label class="form-label">Môn học</label>
<select name="subject_id" class="form-select" required>
<option value="">-- Chọn môn --</option>
<?php foreach ($availableSubjects as $s): ?>
<option value="<?= $s['id'] ?>">
<?= $s['code'] ?> - <?= $s['name'] ?> (<?= $s['credits'] ?> TC)
</option>
<?php endforeach; ?>
</select>

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

</body>
</html>
