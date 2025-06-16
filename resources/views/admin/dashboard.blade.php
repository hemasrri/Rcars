<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Admin Dashboard - RCARS</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
    body { font-family: 'Nunito', sans-serif; }
  </style>
  <script>
    function toggleNestedLinks() {
      const nestedLinks = document.getElementById('nested-links');
      nestedLinks.classList.toggle('hidden');
    }
  </script>
</head>
<body class="bg-gray-50">
  <div class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col">
      {{-- Include Header --}}
      @include('layouts.header')

      {{-- Dashboard Content --}}
      <main class="p-6 space-y-6 overflow-auto">
        {{-- Hostel Summary --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          @foreach ($hostels as $hostel)
            <div class="bg-white rounded-xl shadow p-4 space-y-4">
              <div class="text-yellow-500 text-xl text-center">
                <i class="fas fa-building"></i>
              </div>
              <div class="text-center">
                <span class="font-semibold text-gray-900 select-none">
                  {{ $hostel->hostel_name }}
                </span>
              </div>

              <div class="grid grid-cols-3 gap-2 text-center text-sm">
                {{-- Available --}}
                <div class="space-y-1">
                  <i class="fas fa-bed text-purple-600 text-xl"></i>
                  <div class="text-gray-900 font-bold select-none">{{ $hostel->available_rooms }}</div>
                  <div class="text-gray-500 text-xs font-medium whitespace-nowrap">Available</div>
                </div>

                {{-- Maintenance --}}
                <div class="space-y-1">
                  <i class="fas fa-wrench text-purple-600 text-xl"></i>
                  <div class="text-gray-900 font-bold select-none">{{ $hostel->maintenance_rooms }}</div>
                  <div class="text-gray-500 text-xs font-medium whitespace-nowrap">Maintenance</div>
                </div>

                {{-- Allocated --}}
                <div class="space-y-1">
                  <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                  <div class="text-gray-900 font-bold select-none">{{ $hostel->allocated_rooms }}</div>
                  <div class="text-gray-500 text-xs font-medium whitespace-nowrap">Allocated</div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        {{-- Stats Section --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
          <div class="bg-yellow-100 rounded shadow p-4 flex flex-col items-center space-y-2">
            <i class="fas fa-hourglass-half text-yellow-500 text-3xl"></i>
            <p class="text-gray-600 text-xl font-normal select-none">{{ $pendingApplicationsCount }}</p>
            <p class="text-gray-400 text-xs select-none">Pending Applications</p>
          </div>
          <div class="bg-green-100 rounded shadow p-4 flex flex-col items-center space-y-2">
            <i class="fas fa-user-plus text-green-600 text-3xl"></i>
            <p class="text-gray-600 text-xl font-normal select-none">{{ $newApplicationsCount }}</p>
            <p class="text-gray-400 text-xs select-none">New Applications (Last 30 Days)</p>
          </div>
          <div class="bg-red-100 rounded shadow p-4 flex flex-col items-center space-y-2">
            <i class="fas fa-times-circle text-red-500 text-3xl"></i>
            <p class="text-gray-600 text-xl font-normal select-none">{{ $rejectedApplicationsCount }}</p>
            <p class="text-gray-400 text-xs select-none">Rejected Applications</p>
          </div>
          <div class="bg-purple-100 rounded shadow p-4 flex flex-col items-center space-y-2">
            <i class="fas fa-coins text-purple-600 text-3xl"></i>
            <p class="text-gray-600 text-xl font-normal select-none">RM{{ $totalRevenue }}</p>
            <p class="text-gray-400 text-xs select-none">Total Revenue</p>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Footer -->
  <footer class="mt-16 text-center text-sm text-gray-500 dark:text-gray-400 p-6">
    <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
  </footer>
</body>
</html>
