<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Submit Feedback</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f1f5f9;
    }
    .form-card {
      background: #fff;
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }
    .star {
  color: #6c757d;
  transition: color 0.2s;
}

  </style>
</head>
<body>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6 form-card">
      <h3 class="mb-4 text-center text-primary">Submit Your Feedback</h3>
      <form id="feedbackForm">
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" required />
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" required />
        </div>
        <div class="mb-3">
          <label for="message" class="form-label">Message</label>
          <textarea class="form-control" id="message" rows="3" required></textarea>
        </div>
        <div class="mb-3">
        <label class="form-label d-block">Rating</label>
        <div id="starRating" class="d-flex gap-1">
          @for ($i = 1; $i <= 5; $i++)
            <span class="star fs-3" data-value="{{ $i }}" style="cursor:pointer;">&#9734;</span>
          @endfor
        </div>
        <input type="hidden" id="rating" />
      </div>

        <button type="submit" class="btn btn-primary w-100">Submit</button>
      </form>
      <div id="response" class="mt-3 text-center fw-bold"></div>
    </div>
  </div>
</div>

<script>
  // Redirect to admin dashboard if already logged in
  if (localStorage.getItem('admin_logged_in')) {
    window.location.href = "{{ url('/admin-dashboard') }}";
  }

  // Submit feedback
  document.getElementById('feedbackForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
      name: document.getElementById('name').value,
      email: document.getElementById('email').value,
      message: document.getElementById('message').value,
      rating: document.getElementById('rating').value || null
    };

    const res = await fetch("{{ url('/api/feedback') }}", {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });

    const responseDiv = document.getElementById('response');

    if (res.ok) {
      responseDiv.innerText = '✅ Feedback submitted!';
      responseDiv.classList.remove('text-danger');
      responseDiv.classList.add('text-success');
      document.getElementById('feedbackForm').reset();
    } else {
      responseDiv.innerText = '❌ Failed to submit feedback.';
      responseDiv.class
        .remove('text-success');
      responseDiv.classList.add('text-danger');
    }
  });   
  // Star rating functionality
  const stars = document.querySelectorAll('#starRating .star');
  const ratingInput = document.getElementById('rating');

  stars.forEach(star => {
    star.addEventListener('click', () => {
      const value = parseInt(star.getAttribute('data-value'));
      ratingInput.value = value;

      // Highlight selected stars
      stars.forEach(s => {
        s.innerHTML = parseInt(s.getAttribute('data-value')) <= value ? '★' : '☆';
        s.style.color = parseInt(s.getAttribute('data-value')) <= value ? '#fbc02d' : '#6c757d';
      });
    });
  });
</script>
