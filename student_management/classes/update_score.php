<?php
require "../config/db.php";

$id = $_POST['id'] ?? null;
$qt = $_POST['qt'] ?? null;
$ck = $_POST['ck'] ?? null;

if (!$id) {
    http_response_code(400);
    exit;
}

/* Chuyển về số hợp lệ */
$qt = ($qt === '' || $qt === null) ? null : floatval($qt);
$ck = ($ck === '' || $ck === null) ? null : floatval($ck);

/* Tính điểm tổng kết nếu đủ dữ liệu
   (bạn có thể đổi tỉ lệ nếu muốn) */
$total = null;
if ($qt !== null && $ck !== null) {
    $total = round($qt * 0.4 + $ck * 0.6, 2);
}

$sql = "
UPDATE class_students
SET qt = :qt,
    ck = :ck,
    total = :total
WHERE id = :id
";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':qt'    => $qt,
    ':ck'    => $ck,
    ':total' => $total,
    ':id'    => $id
]);

// ===== CHO TOTAL = ĐIỂM MÔN HỌC =====

if ($total !== null) {
    $infoStmt = $conn->prepare("
        SELECT 
            cs.student_id,
            cs.class_id,
            s.id AS subject_id
        FROM class_students cs
        JOIN classes c 
            ON cs.class_id = c.id
        JOIN subjects s
            ON s.code = c.course_code
        WHERE cs.id = ?
    ");
    $infoStmt->execute([$id]);
    $info = $infoStmt->fetch(PDO::FETCH_ASSOC);

    if ($info) {

        // Quy đổi từ thang 10 sang thang 4
        $score4 = round(($total * 4) / 10, 2);

        $insertStmt = $conn->prepare("
            INSERT INTO student_subject_scores
                (student_id, subject_id, score, class_id)
            VALUES (?, ?, ?, ?)
        ");

        $insertStmt->execute([
            $info['student_id'],
            $info['subject_id'],
            $score4,
            $info['class_id']
        ]);
    }
}



echo $total;
