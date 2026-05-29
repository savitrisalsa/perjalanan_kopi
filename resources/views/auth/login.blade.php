<!DOCTYPE html>
<html>
<head>
    <title>Login Perjalanan Kopi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #111827, #374151, #d97706);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            border: none;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
        }

        .brand-icon {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: #f59e0b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 12px auto;
        }

        .form-control {
            height: 45px;
            border-radius: 10px;
        }

        .btn-login {
            height: 45px;
            border-radius: 10px;
            font-weight: 600;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 34px;
            border: none;
            background: transparent;
            color: #6b7280;
            font-size: 14px;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="card login-card p-4 mx-auto">
        <div class="brand-icon">☕</div>

        <h3 class="text-center fw-bold mb-1">Perjalanan Kopi</h3>
        <p class="text-center text-muted mb-4">Login Admin / Kasir</p>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input
                    type="text"
                    name="username"
                    class="form-control"
                    placeholder="Masukkan username"
                    value="{{ old('username') }}"
                    required
                    autofocus
                >
            </div>

            <div class="mb-3 password-wrapper">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required
                >

                <button type="button" class="toggle-password" onclick="togglePassword()">
                    Lihat
                </button>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100 mt-2">
                Login
            </button>
        </form>

        <hr>

        <p class="text-center text-muted small mb-0">
            Sistem Kasir Perjalanan Kopi
        </p>
    </div>
</div>

<script>
    function togglePassword() {
        let passwordInput = document.getElementById('password');
        let button = document.querySelector('.toggle-password');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            button.innerText = 'Sembunyi';
        } else {
            passwordInput.type = 'password';
            button.innerText = 'Lihat';
        }
    }
</script>

</body>
</html>