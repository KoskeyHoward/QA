<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Toastr CSS with custom styling -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --primary-color: #08925d;
                --secondary-color: #08925d;
            }

            body {
                font-family: 'Figtree', sans-serif;
                background-color: #f8f9fa;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
            main {
                flex: 1;
            }
            
            /* Custom Toastr Styling */
            #toast-container {
                margin-top: 70px;
            }
            .toast {
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                border: none;
                padding: 15px 20px;
                font-size: 14px;
            }
            .toast-success {
                background-color: var(--primary-color); /* #08925d from second file */
            }
            .toast-error {
                background-color: #dc3545;
            }
            .toast-info {
                background-color: #17a2b8;
            }
            .toast-warning {
                background-color: #ffc107;
                color: #212529;
            }
            .toast-close-button {
                color: white;
                opacity: 0.8;
                font-size: 18px;
            }
            .toast-progress {
                height: 3px;
                background-color: var(--secondary-color); /* #08925d from second file */
                opacity: 0.6;
            }
        </style>
    </head>
    <body class="antialiased">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow-sm">
                <div class="container py-4">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="py-4">
            <div class="container">
                {{ $slot }}
            </div>
        </main>

        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Toastr JS with initialization -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script>
            // Initialize Toastr with custom settings
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
                "tapToDismiss": false
            };

            // Display flash messages
            document.addEventListener('DOMContentLoaded', function() {
                @if(Session::has('success'))
                    toastr.success("{{ Session::get('success') }}", 'Success');
                @endif

                @if(Session::has('error'))
                    toastr.error("{{ Session::get('error') }}", 'Error');
                @endif

                @if(Session::has('warning'))
                    toastr.warning("{{ Session::get('warning') }}", 'Warning');
                @endif

                @if(Session::has('info'))
                    toastr.info("{{ Session::get('info') }}", 'Info');
                @endif

                @if($errors->any())
                    @foreach($errors->all() as $error)
                        toastr.error("{{ $error }}", 'Validation Error');
                    @endforeach
                @endif
            });
        </script>
    </body>
</html>