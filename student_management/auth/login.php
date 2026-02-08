<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập hệ thống</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #b30000, #ffcc00);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .login-header {
            background-color: #b30000;
            color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            text-align: center;
            padding: 20px;
        }
        .login-header h4 {
            margin: 0;
            font-weight: bold;
        }
        .btn-login {
            background-color: #b30000;
            color: white;
            font-weight: bold;
        }
        .btn-login:hover {
            background-color: #8f0000;
            color: #ffcc00;
        }
        .form-control:focus {
            border-color: #b30000;
            box-shadow: 0 0 0 0.2rem rgba(179, 0, 0, 0.25);
        }
        .login-footer {
            text-align: center;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>



<div class="card login-card">
    <div class="login-header">
        <h4>HỆ THỐNG QUẢN LÝ SINH VIÊN</h4>
        <small>Đăng nhập để tiếp tục</small>
    </div>

    <div class="card-body p-4">
        <form action="check_login.php" method="post">
            <div class="mb-3">
                <label class="form-label">Tên đăng nhập</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-login">
                    ĐĂNG NHẬP
                </button>
            </div>
        </form>
    </div>

    <div class="card-footer login-footer">
        © <?= date('Y') ?> Student Management System
    </div>
</div>



<div class="modal fade" id="errorModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Đăng nhập thất bại</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">
          ❌ <strong>Tên đăng nhập hoặc mật khẩu không đúng.</strong><br>
          Vui lòng kiểm tra lại.
        </p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" data-bs-dismiss="modal">
          Thử lại
        </button>
      </div>
    </div>
  </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('error') === '1') {
        const errorModal = new bootstrap.Modal(
            document.getElementById('errorModal')
        );
        errorModal.show();
    }
</script>
</body>
</html>
