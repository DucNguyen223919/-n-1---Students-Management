<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
.navbar-hust {
    background-color: #9E1B32;
}

.navbar-hust .nav-link,
.navbar-hust .navbar-brand {
    color: #fff;
    font-weight: 500;
}

.navbar-hust .nav-link:hover {
    color: #F2A900;
}

.navbar-brand img {
    height: 36px;
    margin-right: 8px;
}
</style>

<nav class="navbar navbar-expand-lg navbar-hust">
<div class="container-fluid">

<a class="navbar-brand d-flex align-items-center"
   href="/student_management/dashboard.php">
    <img src="/student_management/assets/images/hust_logo.png" alt="HUST">
    <span>Student Management</span>
</a>

<button class="navbar-toggler" type="button"
        data-bs-toggle="collapse"
        data-bs-target="#mainNavbar">
    <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="mainNavbar">

<ul class="navbar-nav me-auto">

<li class="nav-item"><a class="nav-link" href="/student_management/departments/index.php">Khoa</a></li>
<li class="nav-item"><a class="nav-link" href="/student_management/majors/index.php">Ngành học</a></li>
<li class="nav-item"><a class="nav-link" href="/student_management/subjects/index.php">Môn học</a></li>
<li class="nav-item"><a class="nav-link" href="/student_management/classes/index.php">Lớp học</a></li>
<li class="nav-item"><a class="nav-link" href="/student_management/lecturers/index.php">Giảng viên</a></li>
<li class="nav-item"><a class="nav-link" href="/student_management/students/index.php">Sinh viên</a></li>

</ul>

<!-- USER DROPDOWN -->
<ul class="navbar-nav ms-auto">
<li class="nav-item dropdown">

<a class="nav-link dropdown-toggle"
   href="#"
   role="button"
   data-bs-toggle="dropdown"
   aria-expanded="false">
   <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'User') ?>
</a>

<ul class="dropdown-menu dropdown-menu-end">

<?php if (($_SESSION['role'] ?? '') === 'viewer'): ?>
<li>
  <a class="dropdown-item"
     href="/student_management/students/profile.php">
     👤 Thông tin cá nhân
  </a>
</li>
<li><hr class="dropdown-divider"></li>


<li>
    <a class="dropdown-item" href="/student_management/students/timetable.php">
        📅 Thời khóa biểu
    </a>
</li>
<li><hr class="dropdown-divider"></li>


<?php endif; ?>


<li>
  <a class="dropdown-item" href="/student_management/auth/change_password.php">
    🔑 Đổi mật khẩu
  </a>
</li>

<li><hr class="dropdown-divider"></li>

<li>
  <a class="dropdown-item text-danger"
     href="/student_management/auth/logout.php">
    🚪 Đăng xuất
  </a>
</li>
</ul>

</li>
</ul>

</div>
</div>
</nav>
