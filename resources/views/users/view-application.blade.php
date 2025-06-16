<!DOCTYPE html>
<html lang="en" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Application Print View</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
        }

        .header-below {
            text-align: center;
            margin: 20px 0 10px;
        }

        .header-below img {
            width: 80px;
            margin: 10px auto 0;
        }

        /* Style for buttons container */
.buttons-container {
    display: flex;
    justify-content: flex-end; /* Align buttons to the right */
    flex-wrap: wrap;
    gap: 0.75rem;
    margin: 1rem 1rem 1rem auto;
}


        .section-header {
            background-color: #e5e7eb; /* light gray */
        }

        .application-conditions {
            background-color: #f9f3d2;
            border-left: 6px solid #f5b942;
        }

        /* Dark mode overrides */
        @media (prefers-color-scheme: dark) {
            .section-header {
                background-color: #374151 !important; /* dark gray */
                color: #f3f4f6 !important;
            }
            .application-conditions {
                background-color: #78350f !important; /* dark yellow-brown */
                border-left-color: #facc15 !important;
                color: #fef3c7;
            }
        }

        /* Print styles */
       @media print {
    html, body {
        background: white !important;
        color: black !important;
        padding: 20px;
        font-size: 14px;
    }

    /* Override dark mode utility classes during print */
    .dark\:bg-yellow-900,
    .dark\:text-yellow-100,
    .dark\:border-yellow-400,
    .dark\:bg-gray-900,
    .dark\:text-gray-200,
    .dark\:bg-slate-800,
    .dark\:text-slate-100 {
        background: white !important;
        color: black !important;
        border-color: black !important;
    }

    #navbar,
    header,
    footer,
    .buttons-container {
        display: none !important;
    }

    .section-header,
    .application-conditions {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        background: #f9f3d2 !important; /* light yellow background */
        color: black !important;
        border-left: 6px solid #f5b942 !important;
    }

    .header-below img {
        width: 150px !important;
        height: auto !important;
        margin: 10px auto 0;
    }
}


    </style>
</head>

<body class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 font-sans">

@include('layouts.user_header')

<!-- Header -->
<div class="header-below">
    <img src="{{ asset('images/uthm.png') }}" alt="UTHM Logo">
    <h2><strong>UNIVERSITI TUN HUSSEIN ONN MALAYSIA</strong></h2>
    <h3><strong>STUDENT HOUSING CENTRE</strong></h3>
    <p><strong>APPLICATION FOR RENTAL ACCOMMODATION AT RESIDENTIAL COLLEGE</strong></p>
</div>

<!-- Buttons -->
{{-- Buttons --}}
<div class="buttons-container no-print">
    <a href="{{ url()->previous() }}"
       class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition dark:bg-blue-600 dark:hover:bg-blue-700">
        <i class="fas fa-arrow-left mr-2"></i> Back
    </a>
    <button onclick="window.print()"
            class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition dark:bg-green-600 dark:hover:bg-green-700">
        <i class="fas fa-print mr-2"></i> Print
    </button>
</div>


