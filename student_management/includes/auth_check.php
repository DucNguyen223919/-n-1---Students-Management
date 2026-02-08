<?php
/*************************************************
 * auth_check.php
 * Chức năng:
 * - Kiểm tra người dùng đã đăng nhập hay chưa
 * - Nếu chưa → chuyển về trang login
 *************************************************/

if (!isset($_SESSION['username'])) {
    header("Location: /student_management/auth/login.php");
    exit;
}
