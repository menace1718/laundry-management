<?php
// Redirect to login page
header('Location: login.php');
exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Laundry Management</title>
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
        <span>Laundry Management</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbars">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link active" data-section="home" href="#"><i class="fa-solid fa-house"></i> Home</a></li>
          <li class="nav-item"><a class="nav-link" data-section="customers" href="#"><i class="fa-solid fa-users"></i> Customers</a></li>
          <li class="nav-item"><a class="nav-link" data-section="orders" href="#"><i class="fa-solid fa-shirt"></i> Orders</a></li>
          <li class="nav-item"><a class="nav-link" data-section="pricing" href="#"><i class="fa-solid fa-calculator"></i> Pricing</a></li>
          <li class="nav-item"><a class="nav-link" data-section="schedule" href="#"><i class="fa-solid fa-truck"></i> Schedule</a></li>
          <li class="nav-item"><a class="nav-link" data-section="notifications" href="#"><i class="fa-solid fa-bell"></i> Notifications</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container py-4">
    <section id="section-home" class="app-section">
      <div class="text-center py-5">
        <img src="assets/img/logo.svg" width="96" height="96" alt="Laundry Icon" />
        <h1 class="mt-3">Laundry Management</h1>
        <p class="text-muted">Purpose: Track laundry orders, customer info, and delivery schedules. Users: Laundry Staff, Customers.</p>
        <div class="row g-3 mt-4 justify-content-center">
          <div class="col-6 col-sm-4 col-md-3">
            <a class="feature-card" data-section="customers">
              <i class="fa-solid fa-users"></i>
              <span>Customers</span>
            </a>
          </div>
          <div class="col-6 col-sm-4 col-md-3">
            <a class="feature-card" data-section="orders">
              <i class="fa-solid fa-shirt"></i>
              <span>Orders</span>
            </a>
          </div>
          <div class="col-6 col-sm-4 col-md-3">
            <a class="feature-card" data-section="pricing">
              <i class="fa-solid fa-calculator"></i>
              <span>Pricing</span>
            </a>
          </div>
          <div class="col-6 col-sm-4 col-md-3">
            <a class="feature-card" data-section="schedule">
              <i class="fa-solid fa-truck"></i>
              <span>Schedule</span>
            </a>
          </div>
          <div class="col-6 col-sm-4 col-md-3">
            <a class="feature-card" data-section="notifications">
              <i class="fa-solid fa-bell"></i>
              <span>Notifications</span>
            </a>
          </div>
        </div>
      </div>
    </section>

    <section id="section-customers" class="app-section d-none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="fa-solid fa-users me-2"></i>Customers</h3>
        <button class="btn btn-primary" id="btnAddCustomer"><i class="fa-solid fa-user-plus me-1"></i>Add Customer</button>
      </div>
      <div class="table-responsive">
        <table class="table table-striped" id="tblCustomers">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Email</th>
            </tr>
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
            <tr>
              <th>#</th>
              <th>Customer</th>
              <th>Type</th>
              <th>Weight (kg)</th>
              <th>Price (₱)</th>
              <th>Status</th>
              <th>Updated</th>
              <th>Actions</th>
            </tr>
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
            <tr>
              <th>#</th>
              <th>Customer</th>
              <th>Type</th>
              <th>Slot Time</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
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
            <tr>
              <th>#</th>
              <th>To</th>
              <th>Message</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/app.js"></script>
</body>
</html>
