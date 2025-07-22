<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi QR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e3f0fa 100%);
            min-height: 100vh;
        }
        .navbar {
            background: linear-gradient(90deg, #3498db 0%, #6dd5fa 100%);
            box-shadow: 0 2px 8px rgba(52,152,219,0.08);
        }
        .navbar .navbar-brand, .navbar .nav-link, .navbar .nav-link.active {
            color: #fff !important;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .navbar .nav-link {
            margin-right: 1rem;
            transition: color 0.2s;
        }
        .navbar .nav-link:hover, .navbar .nav-link.active {
            color: #ffe082 !important;
        }
        .navbar .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.3rem;
        }
        .navbar .navbar-brand i {
            font-size: 1.5rem;
        }
        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .main-content-card {
            background: #fff;
            border-radius: 1.1rem;
            box-shadow: 0 4px 24px rgba(52,152,219,0.08);
            padding: 2.5rem 2rem;
            margin-top: 2.5rem;
            margin-bottom: 2.5rem;
        }
        footer {
            text-align: center;
            color: #7b8a99;
            font-size: 0.98rem;
            padding: 1.5rem 0 0.5rem 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-qrcode"></i> 出席
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('attendances') ? 'active' : '' }}" href="{{ route('attendance.index') }}">
                            <i class="fas fa-list mr-1"></i> Daftar Absensi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('attendance.create') }}">
                            <i class="fas fa-plus-circle mr-1"></i> Buat Absensi
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="main-content-card">
            @yield('content')
        </div>
    </div>

    <footer>
        &copy; {{ date('Y') }} Absensi QR. Property of ENVVAVOR. All rights reserved.
        <br>
        <a href="https://www.envvavor.com" target="_blank">www.envvavor.com</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>