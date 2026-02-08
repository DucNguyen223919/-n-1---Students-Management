<?php
$host = "localhost";
$dbname = "student_management";
$username = "root";
$password = ""; // ko co pass

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Kết nối thất bại: " . $e->getMessage());
}
