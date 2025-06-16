<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
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
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
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
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="password"] {
            padding: 10px 14px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: border-color 0.2s ease;
        }

        input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }

        input[type="hidden"] {
            display: none;
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
        }

        button:hover {
            background-color: #0056b3;
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

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
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
        <p class="subtitle">Set your new password below</p>

        @if ($errors->any())
            <div class="error-message">
                <ul style="margin:0; padding-left: 1.2rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="resetPasswordForm" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" id="email" value="{{ $email }}">

            <input type="password" name="password" id="password" placeholder="New Password" required autocomplete="new-password" />
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password" />

            <button type="submit">Reset Password</button>
        </form>
    </div>

    <script>
        document.getElementById('resetPasswordForm').addEventListener('submit', function (e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('password_confirmation').value.trim();

            if (!email) {
                alert('Email is missing. Cannot reset password.');
                e.preventDefault();
                return;
            }

            const emailPattern = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
            if (!emailPattern.test(email)) {
                alert('Invalid email format.');
                e.preventDefault();
                return;
            }

            if (!password || !confirmPassword) {
                alert('Please fill out both password fields.');
                e.preventDefault();
                return;
            }

            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
