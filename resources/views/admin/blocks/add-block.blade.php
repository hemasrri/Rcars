<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Block</title>
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
      {{-- Include Header --}}
      @include('layouts.header')

      <main class="flex-grow p-6">
        <div class="container mx-auto bg-white rounded-lg shadow-md p-8 mt-5">
          <h2 class="text-2xl font-semibold mb-6">Add Block</h2>

          <form id="blockForm" method="POST" action="{{ route('blocks.store') }}">
            @csrf

            <div class="mb-4">
              <label for="hostel_id" class="block text-sm font-medium text-gray-700">Hostel Name:</label>
              <select name="hostel_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
                <option value="" disabled selected>Select Hostel</option>
                @foreach($hostels as $hostel)
                  <option value="{{ $hostel->hostel_id }}">{{ $hostel->hostel_name }}</option>
                @endforeach
              </select>
              @error('hostel_id')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label for="block_name" class="block text-sm font-medium text-gray-700">Block Name:</label>
              <input type="text" name="block_name" id="block_name" value="{{ old('block_name') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200">
              @error('block_name')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label for="gender" class="block text-sm font-medium text-gray-700">Gender:</label>
              <select name="gender" id="gender" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200">
                <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                <option value="mix" {{ old('gender') == 'mix' ? 'selected' : '' }}>Mix</option>
              </select>
              @error('gender')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label for="total_rooms" class="block text-sm font-medium text-gray-700">Total Rooms:</label>
              <input type="number" name="total_rooms" id="total_rooms" value="{{ old('total_rooms') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200">
              @error('total_rooms')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label for="total_floors" class="block text-sm font-medium text-gray-700">Total Floors:</label>
              <input type="number" name="total_floors" id="total_floors" value="{{ old('total_floors') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200">
              @error('total_floors')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded hover:bg-green-700">Add Block</button>
          </form>
        </div>
      </main>

      <!-- Footer -->
      <footer class="mt-16 text-center text-sm text-gray-500 p-6">
        <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
      </footer>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
    </div>
  </div>
</body>
</html>
