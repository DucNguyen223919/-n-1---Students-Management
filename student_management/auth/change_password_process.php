<?php
/****************************************************
 * FILE: change_password_process.php
 * CHỨC NĂNG:
 * - Xử lý đổi mật khẩu cho ú
 ****************************************************/

/* ===== BẮT ĐẦU SESSION =====
 * Session dùng để lưu thông tin đăng nhập
 * */
session_start();

/* ===== KẾT NỐI DATABASE =====
 * File db.php chứa thông tin kết nối MySQL
 */
require __DIR__ . '/../config/db.php';

/* ===== KIỂM TRA ĐÃ ĐĂNG NHẬP CHƯA =====
 * Nếu chưa đăng nhập thì không cho đổi mật khẩu
 */
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

/* ===== LẤY DỮ LIỆU TỪ FORM =====
 * $_POST là dữ liệu gửi từ form đổi mật khẩu
 */
$username = $_SESSION['username'];          // tên đăng nhập hiện tại
$old      = $_POST['old_password'];          // mật khẩu cũ
$new      = $_POST['new_password'];          // mật khẩu mới
$confirm  = $_POST['confirm_password'];      // xác nhận mật khẩu mới

/* =================================================
 * PHẦN 1: KIỂM TRA DỮ LIỆU NGƯỜI DÙNG NHẬP
 * ================================================= */

/* --- Kiểm tra mật khẩu mới có trùng nhau không --- */
if ($new !== $confirm) {
    header("Location: change_password.php?error=Mật khẩu mới không khớp");
    exit;
}

/* --- Kiểm tra độ dài mật khẩu mới --- */
if (strlen($new) < 6) {
    header("Location: change_password.php?error=Mật khẩu phải ít nhất 6 ký tự");
    exit;
}

/* =================================================
 * PHẦN 2: KIỂM TRA MẬT KHẨU CŨ
 * ================================================= */

/* --- Lấy mật khẩu đã mã hóa trong database --- */
$stmt = $conn->prepare(
    "SELECT password FROM users WHERE username = ?"
);
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

/* --- So sánh mật khẩu cũ người dùng nhập --- */
if (!$user || !password_verify($old, $user['password'])) {
    header("Location: change_password.php?error=Mật khẩu cũ không đúng");
    exit;
}

/* =================================================
 * PHẦN 3: CẬP NHẬT MẬT KHẨU MỚI
 *================================================= */

/* --- Mã hóa mật khẩu mới --- 
 * password_hash giúp bảo mật, không lưu mật khẩu dạng chữ
 */
$newHash = password_hash($new, PASSWORD_DEFAULT);

/* --- Cập nhật vào database --- */
$stmt = $conn->prepare(
    "UPDATE users SET password = ? WHERE username = ?"
);
$stmt->execute([$newHash, $username]);
header("Location: change_password.php?success=1");
exit;
