const API = {
  customers: 'api/customers.php',
  orders: 'api/orders.php',
  notifications: 'api/notifications.php',
  pricing: 'api/pricing.php',
  schedule: 'api/schedule.php'
};

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

// Helpers
const fmtPeso = (n) => `₱${Number(n||0).toFixed(2)}`;
const qs = (sel) => document.querySelector(sel);
const qsa = (sel) => Array.from(document.querySelectorAll(sel));

async function apiPost(url, data={}){
  const r = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
  return r.json();
}

// Customers
async function loadCustomers(){
  const res = await apiPost(API.customers, { action: 'list' });
  const tbody = qs('#tblCustomers tbody');
  tbody.innerHTML = '';
  res.data.forEach((c,i)=>{
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${i+1}</td>
      <td>${c.name}</td>
      <td>${c.phone||''}</td>
      <td>${c.email||''}</td>
      <td>
        <button class="btn btn-sm btn-outline-danger" onclick="deleteCustomer(${c.id}, '${c.name}')" title="Delete Customer">
          <i class="fa-solid fa-trash"></i>
        </button>
      </td>
    `;
    tbody.appendChild(tr);
  });
}

document.getElementById('btnAddCustomer').addEventListener('click', async ()=>{
  const name = prompt('Customer name'); if(!name) return;
  const phone = prompt('Phone (optional)')||'';
  const email = prompt('Email (optional)')||'';
  await apiPost(API.customers, { action:'create', name, phone, email });
  loadCustomers();
});

// Delete customer function
async function deleteCustomer(customerId, customerName) {
  if (confirm(`Are you sure you want to delete customer "${customerName}"?\n\nNote: Customers with existing orders cannot be deleted.`)) {
    try {
      await apiPost(API.customers, { action: 'delete', id: customerId });
      alert('Customer deleted successfully');
      loadCustomers();
    } catch (error) {
      alert('Failed to delete customer: ' + (error.message || 'Unknown error'));
    }
  }
}

// Orders
async function loadOrders(){
  const status = qs('#filterStatus').value;
  const res = await apiPost(API.orders, { action: 'list', status });
  const tbody = qs('#tblOrders tbody');
  tbody.innerHTML = '';
  res.data.forEach((o,i)=>{
    const tr = document.createElement('tr');
    tr.innerHTML = `<td>${i+1}</td>
      <td>${o.customer_name}</td>
      <td>${o.type}</td>
      <td>${o.weight||''}</td>
      <td>${fmtPeso(o.price)}</td>
      <td><span class="badge bg-secondary">${o.status}</span></td>
      <td>${o.updated_at}</td>
      <td>
        <div class="btn-group btn-group-sm">
          <button class="btn btn-outline-secondary" data-act="status" data-id="${o.id}">Update Status</button>
        </div>
      </td>`;
    tbody.appendChild(tr);
  });
}

qs('#filterStatus').addEventListener('change', loadOrders);

document.getElementById('btnAddOrder').addEventListener('click', async ()=>{
  const customer_name = prompt('Customer name (new or existing)'); if(!customer_name) return;
  const type = prompt('Type (e.g., shirt/pants/mix)'); if(!type) return;
  const weight = parseFloat(prompt('Weight (kg, optional)')||'0')||0;
  // auto price
  const priceRes = await apiPost(API.pricing, { action: 'calculate', by: 'weight', weight });
  const price = priceRes.data.total || 0;
  await apiPost(API.orders, { action: 'create', customer_name, type, weight, price });
  loadOrders();
});

document.addEventListener('click', async (e)=>{
  const btn = e.target.closest('button[data-act="status"]');
  if(!btn) return;
  const id = btn.getAttribute('data-id');
  
  // Create a better status selection dialog
  const statuses = ['received', 'washing', 'ready', 'delivered'];
  const statusOptions = statuses.map(s => `${s.charAt(0).toUpperCase() + s.slice(1)}`).join('\n');
  const status = prompt(`Select new status:\n\n1. Received\n2. Washing\n3. Ready\n4. Delivered\n\nEnter status name:`);
  
  if(!status) return;
  
  const normalizedStatus = status.toLowerCase().trim();
  if(!statuses.includes(normalizedStatus)) {
    alert('Invalid status. Please use: received, washing, ready, or delivered');
    return;
  }
  
  try {
    await apiPost(API.orders, { action:'update_status', id, status: normalizedStatus });
    alert(`Order status updated to "${normalizedStatus}"`);
    loadOrders();
  } catch (error) {
    alert('Failed to update order status');
    console.error('Update status error:', error);
  }
});

// Pricing
async function loadPricing(){
  const res = await apiPost(API.pricing, { action: 'get_config' });
  qs('#pricePerKg').value = res.data.per_kg;
  qs('#pricePerShirt').value = res.data.per_shirt;
  qs('#pricePerPants').value = res.data.per_pants;
}

qs('#btnSavePricing').addEventListener('click', async ()=>{
  try {
    const per_kg = parseFloat(qs('#pricePerKg').value||'0');
    const per_shirt = parseFloat(qs('#pricePerShirt').value||'0');
    const per_pants = parseFloat(qs('#pricePerPants').value||'0');
    await apiPost(API.pricing, { action: 'set_config', per_kg, per_shirt, per_pants });
    alert('Pricing configuration saved successfully!');
  } catch (error) {
    console.error('Save pricing error:', error);
    alert('Failed to save pricing configuration');
  }
});

qs('#btnCalcWeight').addEventListener('click', async ()=>{
  try {
    const weight = parseFloat(qs('#calcWeight').value||'0');
    const res = await apiPost(API.pricing, { action: 'calculate', by: 'weight', weight });
    qs('#calcTotal').textContent = fmtPeso(res.data.total||0);
  } catch (error) {
    console.error('Weight calculation error:', error);
    qs('#calcTotal').textContent = '₱0.00';
  }
});

qs('#btnCalcType').addEventListener('click', async ()=>{
  try {
    const shirts = parseInt(qs('#calcShirts').value||'0');
    const pants = parseInt(qs('#calcPants').value||'0');
    const res = await apiPost(API.pricing, { action: 'calculate', by: 'type', shirts, pants });
    qs('#calcTotal').textContent = fmtPeso(res.data.total||0);
  } catch (error) {
    console.error('Type calculation error:', error);
    qs('#calcTotal').textContent = '₱0.00';
  }
});

// Schedule
async function loadSchedule(){
  const res = await apiPost(API.schedule, { action: 'list_all' });
  const tbody = qs('#tblSchedule tbody');
  tbody.innerHTML = '';
  res.data.forEach((s,i)=>{
    const tr = document.createElement('tr');
    tr.innerHTML = `<td>${i+1}</td>
      <td>${s.customer_name}</td>
      <td>${s.type}</td>
      <td>${s.slot_time}</td>
      <td><span class="badge bg-secondary">${s.status}</span></td>
      <td><button class="btn btn-sm btn-outline-secondary" data-act="sched-status" data-id="${s.id}">Update</button></td>`;
    tbody.appendChild(tr);
  });
}

document.getElementById('btnAddSlot').addEventListener('click', async ()=>{
  const customer_name = prompt('Customer name'); if(!customer_name) return;
  const type = prompt('Type: pickup or delivery'); if(!type) return;
  const slot_time = prompt('Slot time (YYYY-MM-DD HH:MM)'); if(!slot_time) return;
  await apiPost(API.schedule, { action:'create_slot', customer_name, type, slot_time });
  loadSchedule();
});

document.addEventListener('click', async (e)=>{
  const btn = e.target.closest('button[data-act="sched-status"]');
  if(!btn) return;
  const id = btn.getAttribute('data-id');
  const status = prompt('New status: scheduled, completed, canceled');
  if(!status) return;
  await apiPost(API.schedule, { action:'update_status', id, status });
  loadSchedule();
});

// Notifications
async function loadNotifications(){
  const res = await apiPost(API.notifications, { action: 'list_all' });
  const tbody = qs('#tblNotifications tbody');
  tbody.innerHTML = '';
  res.data.forEach((n,i)=>{
    const tr = document.createElement('tr');
    tr.innerHTML = `<td>${i+1}</td><td>${n.to_name}</td><td>${n.message}</td><td>${n.created_at}</td>`;
    tbody.appendChild(tr);
  });
}

document.getElementById('btnSendNotif').addEventListener('click', async ()=>{
  const to_name = prompt('Send to customer name'); if(!to_name) return;
  const message = prompt('Message'); if(!message) return;
  await apiPost(API.notifications, { action:'send', to_name, message });
  loadNotifications();
});

// Initial loads when showing sections
function onSectionShown(key){
  if(key==='customers') loadCustomers();
  if(key==='orders') loadOrders();
  if(key==='pricing') loadPricing();
  if(key==='schedule') loadSchedule();
  if(key==='notifications') loadNotifications();
}

// observe section switches
const observer = new MutationObserver(()=>{
  ['customers','orders','pricing','schedule','notifications'].forEach(k=>{
    const visible = !document.getElementById(`section-${k}`).classList.contains('d-none');
    if(visible) onSectionShown(k);
  });
});
observer.observe(document.body, { attributes:true, subtree:true, attributeFilter:['class'] });

// default home
showSection('home');
