<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #b4eccd;
            --secondary-color: #08925d;
            --light-bg: #f8f9fa;
            --card-bg: #ffffff;
            --text-light: #495057;
        }
        
        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }
        
        .form-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .feedback-card {
            background-color: rgb(240, 247, 243);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .feedback-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }
        
        .form-title {
            font-size: 2rem;
            color: rgb(5, 163, 103);
        }
        
        .form-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            margin: 0.5rem auto;
            border-radius: 2px;
        }
        
        .form-control, .form-select {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1px solid #e1e1e1;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.8rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 10px 20px rgba(67, 238, 172, 0.2);
        }
        
        .btn-clear {
            background-color: #6c757d;
            border-color: #6c757d;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .btn-clear:hover {
            background-color: #5a6268;
            border-color: #5a6268;
        }
        
        .star-rating {
            margin-top: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .star {
            color: #6c757d;
            transition: color 0.2s;
            cursor: pointer;
            font-size: 1.8rem;
            margin-right: 0.3rem;
        }
        
        .star:hover, .star.active {
            color: #ffc107;
        }
        
        .alert {
            border-radius: 10px;
        }
        
        label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="feedback-card">
            <h3 class="form-title">Submit Your Feedback</h3>

            @if(session('toast'))
                <div class="alert alert-{{ session('toast.type') }}">
                    <strong>{{ session('toast.title') }}:</strong> {{ session('toast.message') }}
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

                <div class="mb-4">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="mb-4">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" 
                              id="message" name="message" rows="4" required>{{ old('message') }}</textarea>
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

                <button type="submit" class="mt-3 btn btn-success">
                    <i class="far fa-paper-plane me-2 "></i>Submit Feedback
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star');
            const ratingInput = document.getElementById('rating');
            const clearRatingButton = document.getElementById('clear-rating');
            const form = document.querySelector('form');

            // Initialize star display based on existing rating
            const currentValue = parseInt(ratingInput.value) || 0;
            stars.forEach((s, index) => {
                s.textContent = index < currentValue ? '★' : '☆';
                s.classList.toggle('active', index < currentValue);
            });

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = parseInt(this.getAttribute('data-value'));
                    ratingInput.value = value;

                    stars.forEach((s, index) => {
                        s.textContent = index < value ? '★' : '☆';
                        s.classList.toggle('active', index < value);
                    });
                });
                
                star.addEventListener('mouseover', function() {
                    const value = parseInt(this.getAttribute('data-value'));
                    stars.forEach((s, index) => {
                        s.textContent = index < value ? '★' : '☆';
                    });
                });
                
                star.addEventListener('mouseout', function() {
                    const currentValue = parseInt(ratingInput.value) || 0;
                    stars.forEach((s, index) => {
                        s.textContent = index < currentValue ? '★' : '☆';
                        s.classList.toggle('active', index < currentValue);
                    });
                });
            });

            clearRatingButton.addEventListener('click', function() {
                ratingInput.value = ''; // Set to empty string to represent null
                stars.forEach((s) => {
                    s.textContent = '☆';
                    s.classList.remove('active');
                });
            });

            // Ensure form submission handles rating correctly
            form.addEventListener('submit', function(event) {
                if (ratingInput.value === '0' || ratingInput.value === '') {
                    ratingInput.value = ''; // Ensure null is sent
                }
            });
        });
    </script>
</body>
</html>