<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
  header('Location: login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Staff Dashboard - Laundry Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <link href="assets/css/styles.css" rel="stylesheet" />
  <link rel="icon" type="image/svg+xml" href="assets/img/logo.svg" />
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="assets/img/logo.svg" alt="Laundry Icon" width="32" height="32" class="me-2">
        <span>Laundry Management - Staff</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbars">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" data-section="home" href="#" title="Home"><i class="fa-solid fa-house"></i></a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($_SESSION['full_name']); ?>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#" onclick="logout()"><i class="fa-solid fa-sign-out-alt me-2"></i>Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container py-4">
    <section id="section-home" class="app-section">
      <div class="row mb-4">
        <div class="col">
          <h2><i class="fa-solid fa-tachometer-alt me-2"></i>Staff Dashboard</h2>
          <p class="text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>! Manage laundry operations below.</p>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="card bg-primary text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h6 class="card-title">Total Orders</h6>
                  <h3 id="totalOrders">-</h3>
                </div>
                <i class="fa-solid fa-shirt fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-warning text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h6 class="card-title">Pending Orders</h6>
                  <h3 id="pendingOrders">-</h3>
                </div>
                <i class="fa-solid fa-clock fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-success text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h6 class="card-title">Completed</h6>
                  <h3 id="completedOrders">-</h3>
                </div>
                <i class="fa-solid fa-check-circle fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-info text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h6 class="card-title">Total Revenue</h6>
                  <h3 id="totalRevenue">₱-</h3>
                </div>
                <i class="fa-solid fa-peso-sign fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-6 col-sm-4 col-md-3">
          <a class="feature-card" data-section="customers">
            <i class="fa-solid fa-users"></i>
            <span>Manage Customers</span>
          </a>
        </div>
        <div class="col-6 col-sm-4 col-md-3">
          <a class="feature-card" data-section="orders">
            <i class="fa-solid fa-shirt"></i>
            <span>Order Management</span>
          </a>
        </div>
        <div class="col-6 col-sm-4 col-md-3">
          <a class="feature-card" data-section="pricing">
            <i class="fa-solid fa-calculator"></i>
            <span>Pricing Config</span>
          </a>
        </div>
        <div class="col-6 col-sm-4 col-md-3">
          <a class="feature-card" data-section="schedule">
            <i class="fa-solid fa-truck"></i>
            <span>Schedule Management</span>
          </a>
        </div>
        <div class="col-6 col-sm-4 col-md-3">
          <a class="feature-card" data-section="notifications">
            <i class="fa-solid fa-bell"></i>
            <span>Send Notifications</span>
          </a>
        </div>
        <div class="col-6 col-sm-4 col-md-3">
          <a class="feature-card" data-section="accounts">
            <i class="fa-solid fa-user-cog"></i>
            <span>Manage Accounts</span>
          </a>
        </div>
      </div>
    </section>

    <!-- Include all sections from original index.php -->
    <section id="section-customers" class="app-section d-none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="fa-solid fa-users me-2"></i>Customers</h3>
        <button class="btn btn-primary" id="btnAddCustomer"><i class="fa-solid fa-user-plus me-1"></i>Add Customer</button>
      </div>
      <div class="table-responsive">
        <table class="table table-striped" id="tblCustomers">
          <thead>
            <tr><th>#</th><th>Name</th><th>Phone</th><th>Email</th><th>Actions</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </section>

    <section id="section-orders" class="app-section d-none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="fa-solid fa-shirt me-2"></i>Orders</h3>
        <button class="btn btn-primary" id="btnAddOrder"><i class="fa-solid fa-plus me-1"></i>New Order</button>
      </div>
      <div class="row g-3 mb-3">
        <div class="col-sm-6 col-md-4">
          <label class="form-label">Filter by Status</label>
          <select id="filterStatus" class="form-select">
            <option value="">All</option>
            <option value="received">Received</option>
            <option value="washing">Washing</option>
            <option value="ready">Ready</option>
            <option value="delivered">Delivered</option>
          </select>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped" id="tblOrders">
          <thead>
            <tr><th>#</th><th>Customer</th><th>Type</th><th>Weight (kg)</th><th>Price (₱)</th><th>Status</th><th>Updated</th><th>Actions</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </section>

    <section id="section-pricing" class="app-section d-none">
      <h3><i class="fa-solid fa-calculator me-2"></i>Pricing</h3>
      <div class="row g-3">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Editable Pricing Config (₱)</h5>
              <div class="mb-3">
                <label class="form-label">Per Kg (₱)</label>
                <input type="number" step="0.01" class="form-control" id="pricePerKg" />
              </div>
              <div class="mb-3">
                <label class="form-label">Per Shirt (₱)</label>
                <input type="number" step="0.01" class="form-control" id="pricePerShirt" />
              </div>
              <div class="mb-3">
                <label class="form-label">Per Pants (₱)</label>
                <input type="number" step="0.01" class="form-control" id="pricePerPants" />
              </div>
              <button class="btn btn-success" id="btnSavePricing"><i class="fa-solid fa-floppy-disk me-1"></i>Save</button>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Quick Calculator</h5>
              <div class="row g-2 align-items-end">
                <div class="col-6">
                  <label class="form-label">By Weight (kg)</label>
                  <input type="number" step="0.01" class="form-control" id="calcWeight" />
                </div>
                <div class="col-6">
                  <button class="btn btn-outline-primary w-100" id="btnCalcWeight">Calculate</button>
                </div>
              </div>
              <hr />
              <div class="row g-2 align-items-end">
                <div class="col-6">
                  <label class="form-label">Shirts (qty)</label>
                  <input type="number" class="form-control" id="calcShirts" />
                </div>
                <div class="col-6">
                  <label class="form-label">Pants (qty)</label>
                  <input type="number" class="form-control" id="calcPants" />
                </div>
              </div>
              <div class="mt-3">
                <button class="btn btn-outline-primary" id="btnCalcType">Calculate</button>
              </div>
              <div class="mt-3"><strong>Estimated Total:</strong> <span id="calcTotal">₱0.00</span></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="section-schedule" class="app-section d-none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="fa-solid fa-truck me-2"></i>Pickup & Delivery Schedule</h3>
        <button class="btn btn-primary" id="btnAddSlot"><i class="fa-solid fa-plus me-1"></i>New Slot</button>
      </div>
      <div class="table-responsive">
        <table class="table table-striped" id="tblSchedule">
          <thead>
            <tr><th>#</th><th>Customer</th><th>Type</th><th>Slot Time</th><th>Status</th><th>Actions</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </section>

    <section id="section-notifications" class="app-section d-none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="fa-solid fa-bell me-2"></i>Notifications</h3>
        <button class="btn btn-primary" id="btnSendNotif"><i class="fa-solid fa-paper-plane me-1"></i>Send</button>
      </div>
      <div class="table-responsive">
        <table class="table table-striped" id="tblNotifications">
          <thead>
            <tr><th>#</th><th>To</th><th>Message</th><th>Date</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </section>

    <section id="section-accounts" class="app-section d-none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="fa-solid fa-user-cog me-2"></i>Manage Accounts</h3>
        <div>
          <button class="btn btn-success me-2" id="btnAddStaff"><i class="fa-solid fa-user-plus me-1"></i>Add Staff</button>
          <button class="btn btn-outline-secondary" onclick="loadAccounts()"><i class="fa-solid fa-refresh me-1"></i>Refresh</button>
        </div>
      </div>
      
      <div class="row g-3 mb-3">
        <div class="col-sm-6 col-md-4">
          <label class="form-label">Filter by Role</label>
          <select id="filterRole" class="form-select">
            <option value="">All Users</option>
            <option value="staff">Staff Only</option>
            <option value="customer">Customers Only</option>
          </select>
        </div>
        <div class="col-sm-6 col-md-4">
          <label class="form-label">Filter by Status</label>
          <select id="filterStatus" class="form-select">
            <option value="">All Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-striped" id="tblAccounts">
          <thead>
            <tr>
              <th>#</th>
              <th>Username</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </section>
  </main>

  <!-- Account Management Modals -->
  <div class="modal fade" id="editAccountModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Account</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="editAccountForm">
            <input type="hidden" id="editUserId">
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" id="editFullName" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" id="editEmail" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input type="tel" class="form-control" id="editPhone">
            </div>
            <div class="mb-3">
              <label class="form-label">Role</label>
              <select class="form-select" id="editRole" required>
                <option value="customer">Customer</option>
                <option value="staff">Staff</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="saveAccountChanges()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addStaffModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Staff Account</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="addStaffForm">
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" id="newUsername" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" id="newPassword" required minlength="6">
            </div>
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" id="newFullName" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" id="newEmail" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input type="tel" class="form-control" id="newPhone">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-success" onclick="createStaffAccount()">Create Account</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Reset Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="resetPasswordForm">
            <input type="hidden" id="resetUserId">
            <p>Reset password for: <strong id="resetUserName"></strong></p>
            <div class="mb-3">
              <label class="form-label">New Password</label>
              <input type="password" class="form-control" id="resetNewPassword" required minlength="6">
            </div>
            <div class="mb-3">
              <label class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="resetConfirmPassword" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-warning" onclick="resetUserPassword()">Reset Password</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/app.js"></script>
  <script>
    // Dashboard stats
    async function loadDashboardStats() {
      try {
        const ordersRes = await apiPost('api/orders.php', { action: 'list' });
        const orders = ordersRes.data || [];
        
        document.getElementById('totalOrders').textContent = orders.length;
        document.getElementById('pendingOrders').textContent = orders.filter(o => o.status !== 'delivered').length;
        document.getElementById('completedOrders').textContent = orders.filter(o => o.status === 'delivered').length;
        
        const totalRevenue = orders.reduce((sum, o) => sum + parseFloat(o.price || 0), 0);
        document.getElementById('totalRevenue').textContent = `₱${totalRevenue.toFixed(2)}`;
      } catch (error) {
        console.error('Failed to load dashboard stats:', error);
      }
    }

    // Load stats when dashboard is shown
    function onSectionShown(key) {
      if (key === 'home') loadDashboardStats();
      if (key === 'customers') loadCustomers();
      if (key === 'orders') loadOrders();
      if (key === 'pricing') loadPricing();
      if (key === 'schedule') loadSchedule();
      if (key === 'notifications') loadNotifications();
      if (key === 'accounts') loadAccounts();
    }

    // Account Management Functions
    async function loadAccounts() {
      try {
        const res = await apiPost('api/accounts.php', { action: 'list_all' });
        const tbody = document.querySelector('#tblAccounts tbody');
        tbody.innerHTML = '';
        
        const roleFilter = document.getElementById('filterRole').value;
        const statusFilter = document.getElementById('filterStatus').value;
        
        let filteredData = res.data || [];
        if (roleFilter) filteredData = filteredData.filter(u => u.role === roleFilter);
        if (statusFilter !== '') filteredData = filteredData.filter(u => u.is_active == statusFilter);
        
        filteredData.forEach((user, i) => {
          const tr = document.createElement('tr');
          const statusBadge = user.is_active == '1' ? 
            '<span class="badge bg-success">Active</span>' : 
            '<span class="badge bg-danger">Inactive</span>';
          const roleBadge = user.role === 'staff' ? 
            '<span class="badge bg-primary">Staff</span>' : 
            '<span class="badge bg-info">Customer</span>';
          
          tr.innerHTML = `
            <td>${i+1}</td>
            <td><strong>${user.username}</strong></td>
            <td>${user.full_name}</td>
            <td>${user.email}</td>
            <td>${roleBadge}</td>
            <td>${statusBadge}</td>
            <td>${new Date(user.created_at).toLocaleDateString()}</td>
            <td>
              <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary" onclick="editAccount(${user.id})" title="Edit">
                  <i class="fa-solid fa-edit"></i>
                </button>
                <button class="btn btn-outline-warning" onclick="resetPassword(${user.id}, '${user.full_name}')" title="Reset Password">
                  <i class="fa-solid fa-key"></i>
                </button>
                <button class="btn btn-outline-${user.is_active == '1' ? 'danger' : 'success'}" 
                        onclick="toggleAccountStatus(${user.id})" 
                        title="${user.is_active == '1' ? 'Deactivate' : 'Activate'}">
                  <i class="fa-solid fa-${user.is_active == '1' ? 'ban' : 'check'}"></i>
                </button>
                ${user.id != <?php echo $_SESSION['user_id']; ?> ? 
                  `<button class="btn btn-outline-danger" onclick="deleteAccount(${user.id}, '${user.full_name}')" title="Delete">
                    <i class="fa-solid fa-trash"></i>
                  </button>` : ''}
              </div>
            </td>
          `;
          tbody.appendChild(tr);
        });
      } catch (error) {
        console.error('Failed to load accounts:', error);
      }
    }

    async function editAccount(userId) {
      try {
        const res = await apiPost('api/accounts.php', { action: 'get_user', id: userId });
        const user = res.data;
        
        document.getElementById('editUserId').value = user.id;
        document.getElementById('editFullName').value = user.full_name;
        document.getElementById('editEmail').value = user.email;
        document.getElementById('editPhone').value = user.phone || '';
        document.getElementById('editRole').value = user.role;
        
        new bootstrap.Modal(document.getElementById('editAccountModal')).show();
      } catch (error) {
        alert('Failed to load user data');
      }
    }

    async function saveAccountChanges() {
      const userId = document.getElementById('editUserId').value;
      const fullName = document.getElementById('editFullName').value;
      const email = document.getElementById('editEmail').value;
      const phone = document.getElementById('editPhone').value;
      const role = document.getElementById('editRole').value;
      
      try {
        await apiPost('api/accounts.php', {
          action: 'update_user',
          id: userId,
          full_name: fullName,
          email: email,
          phone: phone,
          role: role
        });
        
        bootstrap.Modal.getInstance(document.getElementById('editAccountModal')).hide();
        loadAccounts();
        alert('Account updated successfully');
      } catch (error) {
        alert('Failed to update account');
      }
    }

    async function toggleAccountStatus(userId) {
      if (confirm('Are you sure you want to change this account status?')) {
        try {
          await apiPost('api/accounts.php', { action: 'toggle_status', id: userId });
          loadAccounts();
          alert('Account status updated');
        } catch (error) {
          alert('Failed to update status');
        }
      }
    }

    function resetPassword(userId, userName) {
      document.getElementById('resetUserId').value = userId;
      document.getElementById('resetUserName').textContent = userName;
      document.getElementById('resetNewPassword').value = '';
      document.getElementById('resetConfirmPassword').value = '';
      new bootstrap.Modal(document.getElementById('resetPasswordModal')).show();
    }

    async function resetUserPassword() {
      const userId = document.getElementById('resetUserId').value;
      const newPassword = document.getElementById('resetNewPassword').value;
      const confirmPassword = document.getElementById('resetConfirmPassword').value;
      
      if (newPassword !== confirmPassword) {
        alert('Passwords do not match');
        return;
      }
      
      if (newPassword.length < 6) {
        alert('Password must be at least 6 characters');
        return;
      }
      
      try {
        await apiPost('api/accounts.php', {
          action: 'reset_password',
          id: userId,
          new_password: newPassword
        });
        
        bootstrap.Modal.getInstance(document.getElementById('resetPasswordModal')).hide();
        alert('Password reset successfully');
      } catch (error) {
        alert('Failed to reset password');
      }
    }

    async function createStaffAccount() {
      const username = document.getElementById('newUsername').value;
      const password = document.getElementById('newPassword').value;
      const fullName = document.getElementById('newFullName').value;
      const email = document.getElementById('newEmail').value;
      const phone = document.getElementById('newPhone').value;
      
      try {
        await apiPost('api/accounts.php', {
          action: 'create_staff',
          username: username,
          password: password,
          full_name: fullName,
          email: email,
          phone: phone
        });
        
        bootstrap.Modal.getInstance(document.getElementById('addStaffModal')).hide();
        document.getElementById('addStaffForm').reset();
        loadAccounts();
        alert('Staff account created successfully');
      } catch (error) {
        alert('Failed to create staff account: ' + (error.message || 'Unknown error'));
      }
    }

    async function deleteAccount(userId, userName) {
      if (confirm(`Are you sure you want to delete the account for "${userName}"? This action cannot be undone.`)) {
        try {
          await apiPost('api/accounts.php', { action: 'delete_user', id: userId });
          loadAccounts();
          alert('Account deleted successfully');
        } catch (error) {
          alert('Failed to delete account');
        }
      }
    }

    // Event listeners for account management
    document.getElementById('btnAddStaff').addEventListener('click', () => {
      document.getElementById('addStaffForm').reset();
      new bootstrap.Modal(document.getElementById('addStaffModal')).show();
    });

    document.getElementById('filterRole').addEventListener('change', loadAccounts);
    document.getElementById('filterStatus').addEventListener('change', loadAccounts);

    // Logout function
    async function logout() {
      await fetch('api/auth.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'logout' })
      });
      window.location.href = 'login.php';
    }

    // Load initial stats
    loadDashboardStats();
  </script>
</body>
</html>
