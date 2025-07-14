<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback App</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styling -->
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', sans-serif;
        }

        .header {
            background-color: #0d6efd;
            color: white;
            padding: 1.2rem 2rem;
        }

        .header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .welcome-section {
            padding: 4rem 1rem;
        }

        .welcome-card {
            background: white;
            padding: 3rem 2rem;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            max-width: 700px;
            margin: auto;
            text-align: center;
        }

        .welcome-card h2 {
            font-weight: bold;
            color: #0d6efd;
        }

        .welcome-card p {
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
        }

        footer {
            padding: 1rem;
            text-align: center;
            color: #6c757d;
            margin-top: 4rem;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header d-flex justify-content-between align-items-center">
        <h1>Feedback Hub App</h1>
        @if (Route::has('login'))
            <div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-outline-light">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-light text-primary">Admin Login</a>
                @endauth
            </div>
        @endif
    </div>

    <!-- Main Welcome Section -->
    <section class="welcome-section">
        <div class="welcome-card">
            <h2 class="mb-3">Welcome to Feedback App</h2>
            <p>Help us improve by sharing your thoughts or log in as admin to manage submitted feedback.</p>
            <a href="{{ url('/feedback') }}" class="btn btn-primary">Submit Feedback</a>
        </div>
    </section>

    <!-- Approved Feedback Section -->
   @php
    $feedbacks = \App\Models\Feedback::where('approved', true)->latest()->take(15)->get();
    $grouped = $feedbacks->chunk(3); // Only full rows of 3
@endphp

<div class="container mt-5">
    <h3 class="text-center mb-4 text-dark">Recent Feedback</h3>

    @foreach($grouped as $group)
        @if($group->count() === 3) {{-- Only show complete rows --}}
        <div class="row mb-4">
            @foreach($group as $feedback)
                <div class="col-md-4">
                    <div class="p-3 mb-4 rounded shadow-sm" style="background-color: #e0f2fe;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="fw-semibold mb-1">{{ $feedback->name }}</h5>
                                <small class="text-muted">{{ $feedback->email }}</small>
                            </div>
                            @if($feedback->rating)
                                <div class="text-warning fs-5">
                                    {{ str_repeat('★', $feedback->rating) }}
                                </div>
                            @endif
                        </div>
                        <p class="mb-2">{{ $feedback->message }}</p>
                        <small class="text-muted">{{ $feedback->created_at->format('M d, Y') }}</small>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    @endforeach
</div>


    <!-- Footer -->
    <footer>
        &copy; {{ date('Y') }} Feedback Hub. All rights reserved.
    </footer>

</body>
</html>
