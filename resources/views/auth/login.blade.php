<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #dfe9f3, #ffffff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            padding: 40px 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 6px;
            color: #444;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            transition: border 0.2s ease;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .radio-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .alert {
            display: flex;
            align-items: center;
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-left: 5px solid #dc3545;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-icon {
            margin-right: 10px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border-left: 5px solid #28a745;
        }

        .extra-links {
            margin-top: 20px;
            font-size: 14px;
            display: none;
        }

        .extra-links a {
            color: #007bff;
            text-decoration: none;
        }

        .extra-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="logo-wrapper">
        <img src="{{ asset('images/uthm.png') }}" alt="UTHM Logo">
    </div>

    <div class="container">
        <h2>Residential College Accommodation Rental System</h2>
        <h2>Login</h2>

        {{-- ✅ Success message after verification --}}
        @if(session('verified'))
            <div class="alert success">
                ✅ {{ session('verified') }}
            </div>
        @endif

        {{-- 🔴 Validation errors --}}
        @if ($errors->any())
            <div class="alert">
                <span class="alert-icon">⚠️</span>
                <ul style="margin: 0; padding-left: 0; list-style: none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="loginForm" action="{{ route('login.submit') }}" method="POST" novalidate>
            @csrf

            <div class="radio-group">
                <label><input type="radio" name="user_type" value="admin" required> Admin</label>
                <label><input type="radio" name="user_type" value="uthm" required> UTHM User</label>
                <label><input type="radio" name="user_type" value="non-uthm" required> Non-UTHM User</label>
            </div>

            @error('user_type')
                <div class="alert">{{ $message }}</div>
            @enderror

            <div>
                <label for="identifier" id="identifier-label">Username:</label>
                <input type="text" name="identifier" id="identifier" placeholder="Enter your username" required>
            </div>

            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" placeholder="Enter your password" required>
                @error('password')
                    <div class="alert">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <button type="submit">Login</button>
            </div>
        </form>

        <div id="non-uthm-user-options" class="extra-links">
            <a href="{{ route('password.request') }}">Forgot Password?</a> |
            <a href="{{ route('register') }}">Create Account</a>
        </div>
    </div>

    <script>
    // Handle user type change
    document.querySelectorAll('input[name="user_type"]').forEach((elem) => {
        elem.addEventListener('change', function () {
            const identifierInput = document.getElementById('identifier');
            const identifierLabel = document.getElementById('identifier-label');
            const nonUthmUserOptions = document.getElementById('non-uthm-user-options');

            switch (this.value) {
                case 'admin':
                    identifierLabel.textContent = 'Username:';
                    identifierInput.placeholder = 'Enter your username';
                    nonUthmUserOptions.style.display = 'none';
                    break;
                case 'uthm':
                    identifierLabel.textContent = 'SMAP ID / TCIS ID:';
                    identifierInput.placeholder = 'Enter your SMAP ID or TCIS ID';
                    nonUthmUserOptions.style.display = 'none';
                    break;
                case 'non-uthm':
                    identifierLabel.textContent = 'Email:';
                    identifierInput.placeholder = 'Enter your email';
                    nonUthmUserOptions.style.display = 'block';
                    break;
            }
        });
    });

    // Validate form fields before submitting
    document.getElementById('loginForm').addEventListener('submit', function (e) {
        const userType = document.querySelector('input[name="user_type"]:checked');
        const identifier = document.getElementById('identifier').value.trim();
        const password = document.querySelector('input[name="password"]').value.trim();

        if (!userType) {
            alert('Please select a user type.');
            e.preventDefault();
            return;
        }

        if (!identifier) {
            alert('Please fill in your identifier (Username/ID/Email).');
            e.preventDefault();
            return;
        }

        if (!password) {
            alert('Please enter your password.');
            e.preventDefault();
            return;
        }
    });

    // Auto-dismiss success alert after 5 seconds
    window.addEventListener('DOMContentLoaded', () => {
        const successAlert = document.querySelector('.alert.success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = 'opacity 0.5s ease';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500); // Fully remove after fade out
            }, 5000);
        }
    });
</script>


</body>
</html>
