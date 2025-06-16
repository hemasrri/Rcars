<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Forgot Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .logo-wrapper {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .logo-wrapper img {
            height: 60px;
        }

        .container {
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
            padding: 40px 30px;
            width: 100%;
            max-width: 420px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.4rem;
        }

        p.subtitle {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 20px;
        }

        form {
            margin-top: 1rem;
        }

        input[type="email"] {
            padding: 10px 14px;
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        input[type="email"]:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            padding: 10px 18px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        .status-message {
            background-color: #e6ffed;
            border: 1px solid #a3f7b5;
            color: #18794e;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .error-message {
            background-color: #ffe6e6;
            border: 1px solid #f5c2c7;
            color: #b02a37;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 0.9rem;
            text-align: left;
        }

        .back-to-login {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            font-size: 0.9rem;
        }

        .back-to-login:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="logo-wrapper">
        <img src="{{ asset('images/uthm.png') }}" alt="UTHM Logo" />
    </div>

    <div class="container">
        <h2>Residential College Accommodation Rental System</h2>
        <p class="subtitle">Reset your password below</p>

        @if (session('status'))
            <div class="status-message">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-message">
                <ul style="margin:0; padding-left: 1.2rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <input type="email" name="email" placeholder="Enter your email address" required autofocus>
            <button type="submit">Send Reset Link</button>
        </form>

        <a href="{{ route('login') }}" class="back-to-login">← Back to Login</a>
    </div>
</body>
</html>
