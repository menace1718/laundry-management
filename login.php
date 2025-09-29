<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Laundry Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <link rel="icon" type="image/svg+xml" href="assets/img/logo.svg" />
  <style>
    body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
    .login-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    .role-btn { transition: all 0.3s ease; border: 2px solid transparent; }
    .role-btn.active { border-color: #0d6efd; background: #0d6efd; color: white; }
    .role-btn:hover { transform: translateY(-2px); }
  </style>
</head>
<body class="d-flex align-items-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-4">
        <div class="login-card p-4">
          <div class="text-center mb-4">
            <img src="assets/img/logo.svg" width="80" height="80" alt="Laundry Icon" />
            <h2 class="mt-3">Laundry Management</h2>
            <p class="text-muted">Please sign in to continue</p>
          </div>

          <div id="loginAlert" class="alert alert-danger d-none" role="alert"></div>

          <form id="loginForm">
            <!-- Role Selection -->
            <div class="mb-4">
              <label class="form-label">Login as:</label>
              <div class="row g-2">
                <div class="col-6">
                  <button type="button" class="btn role-btn w-100 p-3" data-role="staff">
                    <i class="fa-solid fa-user-tie d-block mb-2" style="font-size: 24px;"></i>
                    <strong>Staff</strong>
                  </button>
                </div>
                <div class="col-6">
                  <button type="button" class="btn role-btn w-100 p-3" data-role="customer">
                    <i class="fa-solid fa-user d-block mb-2" style="font-size: 24px;"></i>
                    <strong>Customer</strong>
                  </button>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" id="username" required>
            </div>

            <div class="mb-4">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" id="password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2" id="loginBtn">
              <i class="fa-solid fa-sign-in-alt me-2"></i>Sign In
            </button>
          </form>

          <div class="mt-4 text-center">
            <p class="mb-2">
              <strong>New Customer?</strong> 
              <a href="register.php" class="text-success text-decoration-none">
                <i class="fa-solid fa-user-plus me-1"></i>Create Account
              </a>
            </p>
            <small class="text-muted">
              <strong>Demo Accounts:</strong><br>
              Staff: <code>staff</code> / <code>password</code><br>
              Customer: <code>john_doe</code> / <code>password</code>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let selectedRole = '';

    // Role selection
    document.querySelectorAll('.role-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        selectedRole = btn.getAttribute('data-role');
      });
    });

    // Login form
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      
      if (!selectedRole) {
        showAlert('Please select Staff or Customer login');
        return;
      }

      const username = document.getElementById('username').value;
      const password = document.getElementById('password').value;
      const loginBtn = document.getElementById('loginBtn');

      loginBtn.disabled = true;
      loginBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Signing in...';

      try {
        const response = await fetch('api/auth.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'login', username, password, role: selectedRole })
        });

        const result = await response.json();

        if (result.ok) {
          if (result.data.role === 'staff') {
            window.location.href = 'staff-dashboard.php';
          } else {
            window.location.href = 'customer-dashboard.php';
          }
        } else {
          showAlert(result.message || 'Login failed');
        }
      } catch (error) {
        showAlert('Connection error. Please try again.');
      }

      loginBtn.disabled = false;
      loginBtn.innerHTML = '<i class="fa-solid fa-sign-in-alt me-2"></i>Sign In';
    });

    function showAlert(message) {
      const alert = document.getElementById('loginAlert');
      alert.textContent = message;
      alert.classList.remove('d-none');
      setTimeout(() => alert.classList.add('d-none'), 5000);
    }

    // Auto-select staff role by default
    document.querySelector('[data-role="staff"]').click();
  </script>
</body>
</html>