<!-- Content Container -->
<div class="container mx-auto">

    <!-- Application Conditions -->
    <div class="application-conditions rounded mb-8 dark:bg-yellow-900 dark:border-yellow-400 dark:text-yellow-100 p-6">
        <h3 class="text-lg font-bold mb-2">APPLICATION CONDITIONS</h3>
        <ol class="list-decimal pl-5 space-y-1">
            <li>The applicant is responsible for the number of days requested after approval is granted...</li>
            <li>The applicant is responsible for the information provided in this form...</li>
            <li>Applicants are required to fill in the correct Residential College support email...</li>
            <li>The application must be submitted within <strong>14 days</strong> from the intended date of stay.</li>
            <li>The response period given to the applicant is within <strong>seven (7) working days</strong>.</li>
            <li>Applicants are subject to the <strong>Residential College Handbook</strong> and <strong>UTHM Student Housing Policy</strong>.</li>
        </ol>
    </div>

    <!-- Application Details -->
    <div class="section-header dark:bg-gray-700 dark:text-white">APPLICATION DETAILS</div>
    <div class="section-content p-2">
        <p><strong>Application ID:</strong> {{ $application->application_id }}</p>
        <p><strong>Submitted On:</strong> {{ \Carbon\Carbon::parse($application->created_at)->format('d-M-Y') }}</p>
    </div>

    <!-- Applicant Info -->
    <div class="section-header dark:bg-gray-700 dark:text-white">APPLICANT INFORMATION</div>
    <div class="section-content p-2 grid grid-cols-1 md:grid-cols-2 gap-2">
        <p><strong>Name:</strong> {{ $application->name }}</p>
        <p><strong>IC Number:</strong> {{ $application->ic_number }}</p>
        <p><strong>Phone:</strong> {{ $application->phone }}</p>
        <p><strong>Email:</strong> {{ $application->email }}</p>
    </div>

    <!-- Rental Info -->
    <div class="section-header dark:bg-gray-700 dark:text-white">RENTAL DETAILS</div>
    <div class="section-content p-2 grid grid-cols-1 md:grid-cols-2 gap-2">
        <p><strong>Program Title:</strong> {{ $application->rental_purpose }}</p>
        <p><strong>Check-In Date:</strong> {{ $application->check_in_date }}</p>
        <p><strong>Check-Out Date:</strong> {{ $application->check_out_date }}</p>
    </div>

    <!-- Participants -->
    <div class="section-header dark:bg-gray-700 dark:text-white">PARTICIPANT DETAILS</div>
    <div class="section-content p-2 grid grid-cols-1 md:grid-cols-3 gap-2">
        <p><strong>Total Participants:</strong> {{ $application->num_participants }}</p>
        <p><strong>Male:</strong> {{ $application->male }}</p>
        <p><strong>Female:</strong> {{ $application->female }}</p>
        <p><strong>Disabled Male:</strong> {{ $application->disabled_male }}</p>
        <p><strong>Disabled Female:</strong> {{ $application->disabled_female }}</p>
    </div>

    <!-- Package Info -->
    <div class="section-header dark:bg-gray-700 dark:text-white">PACKAGE BOOKED</div>
    <div class="section-content p-2 grid grid-cols-1 md:grid-cols-2 gap-2">
        <p>
            <strong>Package Name:</strong>
            {{ $package->package_name ?? 'N/A' }} + {{ $package->details ?? 'N/A' }} + RM{{ number_format($package->price_per_day ?? 0, 2) }} per day
        </p>
        <p><strong>Category:</strong> {{ $package->category ?? 'N/A' }}</p>
    </div>

    <!-- Document & Status -->
    <div class="section-header dark:bg-gray-700 dark:text-white">DOCUMENT & FINAL STATUS</div>
    <div class="section-content p-2 grid grid-cols-1 md:grid-cols-2 gap-2">
        <p>
            <strong>Supporting Document:</strong>
            @if($application->document_path)
                <a href="{{ asset('storage/' . $application->document_path) }}" target="_blank" class="text-blue-600 dark:text-blue-300 underline">View Document</a>
            @else
                No Document Uploaded
            @endif
        </p>
        <p>
            <strong>Final Status:</strong>
            @php
                $status = strtolower($application->application_status);
                $statusColor = match($status) {
                    'approved' => 'text-green-600 dark:text-green-400 font-semibold',
                    'rejected' => 'text-red-600 dark:text-red-400 font-semibold',
                    'pending' => 'text-yellow-600 dark:text-yellow-300 font-semibold',
                    default => 'text-gray-700 dark:text-gray-200'
                };
            @endphp
            <span class="{{ $statusColor }}">{{ ucfirst($status) }}</span>
        </p>
    </div>
</div>

<!-- Footer -->
<footer class="mt-16 text-center text-sm text-gray-500 dark:text-gray-400 p-6 no-print">
    <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
</footer>

</body>
</html>
