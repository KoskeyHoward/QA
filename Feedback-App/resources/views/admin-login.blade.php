<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #e2e8f0;
    }
    .login-box {
      background: #fff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="col-md-5 login-box">
    <h3 class="mb-4 text-center text-primary">Admin Login</h3>
    <form id="loginForm">
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" required />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" required />
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
    <div id="response" class="mt-3 text-center text-danger fw-bold"></div>
  </div>
</div>

<footer class="text-center mt-5">
  &copy; <span id="year"></span> Feedback App. All rights reserved.
</footer>

<!-- Scripts -->
<script>
  // Redirect if already logged in
  if (localStorage.getItem('admin_logged_in')) {
    window.location.href = "{{ url('/admin-dashboard') }}";
  }

  // Set footer year
  document.getElementById('year').textContent = new Date().getFullYear();

  // Handle login form submission
  document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const data = {
      email: document.getElementById('email').value,
      password: document.getElementById('password').value
    };

    const res = await fetch("{{ url('/api/admin/login') }}", {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });

    const result = await res.json();
    const responseDiv = document.getElementById('response');

    if (res.ok) {
      localStorage.setItem('admin_logged_in', 'true');
      responseDiv.classList.replace('text-danger', 'text-success');
      responseDiv.innerText = '✅ Login successful... Redirecting';

      setTimeout(() => {
        window.location.href = "{{ url('/admin-dashboard') }}";
      }, 1000);
    } else {
      responseDiv.classList.replace('text-success', 'text-danger');
      responseDiv.innerText = result.error || 'Login failed. Try again.';
    }
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<!-- This is the admin login page for the feedback app. It allows admins to log in and manage feedbacks. -->
<!-- It includes a form for email and password, and handles login via an API call. -->