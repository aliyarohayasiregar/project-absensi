<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PT Guna Dodos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            padding: 2.5rem;
            backdrop-filter: blur(10px);
        }
        .company-logo {
            width: 120px;
            height: auto;
            margin-bottom: 1.5rem;
        }
        .company-name {
            color: #1a5f7a;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .company-address {
            color: #2c5282;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #cbd5e0;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #43cea2;
            box-shadow: 0 0 0 2px rgba(67, 206, 162, 0.2);
        }
        .btn-login {
            background: linear-gradient(to right, #43cea2, #185a9d);
            border: none;
            padding: 0.8rem;
            border-radius: 8px;
            width: 100%;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: linear-gradient(to right, #3bb793, #14508c);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(67, 206, 162, 0.2);
        }
        .alert {
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.9);
        }
        .form-label {
            color: #2d3748;
            font-weight: 500;
        }
        .invalid-feedback {
            font-size: 0.85rem;
        }
        @media (max-height: 700px) {
            body {
                align-items: flex-start;
            }
            .login-container {
                margin: 20px auto;
            }
        }
    </style>
</head>
<body>
    <div class="login-container text-center">
        <!-- Logo perusahaan bisa ditambahkan di sini -->
        <!-- <img src="{{ asset('images/logo.png') }}" alt="PT Guna Dodos" class="company-logo"> -->
        
        <h1 class="company-name">PT Guna Dodos</h1>
        <p class="company-address">
            Jl. Bintara No. 14 F<br>
            Pekanbaru, Riau
        </p>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="text-start">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       name="password" 
                       required>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-login btn-primary">
                Login
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 