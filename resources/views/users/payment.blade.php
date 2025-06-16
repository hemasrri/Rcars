<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment Page</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { darkMode: 'class' }
  </script>

  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    h2 { color: #1f2937; }
    .info { margin-bottom: 20px; }

    #card-element {
      border: 1px solid #d1d5db;
      padding: 12px;
      border-radius: 4px;
      margin-bottom: 12px;
      background-color: #fff;
    }

    .submit {
      background-color: #2563eb;
      color: white;
      padding: 10px 18px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }

    .submit:hover { background-color: #1e40af; }
    .success-message { color: green; margin-bottom: 20px; }
    .error-message { color: red; margin-bottom: 20px; }

    html.dark #card-element {
      background-color: #1f2937 !important;
      border-color: #374151 !important;
    }

    html.dark .submit { background-color: #3b82f6; }
    html.dark .submit:hover { background-color: #2563eb; }
    html.dark .success-message { color: #22c55e; }
    html.dark .error-message { color: #f87171; }
  </style>
</head>

<body class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 font-sans">
  @include('layouts.user_header')

  <main class="max-w-xl mx-auto mt-10 p-6 bg-white dark:bg-gray-800 rounded shadow">
    <h2 class="text-2xl font-bold mb-4 dark:text-white">Payment Page</h2>

    <div class="info">
      <p><strong>Application ID:</strong> {{ $application->application_id }}</p>
      <p><strong>Amount:</strong> RM {{ number_format($application->payment_amount, 2) }}</p>
      <p><strong>Payment Method:</strong> FPX (Online Payment)</p>
    </div>

    @if(session('success'))
    <div class="success-message">{{ session('success') }}</div>
    <script>
      setTimeout(() => {
        window.location.href = "{{ route('users.dashboard') }}";
      }, 3000);
    </script>
    @endif

    @if(session('error'))
    <div class="error-message">{{ session('error') }}</div>
    @endif

    <form action="{{ route('payments.charge') }}" method="POST" id="payment-form">
      @csrf
      <input type="hidden" name="amount" value="{{ $application->payment_amount }}">
      <input type="hidden" name="application_id" value="{{ $application->application_id }}">
      <input type="hidden" name="payment_method" value="FPX">

      <label for="card-element" class="font-semibold">Card Details</label>
      <div id="card-element"></div>
      <div id="card-errors" role="alert" class="text-red-500 mt-2"></div>

      <button class="submit mt-4" type="submit">Pay Now</button>
    </form>
  </main>

  <footer class="mt-16 text-center text-sm text-gray-500 dark:text-gray-400 p-6">
    <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
  </footer>

  <script src="https://js.stripe.com/v3/"></script>
  <script>
    const stripe = Stripe("{{ config('services.stripe.key') }}");
    const elements = stripe.elements();
    let card;

    function mountCardElement() {
      const isDarkMode = document.documentElement.classList.contains('dark');

      if (card) {
        card.unmount();
        card.destroy();
      }

      const style = {
        base: {
          iconColor: isDarkMode ? '#ffffff' : '#111827',
          color: isDarkMode ? '#ffffff' : '#f97316',
          fontFamily: 'Arial, sans-serif',
          fontSize: '16px',
          fontSmoothing: 'antialiased',
          '::placeholder': {
            color: isDarkMode ? '#9ca3af' : '#6b7280'
          }
        },
        complete: {
          color: isDarkMode ? '#ffffff' : '#111827'
        },
        empty: {
          color: isDarkMode ? '#ffffff' : '#111827'
        },
        invalid: {
          iconColor: '#ef4444',
          color: '#ef4444'
        }
      };

      // Delay to ensure clean remount
      setTimeout(() => {
        card = elements.create('card', { style });
        card.mount('#card-element');
      }, 10);
    }

    mountCardElement();

    document.getElementById('payment-form').addEventListener('submit', function (event) {
      event.preventDefault();
      const form = this;
      const cardErrors = document.getElementById('card-errors');

      stripe.createToken(card).then(function (result) {
        if (result.error) {
          cardErrors.textContent = result.error.message;
        } else {
          const hiddenInput = document.createElement('input');
          hiddenInput.setAttribute('type', 'hidden');
          hiddenInput.setAttribute('name', 'stripeToken');
          hiddenInput.setAttribute('value', result.token.id);
          form.appendChild(hiddenInput);
          form.submit();
        }
      });
    });

    // Toggle dark mode manually for testing
    function toggleDarkMode() {
      document.documentElement.classList.toggle('dark');
      mountCardElement();
    }

    function toggleNotifications() {
      const dropdown = document.getElementById('notificationDropdown');
      dropdown.classList.toggle('hidden');
    }

    function logout() {
      window.location.href = "{{ route('logout') }}";
    }
  </script>
</body>
</html>
