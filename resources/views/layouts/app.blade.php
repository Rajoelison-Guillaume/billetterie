<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Billetterie Madagascar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom futurist blue theme -->
    <style>
        body {
            background-color: #0f172a;
            color: #e2e8f0;
        }
        .navbar, .footer {
            background-color: #1e3a8a;
        }
        .btn-primary {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
        }
        .card {
            background-color: #1e293b;
            color: #e2e8f0;
        }
    </style>
</head>
<body>
    @include('layouts.navigation')

    <main class="container py-5">
        @yield('content')
    </main>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
