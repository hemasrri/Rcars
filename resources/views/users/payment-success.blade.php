<!DOCTYPE html>
<html lang="en" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <style>
        html.dark body {
            background-color: #1a2e2b;
            color: #a7f3d0;
        }

        html.dark h1 {
            color: #6ee7b7;
        }

        html.dark .details {
            background-color: #064e3b;
            color: #d1fae5;
        }

        html.dark .button {
            background-color: #059669;
        }

        html.dark .button:hover {
            background-color: #047857;
        }

        html.dark .countdown {
            color: #a7f3d0;
        }
    </style>
</head>
<body class="font-sans bg-green-50 text-green-900 dark:bg-gray-900 dark:text-green-100 text-center p-8">

    <h1 class="text-3xl font-bold mb-2">Your Transaction is Now Completed</h1>
    <p class="mb-4">Thank you for using RCARS Payment</p>

    <div class="details bg-green-100 dark:bg-gray-800 dark:text-green-100 p-6 rounded-lg max-w-lg mx-auto text-left">
        <p><strong>Applicant Name:</strong> {{ $payment->application->name }}</p>
        <p><strong>IC Number:</strong> {{ $payment->application->ic_number }}</p>
        <p><strong>Transaction Status:</strong> {{ ucfirst($payment->payment_status) }}</p>
        <p><strong>Transaction Date & Time:</strong> {{ $payment->payment_datetime }}</p>
        <p><strong>Transaction ID:</strong> {{ $payment->transaction_id ?? 'N/A' }}</p>
        <p><strong>Application ID:</strong> {{ $payment->application_id }}</p>
        <p><strong>Payment Method:</strong> {{ $payment->payment_method }}</p>
        <p><strong>Amount:</strong> RM {{ number_format($payment->amount, 2) }}</p>
    </div>

    <div class="mt-6">
        <a class="button bg-emerald-500 hover:bg-emerald-600 text-white py-2 px-5 rounded inline-block m-2"
           href="{{ route('users.dashboard') }}">
            Back to Main Page
        </a>
        <a class="button bg-emerald-500 hover:bg-emerald-600 text-white py-2 px-5 rounded inline-block m-2"
           href="{{ route('payments.download.receipt', $payment->payment_id) }}">
            Download Receipt
        </a>
    </div>

    <div class="countdown text-sm mt-4">
        Redirecting to the main page in <span id="seconds">10</span> seconds...
    </div>

    <footer class="mt-16 text-center text-sm text-gray-500 dark:text-gray-400 p-6">
        <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
    </footer>

    <script>
        let countdown = 10;
        const secondsSpan = document.getElementById('seconds');

        const interval = setInterval(() => {
            countdown--;
            secondsSpan.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(interval);
                window.location.href = "{{ route('users.dashboard') }}";
            }
        }, 1000);
    </script>

</body>
</html>
