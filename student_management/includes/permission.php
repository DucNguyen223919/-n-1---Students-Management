<?php
if (!isset($_SESSION['role'])) {
    header("Location: /student_management/auth/login.php");
    exit;
}

function requireRole(array $roles)
{
    if (!in_array($_SESSION['role'], $roles)) {
        die("❌ Bạn không có quyền truy cập chức năng này");
    }
}
