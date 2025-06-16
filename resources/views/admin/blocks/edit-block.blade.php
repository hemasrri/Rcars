<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Block</title>
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
          <h2 class="text-2xl font-semibold mb-6">Edit Block</h2>

          @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
              <strong class="font-bold">Whoops!</strong>
              <span class="block sm:inline">There were some problems with your input.</span>
              <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('blocks.update', $block->block_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
              <label for="hostel_id" class="block text-sm font-medium text-gray-700">Hostel Name</label>
              <select name="hostel_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
                <option value="" disabled>Select Hostel</option>
                @foreach($hostels as $hostel)
                  <option value="{{ $hostel->hostel_id }}" {{ $block->hostel_id == $hostel->hostel_id ? 'selected' : '' }}>{{ $hostel->hostel_name }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-4">
              <label for="block_name" class="block text-sm font-medium text-gray-700">Block Name</label>
              <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" name="block_name" id="block_name" value="{{ old('block_name', $block->block_name) }}" required>
            </div>

            <div class="mb-4">
              <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
              <select name="gender" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
                <option value="male" {{ $block->gender == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ $block->gender == 'female' ? 'selected' : '' }}>Female</option>
                <option value="mix" {{ $block->gender == 'mix' ? 'selected' : '' }}>Mix</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="total_rooms" class="block text-sm font-medium text-gray-700">Total Rooms</label>
              <input type="number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" name="total_rooms" id="total_rooms" value="{{ old('total_rooms', $block->total_rooms) }}" required>
            </div>

            <div class="mb-4">
              <label for="total_floors" class="block text-sm font-medium text-gray-700">Total Floors</label>
              <input type="number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" name="total_floors" id="total_floors" value="{{ old('total_floors', $block->total_floors) }}">
            </div>

            <div class="flex justify-between">
              <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded hover:bg-green-700">Update Block</button>
              <a href="{{ route('blocks.index') }}" class="bg-gray-600 text-white font-semibold py-2 px-4 rounded hover:bg-gray-700">Cancel</a>
            </div>
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
