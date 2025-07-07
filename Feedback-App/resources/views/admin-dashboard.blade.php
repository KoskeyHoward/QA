<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f1f5f9;
    }
    .dashboard-container {
      background: #fff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    .btn-group-sm .btn {
      margin-right: 5px;
    }
    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }
  </style>
</head>
<body>

<div class="container mt-5 dashboard-container">
  <div class="top-bar">
    <h3 class="text-primary">Admin Feedback Dashboard</h3>
    <button class="btn btn-outline-danger btn-sm" onclick="logout()">Logout</button>
  </div>
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Message</th>
          <th>Rating</th>
          <th>Approved</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="feedbackTableBody"></tbody>
    </table>
  </div>
  <div id="statusMsg" class="mt-3 fw-semibold text-success"></div>
</div>

<!-- Scripts -->
<script>
  function renderStars(rating) {
  if (!rating) return '';
  let stars = '';
  for (let i = 1; i <= 5; i++) {
    stars += i <= rating ? '★' : '☆';
  }
  return `<span style="color:#fbc02d; font-size: 1.2rem;">${stars}</span>`;
}
  
  // Redirect to admin login if not logged in
  if (!localStorage.getItem('admin_logged_in')) {
    alert("Please login first.");
    window.location.href = "{{ url('/admin') }}";
  }

  async function loadFeedbacks() {
    const res = await fetch("{{ url('/api/feedbacks') }}");
    const feedbacks = await res.json();
    const tableBody = document.getElementById('feedbackTableBody');
    tableBody.innerHTML = '';

    feedbacks.forEach(fb => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${fb.name}</td>
        <td>${fb.email}</td>
        <td>${fb.message}</td>
        <td>${renderStars(fb.rating)}</td>

        <td><span class="badge ${fb.approved ? 'bg-success' : 'bg-secondary'}">${fb.approved ? 'Yes' : 'No'}</span></td>
        <td>
          <div class="btn-group btn-group-sm">
            <button class="btn btn-success" onclick="approveFeedback(${fb.id})">Approve</button>
            <button class="btn btn-danger" onclick="deleteFeedback(${fb.id})">Delete</button>
          </div>
        </td>
      `;
      tableBody.appendChild(row);
    });
  }

  async function approveFeedback(id) {
    await fetch(`{{ url('/api/feedback') }}/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ approved: true })
    });
    document.getElementById('statusMsg').innerText = "✅ Feedback approved.";
    loadFeedbacks();
  }

  async function deleteFeedback(id) {
    await fetch(`{{ url('/api/feedback') }}/${id}`, {
      method: 'DELETE'
    });
    document.getElementById('statusMsg').innerText = "🗑️ Feedback deleted.";
    loadFeedbacks();
  }

  function logout() {
    localStorage.removeItem('admin_logged_in');
    window.location.href = "{{ url('/admin') }}";
  }

  loadFeedbacks();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<!-- End of admin-dashboard.blade.php -->
<!-- This file is part of the Feedback App project. It provides the admin dashboard for managing feedback