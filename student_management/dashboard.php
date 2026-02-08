<?php
session_start();
require "config/db.php";

/* ===== THỐNG KÊ ===== */
$studentCount    = $conn->query("SELECT COUNT(*) FROM students")->fetchColumn();
$lecturerCount   = $conn->query("SELECT COUNT(*) FROM lecturers")->fetchColumn();
$classCount      = $conn->query("SELECT COUNT(*) FROM classes")->fetchColumn();
$departmentCount = $conn->query("SELECT COUNT(*) FROM departments")->fetchColumn();

/* ===== SINH VIÊN MỚI ===== */
$latestStudents = $conn->query("
    SELECT student_code, full_name
    FROM students
    ORDER BY id DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

/* ===== THỐNG KÊ SV THEO KHOA ===== */
$studentsByDept = $conn->query("
    SELECT d.name AS department_name, COUNT(s.id) AS total_students
    FROM departments d
    LEFT JOIN students s ON s.department_id = d.id
    GROUP BY d.id
")->fetchAll(PDO::FETCH_ASSOC);

/* ===== TOP 3 SINH VIÊN GPA CAO NHẤT ===== */
$topStudents = $conn->query("
    SELECT 
        s.id,
        s.student_code,
        s.full_name,
        ROUND(AVG(ss.score), 2) AS gpa
    FROM students s
    JOIN student_subject_scores ss 
        ON ss.student_id = s.id
    WHERE ss.score IS NOT NULL
    GROUP BY s.id, s.student_code, s.full_name
    HAVING COUNT(ss.score) > 0
    ORDER BY gpa DESC
    LIMIT 3
")->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.card-hust {
  border-left: 6px solid #8B0000;
}
.card-hust h5 {
  color: #8B0000;
}
.card-icon {
  width: 48px;
  height: 48px;
}
</style>
</head>

<body>
<?php include "includes/navbar.php"; ?>

<div class="container mt-4">

<h2 class="mb-4">DASHBOARD – TỔNG QUAN HỆ THỐNG</h2>

<!-- ===== CARD THỐNG KÊ ===== -->
<div class="row g-3 mb-4">

<div class="col-md-3">
  <div class="card card-hust shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-center">
      <div>
        <h5>Sinh viên</h5>
        <h3><?= $studentCount ?></h3>
      </div>
      <img src="assets/images/student.png" class="card-icon">
    </div>
  </div>
</div>

<div class="col-md-3">
  <div class="card card-hust shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-center">
      <div>
        <h5>Giảng viên</h5>
        <h3><?= $lecturerCount ?></h3>
      </div>
      <img src="assets/images/lecturer.png" class="card-icon">
    </div>
  </div>
</div>

<div class="col-md-3">
  <div class="card card-hust shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-center">
      <div>
        <h5>Lớp học</h5>
        <h3><?= $classCount ?></h3>
      </div>
      <img src="assets/images/class.png" class="card-icon">
    </div>
  </div>
</div>

<div class="col-md-3">
  <div class="card card-hust shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-center">
      <div>
        <h5>Khoa</h5>
        <h3><?= $departmentCount ?></h3>
      </div>
      <img src="assets/images/department.png" class="card-icon">
    </div>
  </div>
</div>

</div>


<!-- ===== SINH VIÊN MỚI ===== -->
<div class="card shadow-sm mb-4">
<div class="card-header bg-danger text-white">
Sinh viên mới thêm
</div>
<div class="card-body">
<ul class="list-group">
<?php foreach ($latestStudents as $s): ?>
<li class="list-group-item d-flex justify-content-between">
  <span><?= htmlspecialchars($s['full_name']) ?></span>
  <span class="text-muted"><?= htmlspecialchars($s['student_code']) ?></span>
</li>
<?php endforeach; ?>
</ul>
</div>
</div>


<!-- ===== THỐNG KÊ SV THEO KHOA ===== -->
<div class="card shadow-sm mb-4">
<div class="card-header bg-danger text-white">
Số lượng sinh viên theo khoa
</div>
<div class="card-body">
<table class="table table-bordered">
<tr>
<th>Khoa</th>
<th>Số sinh viên</th>
</tr>
<?php foreach ($studentsByDept as $d): ?>
<tr>
<td><?= $d['department_name'] ?></td>
<td><?= $d['total_students'] ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>
</div>

<!-- ===== TOP 3 SINH VIÊN GPA CAO NHẤT ===== -->
<div class="card shadow-sm mb-4">
<div class="card-header bg-danger text-white">
Top 3 sinh viên có GPA cao nhất
</div>
<div class="card-body">

<table class="table table-bordered table-striped mb-0">
<thead>
<tr>
    <th>#</th>
    <th>Mã SV</th>
    <th>Họ tên</th>
    <th>GPA</th>
</tr>
</thead>
<tbody>
<?php $i = 1; foreach ($topStudents as $st): ?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($st['student_code']) ?></td>
    <td><?= htmlspecialchars($st['full_name']) ?></td>
    <td><?= $st['gpa'] ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php if (empty($topStudents)): ?>
<div class="text-muted">Chưa có dữ liệu điểm.</div>
<?php endif; ?>
</div>
</div>


</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
