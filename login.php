<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BPCC POS System | Log in</title>
  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="../pos/assets/plugins/bootstrap-5/bootstrap.min.css"> 
  <script src="../pos/assets/plugins/bootstrap-5/bootstrap.bundle.min.js"></script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../pos/assets/plugins/fontawesome-free/css/all.min.css">

  <style>
    body {
      min-height: 100vh;
      margin: 0;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background: radial-gradient(circle at top left, #0d6efd 0, #0d6efd 8%, transparent 10%),
                  radial-gradient(circle at bottom right, #198754 0, #198754 8%, transparent 10%),
                  #f4f6f9;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .auth-wrapper {
      width: 100%;
      max-width: 420px;
      padding: 1.5rem;
    }

    .auth-card {
      border-radius: 1rem;
      border: 1px solid rgba(0,0,0,0.05);
      box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
      overflow: hidden;
      background: #ffffff;
    }

    .auth-card-header {
      padding: 1.75rem 2rem 1.25rem;
      text-align: center;
      border-bottom: 1px solid rgba(0,0,0,0.04);
      background: linear-gradient(135deg, #0d6efd 0%, #0ab5d5 50%, #0d6efd 100%);
      color: #ffffff;
    }

    .auth-logo {
      max-width: 180px;
      margin-bottom: 0.75rem;
      filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
    }

    .auth-title {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
    }

    .auth-subtitle {
      font-size: 0.85rem;
      opacity: 0.9;
    }

    .auth-card-body {
      padding: 1.75rem 2rem 1.75rem;
    }

    .form-label {
      font-size: 0.85rem;
      font-weight: 500;
      color: #495057;
    }

    .input-group-text {
      border-radius: 0.75rem 0 0 0.75rem;
      background: #f1f5f9;
      border-right: 0;
    }

    .form-control {
      border-radius: 0 0.75rem 0.75rem 0;
      border-left: 0;
      font-size: 0.9rem;
      padding-top: 0.55rem;
      padding-bottom: 0.55rem;
    }

    .form-control:focus {
      box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.15);
    }

    .auth-footer {
      padding: 0 2rem 1.5rem;
      font-size: 0.8rem;
      color: #6c757d;
      text-align: center;
    }

    .btn-login {
      border-radius: 0.75rem;
      padding: 0.6rem 1rem;
      font-weight: 600;
      letter-spacing: 0.02em;
    }
  </style>
</head>

<body>
  <div class="auth-wrapper">
    <div class="auth-card">
      <div class="auth-card-header">
        <img src="bpcc_logo.png" alt="BPCC Logo" class="auth-logo">
        <div class="auth-title">เข้าสู่ระบบ BPCC POS</div>
        <div class="auth-subtitle">กรุณากรอกชื่อผู้ใช้และรหัสผ่านของคุณ</div>
      </div>
      <div class="auth-card-body">
        <form action="chk_login.php" method="post" autocomplete="off">
          <div class="mb-3">
            <label for="mem_username" class="form-label">ชื่อผู้ใช้</label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="fas fa-user text-primary"></i>
              </span>
              <input 
                type="text" 
                class="form-control" 
                id="mem_username" 
                name="mem_username" 
                placeholder="กรอกชื่อผู้ใช้"
                required
              >
            </div>
          </div>
          <div class="mb-3">
            <label for="mem_password" class="form-label">รหัสผ่าน</label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="fas fa-lock text-primary"></i>
              </span>
              <input 
                type="password" 
                class="form-control" 
                id="mem_password" 
                name="mem_password" 
                placeholder="กรอกรหัสผ่าน"
                required
              >
            </div>
          </div>
          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary btn-login">
              เข้าสู่ระบบ
            </button>
          </div>
        </form>
      </div>
      <div class="auth-footer">
        BPCC POS System &copy; <?php echo date('Y'); ?> <br>Designed by Aj.Dong (091-8390785)
      </div>
    </div>
  </div>
</body>
</html>