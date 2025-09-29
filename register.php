<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create Account - Laundry Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <link rel="icon" type="image/svg+xml" href="assets/img/logo.svg" />
  <style>
    body { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); min-height: 100vh; }
    .register-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    .form-control:focus { border-color: #28a745; box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25); }
    .btn-success { background: #28a745; border-color: #28a745; }
    .btn-success:hover { background: #218838; border-color: #1e7e34; }
  </style>
</head>
<body class="d-flex align-items-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="register-card p-4">
          <div class="text-center mb-4">
            <img src="assets/img/logo.svg" width="80" height="80" alt="Laundry Icon" />
            <h2 class="mt-3 text-success">Create Customer Account</h2>
            <p class="text-muted">Join our laundry service today!</p>
          </div>

          <div id="registerAlert" class="alert d-none" role="alert"></div>

          <form id="registerForm">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">First Name *</label>
                <input type="text" class="form-control" id="firstName" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Last Name *</label>
                <input type="text" class="form-control" id="lastName" required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Username *</label>
              <input type="text" class="form-control" id="username" required>
              <div class="form-text">Choose a unique username for login</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Email Address *</label>
              <input type="email" class="form-control" id="email" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Phone Number</label>
              <input type="tel" class="form-control" id="phone" placeholder="09123456789">
            </div>

            <div class="mb-3">
              <label class="form-label">Password *</label>
              <input type="password" class="form-control" id="password" required minlength="6">
              <div class="form-text">Minimum 6 characters</div>
            </div>

            <div class="mb-4">
              <label class="form-label">Confirm Password *</label>
              <input type="password" class="form-control" id="confirmPassword" required>
            </div>

            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="agreeTerms" required>
              <label class="form-check-label" for="agreeTerms">
                I agree to the terms and conditions
              </label>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2" id="registerBtn">
              <i class="fa-solid fa-user-plus me-2"></i>Create Account
            </button>
          </form>

          <div class="mt-4 text-center">
            <p class="mb-0">Already have an account? 
              <a href="login.php" class="text-success text-decoration-none">
                <strong>Sign In</strong>
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('registerForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const firstName = document.getElementById('firstName').value.trim();
      const lastName = document.getElementById('lastName').value.trim();
      const username = document.getElementById('username').value.trim();
      const email = document.getElementById('email').value.trim();
      const phone = document.getElementById('phone').value.trim();
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      const agreeTerms = document.getElementById('agreeTerms').checked;

      // Validation
      if (!firstName || !lastName || !username || !email || !password) {
        showAlert('Please fill in all required fields', 'danger');
        return;
      }

      if (password !== confirmPassword) {
        showAlert('Passwords do not match', 'danger');
        return;
      }

      if (password.length < 6) {
        showAlert('Password must be at least 6 characters', 'danger');
        return;
      }

      if (!agreeTerms) {
        showAlert('Please agree to the terms and conditions', 'danger');
        return;
      }

      const registerBtn = document.getElementById('registerBtn');
      registerBtn.disabled = true;
      registerBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Creating Account...';

      try {
        const response = await fetch('api/auth.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            action: 'register',
            username,
            password,
            full_name: `${firstName} ${lastName}`,
            email,
            phone
          })
        });

        const result = await response.json();

        if (result.ok) {
          showAlert('Account created successfully! Redirecting to login...', 'success');
          setTimeout(() => {
            window.location.href = 'login.php';
          }, 2000);
        } else {
          showAlert(result.message || 'Registration failed', 'danger');
        }
      } catch (error) {
        showAlert('Connection error. Please try again.', 'danger');
      }

      registerBtn.disabled = false;
      registerBtn.innerHTML = '<i class="fa-solid fa-user-plus me-2"></i>Create Account';
    });

    function showAlert(message, type) {
      const alert = document.getElementById('registerAlert');
      alert.className = `alert alert-${type}`;
      alert.textContent = message;
      alert.classList.remove('d-none');
      setTimeout(() => alert.classList.add('d-none'), 5000);
    }
  </script>
</body>
</html>
