<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment Review</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
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
    {{-- Header --}}
    @include('layouts.header')

    <main class="p-6 space-y-6 overflow-auto">
      <div class="container mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold mb-4">Payments</h2>

        {{-- Success Message --}}
        @if(session('success'))
          <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
          </div>
        @endif

        {{-- Filter Form --}}
<form method="GET" action="{{ route('payments.index') }}" class="flex flex-wrap gap-4 mb-4">
    <input type="date" name="start_date" class="border border-gray-300 rounded-md p-2"
           value="{{ request('start_date') }}" placeholder="Start Date">

    <input type="date" name="end_date" class="border border-gray-300 rounded-md p-2"
           value="{{ request('end_date') }}" placeholder="End Date">

    <select name="status" class="border border-gray-300 rounded-md p-2">
        <option value="">Select Status</option>
        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
    </select>

    <select name="session_semester" id="session_semester" class="border border-gray-300 rounded-md p-2">
        <option value="">Select Semester</option>
        @foreach($semesterOptions as $value => $label)
            <option value="{{ $value }}" {{ request('session_semester') == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>

    <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        Filter
    </button>
</form>

        {{-- Export Buttons --}}
<div class="flex gap-4 mb-4">
  {{-- Export to Excel --}}
  <a href="{{ route('payments.export.excel', ['session_semester' => request('session_semester'), 'status' => request('status'), 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
     class="inline-block bg-green-600 text-white font-semibold px-5 py-2 rounded-md shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition">
    <i class="fas fa-file-excel mr-2"></i> Export to Excel
  </a>

  {{-- Export to PDF --}}
  <a href="{{ route('payments.export.pdf', request()->query()) }}"
     class="inline-block bg-red-600 text-white font-semibold px-5 py-2 rounded-md shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition">
    <i class="fas fa-file-pdf mr-2"></i> Export to PDF
  </a>
</div>



        {{-- Payment Table --}}
        <table class="min-w-full bg-white border border-gray-300 rounded-lg">
          <thead>
            <tr class="bg-gray-200">
              <th class="py-2 px-4 border-b">No.</th>
              <th class="py-2 px-4 border-b">Session/Semester</th>
              <th class="py-2 px-4 border-b">Application</th>
              <th class="py-2 px-4 border-b">Status</th>
              <th class="py-2 px-4 border-b">Amount (RM)</th>
            </tr>
          </thead>
        <tbody>
  @foreach($payments as $index => $payment)
    <tr class="hover:bg-gray-100 text-center">
      <td class="py-2 px-4 border-b">{{ $payments->firstItem() + $index }}</td>
      <td class="py-2 px-4 border-b">{{ $payment->session }}/{{ $payment->semester }}</td>
      <td class="py-2 px-4 border-b text-left">
        {{ $payment->application->application_id }}<br>
        <span class="text-sm text-gray-500">{{ $payment->application->name }}</span>
      </td>
      <td class="py-2 px-4 border-b">
  <span class="@if(strtolower($payment->payment_status) == 'paid') text-green-600 font-semibold
                @elseif(strtolower($payment->payment_status) == 'pending') text-yellow-600 font-semibold
                @elseif(strtolower($payment->payment_status) == 'exception') text-red-600 font-semibold
                @endif">
    {{ ucfirst($payment->payment_status) }}
  </span>
  @if(strtolower($payment->payment_status) == 'paid')
    <div class="text-sm text-gray-600 mt-1">
      {{ \Carbon\Carbon::parse($payment->payment_datetime)->format('Y-m-d H:i:s') }}<br>
      {{ $payment->transaction_id }}<br>
      {{ ucfirst($payment->payment_method) }}
    </div>
  @endif
</td>

      <td class="py-2 px-4 border-b text-green-600 font-semibold">{{ number_format($payment->amount, 2) }}</td>
    </tr>
  @endforeach
</tbody>


</table>
        {{-- Pagination --}}
        <div class="mt-4">
  {{ $payments->withQueryString()->links() }}
</div>
      </div>
    </main>

    {{-- Footer --}}
    <footer class="mt-16 text-center text-sm text-gray-500 p-6">
      <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
    </footer>
  </div>
</div>
</body>
</html>
