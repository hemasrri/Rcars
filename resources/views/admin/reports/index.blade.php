<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>System Reports</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

  <style>
    body {
      font-family: 'Nunito', sans-serif;
    }
  </style>
   <script>
    function toggleNestedLinks() {
      const nestedLinks = document.getElementById('nested-links');
      nestedLinks.classList.toggle('hidden');
    }
  </script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
  <div class="flex flex-col md:flex-row min-h-screen">
    {{-- Sidebar --}}
    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col">
      {{-- Header --}}
      @include('layouts.header')

      <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold mb-6">📊 System Reports</h2>

        {{-- Semester Filter --}}
        <section class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
          <h3 class="text-xl font-semibold text-purple-700 dark:text-purple-400 mb-4">📅 Filter by Semester</h3>
          <form method="GET" action="{{ route('admin.reports') }}">
            <div class="w-full md:w-1/2 lg:w-1/3">
              <label for="session_semester" class="block mb-1 font-medium">Semester</label>
              <select name="session_semester" id="session_semester" class="w-full border-gray-300 dark:border-gray-600 rounded p-2 dark:bg-gray-700 dark:text-white">
                <option value="">-- Select Semester --</option>
                @foreach ($semesterOptions as $value => $label)
                  <option value="{{ $value }}" {{ request('session_semester') == $value ? 'selected' : '' }}>
                    {{ $label }}
                  </option>
                @endforeach
              </select>
            </div>
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
              Filter Reports
            </button>
          </form>

          @if(request('session_semester') && isset($semesterOptions[request('session_semester')]))
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-4">
              Showing report for semester: <strong>{{ $semesterOptions[request('session_semester')] }}</strong>
            </p>
          @else
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-4">
              No semester selected or semester not found.
            </p>
          @endif

          <div class="bg-yellow-100 dark:bg-yellow-200 text-yellow-900 p-4 rounded mt-4">
            Please select a semester to view specific report data.
          </div>
        </section>

        {{-- Extract session/semester --}}
        @php
          $session = $semester = null;
          if (!empty($selectedSemester) && str_contains($selectedSemester, '-')) {
            [$session, $semester] = explode('-', $selectedSemester);
          }
        @endphp


    

        {{-- 📋 Application Summary --}}
        <section class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
          <h3 class="text-xl font-semibold text-blue-700 dark:text-blue-400 mb-4">📋 Application Summary</h3>
          <p>Total Applications: <strong>{{ $totalApplications ?? 0 }}</strong></p>
          <ul class="list-disc list-inside mt-2">
            <li>✅ Approved: {{ $approved ?? 0 }}</li>
            <li>❌ Rejected: {{ $rejected ?? 0 }}</li>
            <li>⏳ Pending: {{ $pending ?? 0 }}</li>
          </ul>

          @if ($session && $semester)
            <a href="{{ route('admin.export.applications', ['session' => str_replace('/', '-', $session), 'semester' => $semester]) }}"
               class="inline-block mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
              <i class="fas fa-file-excel mr-2"></i>Export Applications (Excel)
            </a>
          @endif
        </section>

        {{-- 💳 Payment Summary --}}
        <section class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
          <h3 class="text-xl font-semibold text-green-700 dark:text-green-400 mb-4">💳 Payment Summary</h3>
          <p>Total Payment Collected: <strong>RM {{ number_format($totalPaid ?? 0, 2) }}</strong></p>
          <ul class="list-disc list-inside mt-2">
            <li>✅ Paid: {{ $paidCount ?? 0 }}</li>
            <li>🕒 Pending: {{ $pendingCount ?? 0 }}</li>
          </ul>

          @if ($session && $semester)
            <a href="{{ route('admin.export.payments', ['session' => str_replace('/', '-', $session), 'semester' => $semester]) }}"
               class="inline-block mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
              <i class="fas fa-file-excel mr-2"></i>Export Payments (Excel)
            </a>
          @endif
        </section>
      </main>
    </div>
  </div>
</body>
</html>
