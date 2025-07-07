<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Feedback App</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      transition: background-color 0.3s, color 0.3s;
    }

    .welcome-box {
      background: white;
      padding: 3rem;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
      transition: background 0.3s, color 0.3s;
    }

    .btn-lg {
      padding: 0.75rem 1.5rem;
      font-size: 1.1rem;
    }

    footer {
      padding: 1rem;
      text-align: center;
      color: #6c757d;
    }

    /* Dark Mode Styles */
    body.dark-mode {
      background-color: #121212;
      color: #e1e1e1;
    }

    .dark-mode .welcome-box {
      background-color: #1e1e1e;
      color: #e1e1e1;
    }

    .toggle-container {
      position: absolute;
      top: 20px;
      right: 20px;
    }
  </style>
</head>
<body>

<!-- 🌙 Dark Mode Toggle Switch -->
<div class="toggle-container">
  <div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" id="darkModeToggle">
    <label class="form-check-label" for="darkModeToggle">Dark Mode</label>
  </div>
</div>

<!-- 💬 Welcome Box -->
<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="welcome-box text-center">
    <h1 class="mb-4 text-primary">Welcome to Feedback App</h1>
    <p class="mb-4 text-muted">Help us improve by sharing your thoughts or log in as admin to manage feedback.</p>
    <div>
      <a href="{{ url('/feedback') }}" class="btn btn-primary btn-lg me-3">Submit Feedback</a>
      <a href="{{ url('/admin-login') }}" class="btn btn-outline-secondary btn-lg">Admin Login</a>
    </div>
  </div>
</div>

<!-- 📌 Footer -->
<footer>
  &copy; <span id="year"></span> Feedback App. All rights reserved.
</footer>

<!-- Scripts -->
<script>
  // Redirect to admin dashboard if already logged in
  if (localStorage.getItem('admin_logged_in')) {
    window.location.href = "{{ url('/admin-dashboard') }}";
  }

  // Set current year in footer
  document.getElementById('year').textContent = new Date().getFullYear();

  // Dark mode toggle logic
  const toggle = document.getElementById('darkModeToggle');
  const prefersDark = localStorage.getItem('darkMode') === 'true';

  if (prefersDark) {
    document.body.classList.add('dark-mode');
    toggle.checked = true;
  }

  toggle.addEventListener('change', () => {
    if (toggle.checked) {
      document.body.classList.add('dark-mode');
      localStorage.setItem('darkMode', 'true');
    } else {
      document.body.classList.remove('dark-mode');
      localStorage.setItem('darkMode', 'false');
    }
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
