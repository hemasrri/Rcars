<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Verify Your Email Address</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #dfe9f3, #ffffff);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
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
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            max-width: 450px;
            padding: 40px 30px;
            text-align: center;
            margin-top: 100px;
        }
        h2 {
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }
        p {
            color: #444;
            font-size: 16px;
            margin-bottom: 1.2rem;
        }
        strong {
            color: #007bff;
        }
        .alert {
            margin-bottom: 20px;
            font-size: 14px;
            border-radius: 8px;
            padding: 12px;
        }
        form button {
            font-weight: 600;
            font-size: 14px;
            text-decoration: underline;
            border: none;
            background: none;
            color: #007bff;
            cursor: pointer;
            padding: 0;
        }
        form button:hover {
            color: #0056b3;
        }
        @media (max-width: 576px) {
            .container {
                margin-top: 60px;
                padding: 30px 20px;
            }
            h2 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>

    <div class="logo-wrapper">
        <img src="{{ asset('images/uthm.png') }}" alt="UTHM Logo" />
    </div>

    <div class="container">
        <h2>Residential College Accommodation Rental System</h2>
        <h2>Verify Your Email Address</h2>

        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <p>
            Before proceeding, please check your email for a verification link.<br />
            If you did not receive the email,
        </p>
        <p>Verification link will be sent to: <strong>{{ Auth::user()->email }}</strong></p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">click here to request another</button>.
        </form>
    </div>

</body>
</html>
