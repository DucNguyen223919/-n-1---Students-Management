<?php
session_start();
require "../config/db.php";

$student_id = $_GET['id'] ?? 0;

/* ===== LẤY SINH VIÊN + NGÀNH ===== */
$stmt = $conn->prepare("
  SELECT s.*, m.name AS major_name
  FROM students s
  JOIN majors m ON s.major_id = m.id
  WHERE s.id = ?
");
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$student) die("Sinh viên không tồn tại");
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

<h2>
CHƯƠNG TRÌNH HỌC – <?= htmlspecialchars($student['full_name']) ?>
</h2>

<p>
<b>Ngành:</b> <?= htmlspecialchars($student['major_name']) ?>
</p>

<h4 class="mt-3">
GPA: <span id="gpaTotal">—</span>
</h4>

<h5 class="mt-2">
Trạng thái: <span id="completedStatus">—</span>
</h5>

<table class="table table-bordered table-hover align-middle mt-3">
<thead>
<tr>
<th>STT</th>
<th>Mã môn</th>
<th>Tên môn</th>
<th>Tín chỉ</th>
<th>Điểm</th>
<th>Lịch sử</th>
</tr>
</thead>

<tbody id="program-body">
<tr>
<td colspan="5" class="text-center text-muted">Đang tải...</td>
</tr>
</tbody>
</table>

<a href="index.php" class="btn btn-secondary">← Quay lại</a>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const studentId = <?= $student_id ?>;
const majorId   = <?= $student['major_id'] ?>;

function loadProgram() {
  fetch(`get_program.php?student_id=${studentId}&major_id=${majorId}`)
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('program-body');

      const statusEl = document.getElementById('completedStatus');
      let totalSubjects  = data.subjects.length;
      let scoredSubjects = 0;
      data.subjects.forEach(s => {
        if (s.score !== null && s.score !== '') {
          scoredSubjects++;
        }
      });

      if (totalSubjects > 0 && scoredSubjects === totalSubjects) {
        statusEl.innerText = 'Đã hoàn thành chương trình học';
        statusEl.className = 'text-success fw-bold';
      } else {
        statusEl.innerText = 'Chưa hoàn thành chương trình học';
        statusEl.className = 'text-danger fw-bold';
      }

      tbody.innerHTML = '';
      if (data.subjects.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">Chưa có môn</td></tr>`;
        return;
      }
      data.subjects.forEach((s, i) => {
        tbody.innerHTML += `
          <tr>
            <td>${i+1}</td>
            <td>${s.code}</td>
            <td>${s.name}</td>
            <td>${s.credits}</td>
            <td>
              <input type="number" step="0.1" min="0" max="10"
                class="form-control form-control-sm"
                value="${s.score ?? ''}"
                onchange="saveScore(${s.id}, this.value)">
            </td>
            <td>
              <button type="button"
                class="btn btn-sm btn-outline-secondary"
                onclick="showHistory(${s.id})">
                Lịch sử
              </button>
            </td>
          </tr>
        `;
      });
    });
}

function showHistory(subjectId) {
    fetch(`get_subject_history.php?student_id=${studentId}&subject_id=${subjectId}`)
        .then(res => res.json())
        .then(data => {
            const body = document.getElementById('historyBody');
            body.innerHTML = '';
            if (data.length === 0) {
                body.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Chưa có lịch sử.
                        </td>
                    </tr>
                `;
            } else {
                data.forEach((row, index) => {
                    body.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${row.class_code ?? ''}</td>
                            <td>${row.class_name ?? ''}</td>
                            <td>${row.score}</td>
                        </tr>
                    `;
                });
            }
            const modal = new bootstrap.Modal(
                document.getElementById('historyModal')
            );
            modal.show();
        });
}


function saveScore(subjectId, score) {
  fetch('save_score.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
      student_id: studentId,
      subject_id: subjectId,
      score: score
    })
  }).then(() => loadGpa());
}

function loadGpa() {
  fetch(`calc_gpa.php?student_id=${studentId}&major_id=${majorId}`)
    .then(res => res.json())
    .then(d => {
      document.getElementById('gpaTotal').innerText =
        d.gpa !== null ? d.gpa : '—';
    });
}

loadProgram();
loadGpa();
</script>

<!-- Modal lịch sử điểm -->
<div class="modal fade" id="historyModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Lịch sử học / thi môn học</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Lần</th>
              <th>Mã lớp</th>
              <th>Tên lớp</th>
              <th>Điểm</th>
            </tr>
          </thead>
          <tbody id="historyBody"></tbody>
        </table>

      </div>
    </div>
  </div>
</div>

</body>
</html>
