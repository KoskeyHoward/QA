<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback App | Laravel</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Toastify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    
    <style>
        :root {
            --primary-color: #08925d;
            --secondary-color: #08925d;
            --light-bg: #f8f9fa;
            --dark-bg: #121212;
            --card-bg: #ffffff;
            --dark-card-bg: #1e1e1e;
            --text-light: #495057;
            --text-dark: #e1e1e1;
        }
        
        
        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-light);
            transition: all 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Header Styles */
        header {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
        }
        
        .nav-link {
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            transform: translateY(-2px);
        }
        
        /* Main Content Styles */
        .hero-section {
            background: linear-gradient(135deg, rgba(67,97,238,0.1) 0%, rgba(63,55,201,0.1) 100%);
            border-radius: 20px;
            padding: 4rem 2rem;
            margin: 2rem auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hero-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }
        
        .hero-title {
            font-size: 2.8rem;
            font-weight: 700;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Button Styles */
        .btn-cta {
            padding: 0.8rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 10px 20px rgba(67,97,238,0.2);
        }
        
        .btn-outline-secondary {
            border-width: 2px;
        }
        
        .btn-outline-secondary:hover {
            background-color: transparent;
            transform: translateY(-3px);
        }
        
        /* Feedback Cards */
        .feedback-section {
            width: 100%;
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 2rem;
        }
        
        .section-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            margin: 0.5rem auto;
            border-radius: 2px;
        }
        
        .feedback-card {
            background-color: var(--card-bg);
            border-radius: 15px;
            padding: 1.5rem;
            height: 100%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: none;
        }
        
        .feedback-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .feedback-user {
            flex: 1;
        }
        
        .feedback-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--primary-color);
        }
        
        .feedback-email {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .feedback-rating {
            color: #ffc107;
            font-size: 1.1rem;
        }
        
        .feedback-message {
            color: var(--text-light);
            margin: 1rem 0;
            line-height: 1.6;
        }
        
        .feedback-date {
            font-size: 0.8rem;
            color: #6c757d;
            text-align: right;
        }
        
        /* Dark Mode Styles */
        body.dark-mode {
            background-color: var(--dark-bg);
            color: var(--text-dark);
        }
        
        .dark-mode .hero-section {
            background: linear-gradient(135deg, rgba(67,97,238,0.1) 0%, rgba(63,55,201,0.1) 100%);
        }
        
        .dark-mode .feedback-card {
            background-color: var(--dark-card-bg);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .dark-mode .feedback-name,
        .dark-mode .feedback-message {
            color: var(--text-dark);
        }
        
        .dark-mode .feedback-email,
        .dark-mode .feedback-date {
            color: #a1a1a1;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .btn-cta {
                padding: 0.7rem 1.5rem;
                font-size: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.8rem;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body class="text-white">
    <header class="py-3 text-success">
        <div class="header d-flex justify-content-between align-items-center">
            <h1>Feedback Hub App</h1>
                @if (Route::has('login'))
                    <nav>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-outline-success text-dark nav-link">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn nav-link">
                                <i class="fas fa-sign-in-alt me-2"></i>Admin Login
                            </a>
                        @endauth
                    </nav>
                @endif
        </div>
    </header>

    <main class="py-5 flex-grow-1">
        <div class="container">
            <div class="text-center hero-section">
                <h1 class="hero-title">Welcome to Feedback App</h1>
                <p class="hero-subtitle">Share your thoughts to help us improve or manage feedback as an admin.</p>
                <div class="flex-wrap d-flex justify-content-center">
                    <a href="{{ url('/feedback') }}" class="btn btn-primary btn-cta">
                        <i class="far fa-comment-dots me-2"></i>Submit Feedback
                    </a>
                    
                </div>
            </div>
        </div>

        <!-- Approved Feedback Section -->
        <section class="feedback-section">
            <h2 class="section-title">Recent Feedback</h2>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach(\App\Models\Feedback::where('approved', true)->latest()->take(6)->get() as $feedback)
                <div class="col">
                    <div class="feedback-card">
                        <div class="feedback-header">
                            <div class="feedback-user">
                                <h3 class="feedback-name">{{ $feedback->name }}</h3>
                                <p class="feedback-email">{{ $feedback->email }}</p>
                            </div>
                            @if($feedback->rating)
                            <div class="feedback-rating">
                                {{ str_repeat('★', $feedback->rating) }}
                            </div>
                            @endif
                        </div>
                        <p class="feedback-message">{{ $feedback->message }}</p>
                        <p class="feedback-date">
                            {{ $feedback->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </main>

    <footer class="py-4 text-center text-muted">
        <div class="container">
            <p class="mb-0">© {{ date('Y') }} Feedback App. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Dark Mode Toggle Script (optional) -->
    <script>
        // Simple dark mode toggle
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.createElement('button');
            darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            darkModeToggle.className = 'btn btn-sm btn-outline-secondary position-fixed bottom-0 end-0 m-3 rounded-circle';
            darkModeToggle.style.width = '50px';
            darkModeToggle.style.height = '50px';
            darkModeToggle.addEventListener('click', function() {
                document.body.classList.toggle('dark-mode');
                darkModeToggle.innerHTML = document.body.classList.contains('dark-mode') ? 
                    '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            });
            document.body.appendChild(darkModeToggle);
        });
    </script>
</body>
</html>