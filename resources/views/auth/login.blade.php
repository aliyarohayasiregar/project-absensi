<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PT Guna Dodos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1rem;
            filter: drop-shadow(0 0 5px rgba(40, 167, 69, 0.3));
        }
        .company-logo:hover {
            animation: treeWave 2s ease infinite;
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
            box-shadow: 0 0 0 0.2rem rgba(67, 206, 162, 0.25);
        }
        .btn-login {
            width: 100%;
            padding: 0.8rem;
            font-weight: 600;
            background: linear-gradient(to right, #43cea2, #185a9d);
            border: none;
            border-radius: 8px;
            color: white;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 206, 162, 0.4);
        }
        @keyframes treeWave {
            0% { transform: rotate(0deg); }
            25% { transform: rotate(5deg); }
            75% { transform: rotate(-5deg); }
            100% { transform: rotate(0deg); }
        }
    </style>
</head>
<body>
    <div class="login-container text-center">
        <!-- Icon pohon sawit -->
        <i class="fas fa-tree company-logo"></i>
        
        <h1 class="company-name">PT Guna Dodos</h1>
        <p class="company-address">
            <!-- Jl. Bintara No. 14 F<br>
            Pekanbaru, Riau -->
        </p>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="text-start">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-2"></i>Email
                </label>
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
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-2"></i>Password
                </label>
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       name="password" 
                       required>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 