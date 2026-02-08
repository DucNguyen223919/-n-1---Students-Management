<?php
// Bắt đầu session (bắt buộc để dùng $_SESSION)
session_start();
require __DIR__ . '/../includes/auth_check.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
// Nếu chưa có biến $_SESSION['username']
// => chưa đăng nhập
if (!isset($_SESSION['username'])) {

    // Chuyển hướng người dùng về trang đăng nhập
    header("Location: login.php");

    // Dừng toàn bộ xử lý phía dưới
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <!-- Khai báo bảng mã tiếng Việt -->
    <meta charset="UTF-8">

    <!-- Tiêu đề hiển thị trên tab trình duyệt -->
    <title>Đổi mật khẩu</title>

    <!-- Nhúng thư viện Bootstrap để dùng giao diện có sẵn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php
// Nhúng file navbar (thanh menu)
// __DIR__ là thư mục hiện tại của file này
include __DIR__ . "/../includes/navbar.php";
?>

<!-- Khung chính, giới hạn chiều rộng tối đa 500px -->
<div class="container mt-5" style="max-width: 500px;">

    <!-- Tiêu đề trang -->
    <h3 class="mb-3 text-center">ĐỔI MẬT KHẨU</h3>

    <?php
    // Nếu trên URL có ?error=...
    // => hiển thị thông báo lỗi
    if (isset($_GET['error'])):
    ?>
        <div class="alert alert-danger">
            <!-- htmlspecialchars để tránh lỗi bảo mật XSS -->
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <?php
    // Nếu trên URL có ?success
    // => hiển thị thông báo thành công
    if (isset($_GET['success'])):
    ?>
        <div class="alert alert-success">
            Đổi mật khẩu thành công!
        </div>
    <?php endif; ?>

    <!-- Form gửi dữ liệu bằng phương thức POST -->
    <!-- action trỏ tới file xử lý đổi mật khẩu -->
    <form method="post" action="change_password_process.php">

        <!-- Ô nhập mật khẩu cũ -->
        <div class="mb-3">
            <label class="form-label">Mật khẩu cũ</label>
            <input type="password" name="old_password" class="form-control" required>
        </div>

        <!-- Ô nhập mật khẩu mới -->
        <div class="mb-3">
            <label class="form-label">Mật khẩu mới</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>

        <!-- Ô nhập lại mật khẩu mới -->
        <div class="mb-3">
            <label class="form-label">Nhập lại mật khẩu mới</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <!-- Nút submit form -->
        <button class="btn btn-primary w-100">
            Đổi mật khẩu
        </button>

    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
