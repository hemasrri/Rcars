<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Booking Quotation</title>
    <meta name="description" content="Booking summary for residential college accommodation at Universiti Tun Hussein Onn Malaysia" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #3b82f6;
            --text-color: #000;
            --background-color: #fff;
            --font-family: 'Arial', sans-serif;
        }

        body {
            font-family: var(--font-family);
            margin: 0;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px; /* Increased margin for spacing */
        }

        .print-btn, .back-btn {
            margin-top: 10px;
            padding: 8px 16px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
            margin-left: 10px; /* Space between buttons */
        }

        .print-btn:hover, .back-btn:hover {
            background-color: var(--primary-color);
        }

        hr.blue {
            border: 0;
            height: 2px;
            background-color: var(--primary-color);
            margin: 30px 0;
        }

        .centered {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo {
            width: 250px;
            margin-bottom: 10px;
        }

        h2, h3, h4, p {
            margin: 0;
        }

        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 16px;
            margin-top: 25px;
            margin-bottom: 15px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            width: 250px;
            font-weight: bold;
        }

        .info-value {
            flex: 1;
        }

        @media print {
            .print-btn,
            .back-btn,
            header,
            footer {
                display: none !important;
            }
            body {
                margin: 10mm;
            }
        }
    </style>
</head>
<body onload="autoPrintAndSetDate()">

    <!-- Top Navbar -->
    <header id="navbar" class="relative bg-white dark:bg-gray-800 shadow p-4 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/uthm.png') }}" alt="UTHM Logo" class="h-8 md:h-10">
        </div>

        <div class="flex items-center gap-6 relative">
            <!-- Dark Mode Toggle -->
            <button onclick="toggleDarkMode()" class="focus:outline-none text-gray-800 dark:text-gray-200" title="Toggle Dark Mode" aria-label="Toggle Dark Mode">
                <i id="darkModeIcon" class="fas fa-moon fa-sm"></i>
            </button>

            <!-- Notification Bell -->
            <button id="notificationBtn" onclick="toggleNotifications()" class="relative focus:outline-none text-gray-800 dark:text-gray-200" title="Notifications" aria-label="Notifications">
                <i class="fas fa-bell fa-sm"></i>
                <span class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full border-2 border-white dark:border-gray-800"></span>
            </button>

            <!-- Logout Button -->
            <button onclick="logout()" class="focus:outline-none text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-600" title="Logout" aria-label="Logout">
                <i class="fas fa-sign-out-alt fa-sm"></i>
            </button>

            <!-- Notification Dropdown Panel -->
            <div id="notificationDropdown" class="hidden absolute top-12 right-0 w-80 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-lg z-50">
                <div class="p-4 font-semibold text-gray-700 dark:text-gray-200 border-b dark:border-gray-600">
                    Notifications
                </div>
            </div>
        </div>
    </header>

    <!-- Top Bar -->
    <div class="top-bar">
        <a href="{{ route('users.dashboard') }}" class="back-btn">Back</a> <!-- Adjust the route as needed -->
        <button onclick="handlePrint()" class="print-btn" aria-label="Print Booking Summary">Print</button>
    </div>

    <!-- Header Info -->
    <div class="centered" style="display: flex; flex-direction: column; align-items: center; margin-top: 20px;">
        <img src="{{ asset('images/uthm.png') }}" alt="UTHM Logo" class="logo" style="width: 200px; margin-bottom: 10px;" />
        <h2 style="font-size: 18px;">UNIVERSITI TUN HUSSEIN ON MALAYSIA</h2>
        <h3 style="font-size: 16px;">STUDENT HOUSING CENTRE</h3>
        <p style="font-size: 14px;"><strong>BOOKING SUMMARY OF RENTAL ACCOMMODATION AT RESIDENTIAL COLLEGE</strong></p>
    </div>

    <!-- Applicant Info -->
    <section aria-labelledby="applicant-info-title" style="margin-top: 20px;">
        <h4 id="applicant-info-title" class="section-title" style="font-size: 16px;">A. Applicant Information</h4>
        <div class="info-row"><span class="info-label" style="width: 200px; font-weight: bold;">Name:</span> <span class="info-value">{{ $user->user_name ?? '-' }}</span></div>
        <div class="info-row"><span class="info-label" style="width: 200px; font-weight: bold;">National ID:</span> <span class="info-value">{{ $user->ic_number ?? 'N/A' }}</span></div>
        <div class="info-row"><span class="info-label" style="width: 200px; font-weight: bold;">Matrix Number:</span> <span class="info-value">{{ $user->user_id ?? '-' }}</span></div>
        <div class="info-row"><span class="info-label" style="width: 200px; font-weight: bold;">Phone Number:</span> <span class="info-value">{{ $user->phone ?? '-' }}</span></div>
        <div class="info-row"><span class="info-label" style="width: 200px; font-weight: bold;">Email:</span> <span class="info-value">{{ $user->email ?? '-' }}</span></div>
        <div class="info-row"><span class="info-label" style="width: 200px; font-weight: bold;">Rental Purpose:</span> <span class="info-value">{{ $application->rental_purpose ?? 'N/A' }}</span></div>
        <div class="info-row"><span class="info-label" style="width: 200px; font-weight: bold;">Check-in Date:</span> 
            <span class="info-value">
                {{ $application->check_in_date ? \Carbon\Carbon::parse($application->check_in_date)->format('d/m/Y') : '-' }}
            </span>
        </div>
        <div class="info-row"><span class="info-label" style="width: 200px; font-weight: bold;">Check-out Date:</span> 
            <span class="info-value">
                {{ $application->check_out_date ? \Carbon\Carbon::parse($application->check_out_date)->format('d/m/Y') : '-' }}
            </span>
        </div>
        <div class="info-row">
            <span class="info-label" style="width: 200px; font-weight: bold;">Package:</span>
            <span class="info-value">
                <strong>Package Name:</strong>
                {{ $package->package_name ?? 'N/A' }} + 
                {{ $package->details ?? 'N/A' }} + 
                RM{{ number_format($package->price_per_day ?? 0, 2) }} per day
                <br>
                <strong>Category:</strong> {{ $package->category ?? 'N/A' }}
            </span>
        </div>
        <div class="info-row"><span class="info-label" style="width: 200px; font-weight: bold;">Participants (Male):</span> <span class="info-value">{{ $application->male ?? '-' }}</span></div>
        <div class="info-row"><span class="info-label" style="width: 200px; font-weight: bold;">Participants (Female):</span> <span class="info-value">{{ $application->female ?? '-' }}</span></div>
        <div class="info-row"><span class="info-label" style="width: 200px; font-weight: bold;">Disabled (Male):</span> <span class="info-value">{{ $application->disabled_male ?? '-' }}</span></div>
        <div class="info-row"><span class="info-label" style="width: 200px; font-weight: bold;">Disabled (Female):</span> <span class="info-value">{{ $application->disabled_female ?? '-' }}</span></div>
    </section>

    <!-- Officer Approval -->
    <section aria-labelledby="officer-approval-title" style="margin-top: 20px;">
        <h4 id="officer-approval-title" class="section-title" style="font-size: 16px;">B. Approval of Residential College Coordinating Officer</h4>
        <div class="info-row">
            <span class="info-label" style="width: 200px; font-weight: bold;">Application Status:</span>
            <span class="info-value">{{ ucfirst($application->application_status ?? '-') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label" style="width: 200px; font-weight: bold;">Amount:</span>
            <span class="info-value">
                @if ($application->payment_exception ?? false)
                    Fee Exception Approved — No Payment Required
                @else
                    RM {{ number_format($application->payment_amount ?? 0, 2) }}
                    ({{ $application->payment->payment_status ?? 'N/A' }})
                @endif
            </span>
        </div>
        <div class="info-row"><span class="info-label" style="width: 200px; font-weight: bold;">Room Number:</span> <span class="info-value">{{ $application->room_allocation ?? '-' }}</span></div>
        <div class="info-row"><span class="info-label" style="width: 200px; font-weight: bold;">Coordinating Officer:</span> <span class="info-value">{{ $application->processed_by ?? '-' }}</span></div>
        <div class="info-row">
            <span class="info-label" style="width: 200px; font-weight: bold;">Date:</span>
            <span class="info-value">
                {{ $application->processed_at ? \Carbon\Carbon::parse($application->processed_at)->format('d/m/Y h:i A') : '-' }}
            </span>
        </div>
    </section>

    <footer class="mt-16 text-center text-sm text-gray-500 p-6" role="contentinfo">
        <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
    </footer>

    <script>
        function handlePrint() {
            const printBtn = document.querySelector('.print-btn');
            printBtn.style.display = 'none';
            window.print();
            setTimeout(() => {
                printBtn.style.display = 'inline-block';
            }, 500); // Delay to ensure print dialog appeared
        }

        function autoPrintAndSetDate() {
            // Automatically trigger print dialog
            const printBtn = document.querySelector('.print-btn');
            if (printBtn) {
                printBtn.style.display = 'none';  // Hide button during print
            }

            window.print();

            setTimeout(() => {
                if (printBtn) {
                    printBtn.style.display = 'inline-block'; // Show button back after print dialog closes
                }
            }, 500);
        }
    </script>

</body>
</html>
