<?php
/*************************************************
 * sort.php
 * Xử lý sắp xếp danh sách sinh viên
 *************************************************/

// Các cột được phép sắp xếp
$allowedSort = [
    'student_code' => 's.student_code',
    'full_name'    => 's.full_name',
    'major_class'  => 's.major_class'
];

// Lấy tham số từ URL
$sort  = $_GET['sort']  ?? 'student_code';
$order = $_GET['order'] ?? 'asc';

// Kiểm tra hợp lệ
$sortColumn = $allowedSort[$sort] ?? 's.student_code';
$order      = ($order === 'desc') ? 'DESC' : 'ASC';

// Chuỗi ORDER BY để index.php dùng
$orderBy = " ORDER BY $sortColumn $order ";
