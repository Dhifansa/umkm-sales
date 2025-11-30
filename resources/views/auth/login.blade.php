<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UMKM Sales Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .login-header i {
            font-size: 4rem;
            margin-bottom: 15px;
        }

        .login-header h3 {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .login-header p {
            opacity: 0.9;
            margin: 0;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: bold;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }

        .divider span {
            padding: 0 15px;
            color: #999;
            font-size: 14px;
        }

        .demo-credentials {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .demo-credentials h6 {
            color: #667eea;
            margin-bottom: 10px;
        }

        .demo-credentials p {
            margin: 5px 0;
            font-size: 14px;
        }

        .floating-shapes {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 20s infinite;
        }

        .shape1 {
            top: 10%;
            left: 10%;
            font-size: 5rem;
            animation-delay: 0s;
        }

        .shape2 {
            top: 60%;
            right: 10%;
            font-size: 4rem;
            animation-delay: 5s;
        }

        .shape3 {
            bottom: 10%;
            left: 20%;
            font-size: 6rem;
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-30px) rotate(180deg);
            }
        }

        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <!-- Floating Shapes Background -->
    <div class="floating-shapes">
        <i class="bi bi-shop shape shape1"></i>
        <i class="bi bi-cart shape shape2"></i>
        <i class="bi bi-bag shape shape3"></i>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="bi bi-shop-window"></i>
                <h3>UMKM Sales</h3>
                <p>Sistem Manajemen Penjualan</p>
            </div>

            <div class="login-body">
                <h5 class="text-center mb-4">Selamat Datang Kembali!</h5>

                @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-circle"></i>
                    <strong>Login Gagal!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-floating">
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               placeholder="name@example.com"
                               value="{{ old('email') }}"
                               required
                               autofocus>
                        <label for="email"><i class="bi bi-envelope"></i> Email</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating">
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Password"
                               required>
                        <label for="password"><i class="bi bi-lock"></i> Password</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input"
                               type="checkbox"
                               id="remember"
                               name="remember"
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Ingat saya
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-login">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </button>
                    </div>
                </form>

                <div class="demo-credentials">
                    <h6><i class="bi bi-info-circle"></i> Demo Credentials</h6>
                    <p><strong>Email:</strong> admin@umkm.com</p>
                    <p><strong>Password:</strong> password</p>
                </div>
            </div>
        </div>

        <p class="text-center text-white mt-4 mb-0">
            <small>&copy; 2024 UMKM Sales Management. All rights reserved.</small>
        </p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
