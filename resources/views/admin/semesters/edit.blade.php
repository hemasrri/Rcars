@php
    /** @var \App\Models\Semester $semester */
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Semester</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Nunito', sans-serif;
      background-color: #f4f4f4;
    }
  </style>
  <script>
    function toggleNestedLinks() {
      const nestedLinks = document.getElementById('nested-links');
      nestedLinks.classList.toggle('hidden');
    }
  </script>
</head>
<body>
  <div class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col">
      {{-- Header --}}
      @include('layouts.header')

      <main class="flex-grow p-6">
        <div class="container mx-auto bg-white rounded-lg shadow-md p-8 mt-5">
          <h2 class="text-2xl font-semibold mb-6">Edit Semester</h2>

          {{-- Validation Errors --}}
          @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
              <strong class="font-bold">Whoops!</strong>
              <span class="block sm:inline">There were some problems with your input.</span>
              <ul class="list-disc pl-5 mt-2">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          @if(isset($semester))
          <form action="{{ route('semesters.update', optional($semester)->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
              <label for="session" class="block text-sm font-medium text-gray-700">Session</label>
              <input type="text" name="session" id="session"
                     value="{{ old('session', optional($semester)->session) }}"
                     class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200"
                     required>
            </div>

            <div class="mb-4">
              <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
              <input type="text" name="semester" id="semester"
                     value="{{ old('semester', optional($semester)->semester) }}"
                     class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200"
                     required>
            </div>

            <div class="mb-4">
              <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
              <input type="date" name="start_date" id="start_date"
                     value="{{ old('start_date', optional($semester)?->start_date ? \Carbon\Carbon::parse($semester->start_date)->format('Y-m-d') : '') }}"
                     class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200"
                     required>
            </div>

            <div class="mb-4">
              <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
              <input type="date" name="end_date" id="end_date"
                     value="{{ old('end_date', optional($semester)?->end_date ? \Carbon\Carbon::parse($semester->end_date)->format('Y-m-d') : '') }}"
                     class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200"
                     required>
            </div>

            <div class="flex justify-between mt-6">
              <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded hover:bg-green-700">
                Update Semester
              </button>
              <a href="{{ route('semesters.index') }}" class="bg-gray-600 text-white font-semibold py-2 px-4 rounded hover:bg-gray-700">
                Cancel
              </a>
            </div>
          </form>
          @else
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
              <p>No semester data found.</p>
            </div>
          @endif
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
