<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>RCARS Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 font-sans">

    @include('layouts.user_header')

<!-- Welcome Banner -->
<section class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg mt-6 mx-4 md:mx-6">
    <h2 class="text-2xl font-semibold mb-2">
        Welcome to RCARS Dashboard, {{ auth()->user()->user_name }}!
    </h2>
</section>

<!-- Flash Message -->
@if (session('success'))
    <div class="mx-4 md:mx-6 mt-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-900 dark:border-green-700 dark:text-green-300" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    </div>
@endif

<!-- Featured Accommodations -->
<section class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg mt-6 mx-4 md:mx-6">
    <h2 class="text-xl font-bold mb-4">Featured Accommodations</h2>

    @if ($hostels->isEmpty())
        <p class="text-gray-700 dark:text-gray-300">No hostels available at the moment.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($hostels as $hostel)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow hover:shadow-md transition">
                    <img src="{{ asset('storage/' . $hostel->image) }}" alt="{{ $hostel->hostel_name }}" class="w-full h-40 object-cover rounded mb-2" />
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $hostel->hostel_name }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Facilities: {{ $hostel->facilities }}</p>
                    <a href="{{ route('application.form') }}" class="text-blue-500 hover:underline">Apply Now</a>
                </div>
            @endforeach
        </div>
    @endif
</section>

<!-- Application History -->
<section class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg mt-6 mx-4 md:mx-6 overflow-x-auto">
    <h2 class="text-xl font-bold mb-4">APPLICATION HISTORY</h2>

    @if ($applications->count() > 0)
        <table class="min-w-full text-left border-collapse border border-gray-300 dark:border-gray-600">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">Rental Purpose</th>
                    <th class="px-4 py-2 border">Dates</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Payment Status</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $index => $application)
                    @php
                        $status = $application->application_status;
                        $paymentStatus = optional($application->payment)->payment_status;
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-2 border">{{ $applications->firstItem() + $index }}</td>
                        <td class="px-4 py-2 border">
                            <strong>{{ $application->application_id }}</strong><br>
                            {{ $application->rental_purpose }}
                        </td>
                        <td class="px-4 py-2 border whitespace-nowrap">
    {{ optional($application->check_in_date)->format('d-m-Y') ?? 'N/A' }} <br> - <br>
    {{ optional($application->check_out_date)->format('d-m-Y') ?? 'N/A' }}
</td>
                        <td class="px-4 py-2 border">
                            <span class="px-2 py-1 rounded text-sm font-semibold
                                @if ($status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-400
                                @elseif ($status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-400
                                @elseif ($status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-400
                                @elseif ($status === 'draft') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-400
                                @endif">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border">
                            @if (in_array($status, ['pending', 'draft', 'rejected']))
                                <span class="px-2 py-1 rounded text-sm bg-gray-200 text-gray-800 font-semibold dark:bg-gray-700 dark:text-gray-300">Not Applicable</span>
                            @elseif ($application->payment_exception)
                                <span class="px-2 py-1 rounded text-sm bg-green-100 text-green-800 font-semibold dark:bg-green-900 dark:text-green-400">
                                    Fee Exception Approved — No Payment Required
                                </span>
                            @elseif ($paymentStatus)
                                <span class="px-2 py-1 rounded text-sm font-semibold
                                    @if ($paymentStatus === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-400
                                    @elseif ($paymentStatus === 'unpaid') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-400
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-400
                                    @endif">
                                    {{ ucfirst($paymentStatus) }}
                                </span>
                            @else
                                <span class="px-2 py-1 rounded text-sm bg-yellow-100 text-yellow-800 font-semibold dark:bg-yellow-900 dark:text-yellow-400">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border space-x-1">
                            @if ($status === 'draft')
                                <a href="{{ route('application.edit', $application->application_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                                <form action="{{ route('application.destroy', $application->application_id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                </form>
                            @else
                                <a href="{{ route('application.view', $application->application_id) }}" class="text-gray-600 dark:text-gray-400 hover:underline">View</a>
                                @if ($status === 'approved' && !$application->payment_exception && $paymentStatus !== 'paid')
                                    <a href="{{ route('payments.create', $application->application_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Pay Now</a>
                                @elseif ($status === 'approved')
                                    <a href="{{ route('user.quotation', $application->application_id) }}" class="text-green-600 dark:text-green-400 hover:underline">Booking Summary</a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $applications->links() }}
        </div>
    @else
        <p class="text-gray-600 dark:text-gray-400">No applications found.</p>
    @endif
</section>
<script>
    function toggleDarkMode() {
        const htmlEl = document.documentElement;
        const icon = document.getElementById('darkModeIcon');
        htmlEl.classList.toggle('dark');
        const isDark = htmlEl.classList.contains('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        icon.classList.toggle('fa-moon', !isDark);
        icon.classList.toggle('fa-sun', isDark);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const savedTheme = localStorage.getItem('theme');
        const htmlEl = document.documentElement;
        const icon = document.getElementById('darkModeIcon');
        if (savedTheme === 'dark') {
            htmlEl.classList.add('dark');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        }
    });

    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');

    notificationBtn?.addEventListener('click', function (e) {
        e.stopPropagation();
        notificationDropdown?.classList.toggle('hidden');
    });

    window.addEventListener('click', function (e) {
        if (!notificationDropdown.contains(e.target) && !notificationBtn.contains(e.target)) {
            notificationDropdown.classList.add('hidden');
        }
    });

    function logout() {
        window.location.href = "{{ route('login') }}";
    }
document.addEventListener('DOMContentLoaded', function () {
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');

    if (notificationBtn) {
        notificationBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            notificationDropdown?.classList.toggle('hidden');
        });

        window.addEventListener('click', function (e) {
            if (!notificationDropdown.contains(e.target) && !notificationBtn.contains(e.target)) {
                notificationDropdown.classList.add('hidden');
            }
        });
    }
});
</script>

</body>
</html>