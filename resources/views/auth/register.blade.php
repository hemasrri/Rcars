<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register</title>
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
            z-index: 100;
        }

        .logo-wrapper img {
            height: 60px; /* Adjust as needed */
        }
.container {
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    padding: 5px 20px; /* Reduced vertical padding */
    width: 100%;
    max-width: 600px;
    max-height: 550px; /* Optional: Set a max height */
    text-align: center;
    position: relative;
    z-index: 10;
}

.container h2 {
    margin-bottom: 8px; /* Reduced margin */
    font-weight: 600;
    color: #333;
}

.container form > div {
    margin-bottom: 4px; /* Reduced space between inputs */
}

.container button {
    padding: 4px; /* Slightly smaller button */
    font-size: 15px;
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
        input[type="email"],
        input[type="tel"],
        input[type="password"] {
            width: 100%;
            padding: 2px 2px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 10px;
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

        .alert-modal {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .alert-box {
            background-color: #fff;
            border-left: 5px solid #dc3545;
            padding: 20px;
            border-radius: 10px;
            max-width: 350px;
            color: #721c24;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            text-align: left;
        }

        .alert-box h3 {
            margin-top: 0;
            margin-bottom: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #dc3545;
        }

        .alert-box ul {
            list-style-type: disc;
            margin-left: 20px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-box button {
            width: 100%;
            background-color: #dc3545;
            color: white;
            border-radius: 8px;
            padding: 10px;
            font-weight: 600;
        }

        .alert-box button:hover {
            background-color: #a71d2a;
        }

        .login-link {
            margin-top: 10px;
            font-size: 14px;
            color: #444;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Logos -->
    <div class="logo-wrapper">
        <img src="{{ asset('images/uthm.png') }}" alt="UTHM Logo" />
    </div>

 <div class="container">
        <h2>Residential College Accommodation Rental System</h2>
        <h2>Register Your Account</h2>

        {{-- Error Modal --}}
        @if ($errors->any())
            <div id="error-modal" class="alert-modal">
                <div class="alert-box">
                    <h3>
                        <!-- SVG close icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" color="#dc3545">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        Errors Found
                    </h3>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button id="close-error">Close</button>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}" novalidate>
            @csrf
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required />

            <label for="ic">IC Number</label>
            <input type="text" id="ic" name="ic" required />

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required />

            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" required />

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />

            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required />

            <button type="submit">Register</button>
        </form>

        <p class="login-link">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </p>
    </div>

    <script>
        document.getElementById('close-error')?.addEventListener('click', () => {
            document.getElementById('error-modal').style.display = 'none';
        });
    </script>

</body>
</html>
