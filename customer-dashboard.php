<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
  header('Location: login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Customer Portal - Laundry Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <link href="assets/css/styles.css" rel="stylesheet" />
  <link rel="icon" type="image/svg+xml" href="assets/img/logo.svg" />
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="assets/img/logo.svg" alt="Laundry Icon" width="32" height="32" class="me-2">
        <span>Laundry Management - Customer</span>
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
          <h2><i class="fa-solid fa-tachometer-alt me-2"></i>Customer Dashboard</h2>
          <p class="text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>! Track your laundry orders below.</p>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="card bg-primary text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h6 class="card-title">My Orders</h6>
                  <h3 id="myTotalOrders">-</h3>
                </div>
                <i class="fa-solid fa-shirt fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-warning text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h6 class="card-title">In Progress</h6>
                  <h3 id="myPendingOrders">-</h3>
                </div>
                <i class="fa-solid fa-clock fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-info text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h6 class="card-title">Total Spent</h6>
                  <h3 id="myTotalSpent">₱-</h3>
                </div>
                <i class="fa-solid fa-peso-sign fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-6 col-sm-4 col-md-3">
          <a class="feature-card" data-section="orders">
            <i class="fa-solid fa-shirt"></i>
            <span>My Orders</span>
          </a>
        </div>
        <div class="col-6 col-sm-4 col-md-3">
          <a class="feature-card" data-section="schedule">
            <i class="fa-solid fa-truck"></i>
            <span>Schedule Pickup</span>
          </a>
        </div>
        <div class="col-6 col-sm-4 col-md-3">
          <a class="feature-card" data-section="notifications">
            <i class="fa-solid fa-bell"></i>
            <span>My Notifications</span>
          </a>
        </div>
        <div class="col-6 col-sm-4 col-md-3">
          <a class="feature-card" href="#" onclick="requestNewOrder()">
            <i class="fa-solid fa-plus"></i>
            <span>Request Order</span>
          </a>
        </div>
      </div>
    </section>

    <section id="section-orders" class="app-section d-none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="fa-solid fa-shirt me-2"></i>My Orders</h3>
        <button class="btn btn-success" onclick="requestNewOrder()"><i class="fa-solid fa-plus me-1"></i>Request New Order</button>
      </div>
      <div class="table-responsive">
        <table class="table table-striped" id="tblMyOrders">
          <thead>
            <tr><th>#</th><th>Type</th><th>Weight (kg)</th><th>Price (₱)</th><th>Status</th><th>Updated</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </section>

    <section id="section-schedule" class="app-section d-none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="fa-solid fa-truck me-2"></i>My Schedule</h3>
        <button class="btn btn-success" id="btnRequestSlot"><i class="fa-solid fa-plus me-1"></i>Request Pickup/Delivery</button>
      </div>
      <div class="table-responsive">
        <table class="table table-striped" id="tblMySchedule">
          <thead>
            <tr><th>#</th><th>Type</th><th>Slot Time</th><th>Status</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </section>

    <section id="section-notifications" class="app-section d-none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="fa-solid fa-bell me-2"></i>My Notifications</h3>
      </div>
      <div class="table-responsive">
        <table class="table table-striped" id="tblMyNotifications">
          <thead>
            <tr><th>#</th><th>Message</th><th>Date</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const API = {
      customers: 'api/customers.php',
      orders: 'api/orders.php',
      notifications: 'api/notifications.php',
      pricing: 'api/pricing.php',
      schedule: 'api/schedule.php'
    };

    const fmtPeso = (n) => `₱${Number(n||0).toFixed(2)}`;
    const qs = (sel) => document.querySelector(sel);

    async function apiPost(url, data={}){
      const r = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
      return r.json();
    }

    function showSection(key) {
      document.querySelectorAll('.app-section').forEach(s => s.classList.add('d-none'));
      document.getElementById(`section-${key}`).classList.remove('d-none');
      document.querySelectorAll('[data-section]').forEach(a => a.classList.remove('active'));
      document.querySelectorAll(`[data-section="${key}"]`).forEach(a => a.classList.add('active'));
    }

    document.addEventListener('click', (e) => {
      const a = e.target.closest('[data-section]');
      if (a && a.tagName !== 'SELECT' && a.tagName !== 'BUTTON') {
        e.preventDefault();
        showSection(a.getAttribute('data-section'));
      }
    });

    // Customer-specific functions
    async function loadMyOrders() {
      const res = await apiPost(API.orders, { action: 'list_by_customer', customer_name: '<?php echo $_SESSION['full_name']; ?>' });
      const tbody = qs('#tblMyOrders tbody');
      tbody.innerHTML = '';
      (res.data || []).forEach((o,i)=>{
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${i+1}</td>
          <td>${o.type}</td>
          <td>${o.weight||''}</td>
          <td>${fmtPeso(o.price)}</td>
          <td><span class="badge bg-secondary">${o.status}</span></td>
          <td>${o.updated_at}</td>`;
        tbody.appendChild(tr);
      });
    }

    async function loadMySchedule() {
      const res = await apiPost(API.schedule, { action: 'list_by_customer', customer_name: '<?php echo $_SESSION['full_name']; ?>' });
      const tbody = qs('#tblMySchedule tbody');
      tbody.innerHTML = '';
      (res.data || []).forEach((s,i)=>{
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${i+1}</td>
          <td>${s.type}</td>
          <td>${s.slot_time}</td>
          <td><span class="badge bg-secondary">${s.status}</span></td>`;
        tbody.appendChild(tr);
      });
    }

    async function loadMyNotifications() {
      const res = await apiPost(API.notifications, { action: 'list_by_customer', to_name: '<?php echo $_SESSION['full_name']; ?>' });
      const tbody = qs('#tblMyNotifications tbody');
      tbody.innerHTML = '';
      (res.data || []).forEach((n,i)=>{
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${i+1}</td><td>${n.message}</td><td>${n.created_at}</td>`;
        tbody.appendChild(tr);
      });
    }

    async function loadDashboardStats() {
      try {
        const ordersRes = await apiPost(API.orders, { action: 'list_by_customer', customer_name: '<?php echo $_SESSION['full_name']; ?>' });
        const orders = ordersRes.data || [];
        
        document.getElementById('myTotalOrders').textContent = orders.length;
        document.getElementById('myPendingOrders').textContent = orders.filter(o => o.status !== 'delivered').length;
        
        const totalSpent = orders.reduce((sum, o) => sum + parseFloat(o.price || 0), 0);
        document.getElementById('myTotalSpent').textContent = `₱${totalSpent.toFixed(2)}`;
      } catch (error) {
        console.error('Failed to load dashboard stats:', error);
      }
    }

    function requestNewOrder() {
      alert('Please contact staff to place a new order. You can call or visit our store.');
    }

    document.getElementById('btnRequestSlot').addEventListener('click', () => {
      const type = prompt('Type: pickup or delivery');
      if (!type) return;
      const slot_time = prompt('Preferred time (YYYY-MM-DD HH:MM)');
      if (!slot_time) return;
      
      apiPost(API.schedule, { 
        action: 'create_slot', 
        customer_name: '<?php echo $_SESSION['full_name']; ?>', 
        type, 
        slot_time 
      }).then(() => {
        alert('Schedule request submitted!');
        loadMySchedule();
      });
    });

    function onSectionShown(key) {
      if (key === 'home') loadDashboardStats();
      if (key === 'orders') loadMyOrders();
      if (key === 'schedule') loadMySchedule();
      if (key === 'notifications') loadMyNotifications();
    }

    const observer = new MutationObserver(()=>{
      ['home','orders','schedule','notifications'].forEach(k=>{
        const visible = !document.getElementById(`section-${k}`).classList.contains('d-none');
        if(visible) onSectionShown(k);
      });
    });
    observer.observe(document.body, { attributes:true, subtree:true, attributeFilter:['class'] });

    async function logout() {
      await fetch('api/auth.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'logout' })
      });
      window.location.href = 'login.php';
    }

    showSection('home');
    loadDashboardStats();
  </script>
</body>
</html>
