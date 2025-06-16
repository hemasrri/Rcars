<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Semester</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet"/>
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
          <h2 class="text-2xl font-semibold mb-6">Add Semester</h2>

          <form action="{{ route('semesters.store') }}" method="POST">
            @csrf

            <div class="mb-4">
              <label for="session" class="block text-sm font-medium text-gray-700">Session</label>
              <input type="text" name="session" id="session" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" placeholder="e.g., 2024/2025" required>
            </div>

            <div class="mb-4">
              <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
              <select name="semester" id="semester" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
                <option value="1">Semester 1</option>
                <option value="2">Semester 2</option>
                <option value="3">Semester 3</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
              <input type="date" name="start_date" id="start_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
            </div>

            <div class="mb-4">
              <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
              <input type="date" name="end_date" id="end_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
            </div>

            <div class="flex justify-between">
              <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded hover:bg-green-700">Create Semester</button>
              <button type="reset" class="bg-gray-600 text-white font-semibold py-2 px-4 rounded hover:bg-gray-700 ml-2">Reset</button>
            </div>
          </form>

          @if(session('success'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
              {{ session('success') }}
            </div>
          @endif

          @if($errors->any())
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
              <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>
      </main>

      {{-- Footer --}}
      <footer class="mt-16 text-center text-sm text-gray-500 p-6">
        <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
      </footer>

      {{-- Hidden Logout --}}
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
      </form>
    </div>
  </div>
</body>
</html>

