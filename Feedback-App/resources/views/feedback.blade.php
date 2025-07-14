{{-- resources/views/feedback.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f9;
        }
        .form-card {
            background: #fff;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            margin-top: 2rem;
        }
        .star {
            color: #6c757d;
            transition: color 0.2s;
            cursor: pointer;
            font-size: 1.5rem;
        }
        .star.active {
            color: #fbc02d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="form-card">
                    <h3 class="mb-4 text-center text-primary">Submit Your Feedback</h3>

                    @if(session('success'))
                        <div class="alert alert-success text-center" id="successMessage">
                         {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('feedback.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="3" required>{{ old('message') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Rating (optional)</label>
                            <div class="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star" data-value="{{ $i }}">
                                        {{ $i <= old('rating', 0) ? '★' : '☆' }}
                                    </span>
                                @endfor
                                <input type="hidden" name="rating" id="rating" value="{{ old('rating', '') }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Submit Feedback</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
       document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = parseInt(this.getAttribute('data-value'));
            ratingInput.value = value;

            stars.forEach((s, index) => {
                s.textContent = index < value ? '★' : '☆';
                s.classList.toggle('active', index < value);
            });
        });
    });
});
    </script>
</body>
</html>