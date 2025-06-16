<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Room</title>
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
          <h2 class="text-2xl font-semibold mb-6">Edit Room</h2>

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

          <form action="{{ route('rooms.update', $room->room_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
              <label for="room_number" class="block text-sm font-medium text-gray-700">Room Number</label>
              <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" id="room_number" name="room_number" value="{{ old('room_number', $room->room_number) }}" required>
            </div>

            <div class="mb-4">
              <label for="floor_number" class="block text-sm font-medium text-gray-700">Floor Number</label>
              <input type="number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" id="floor_number" name="floor_number" value="{{ old('floor_number', $room->floor_number) }}" required>
            </div>

            <div class="mb-4">
              <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
              <input type="number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" id="capacity" name="capacity" value="{{ old('capacity', $room->capacity) }}" required>
            </div>

            <div class="mb-4">
  <label for="room_status" class="block text-sm font-medium text-gray-700">Room Status</label>
  <select id="room_status" name="room_status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200">
    <option value="available" {{ old('room_status', $room->room_status) == 'available' ? 'selected' : '' }}>Available</option>
    <option value="allocate" {{ old('room_status', $room->room_status) == 'allocate' ? 'selected' : '' }}>Allocated</option>
    <option value="maintenance" {{ old('room_status', $room->room_status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
  </select>
</div>


            <div class="flex justify-between">
              <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded hover:bg-green-700">Update Room</button>
              <a href="{{ route('rooms.index') }}" class="bg-gray-600 text-white font-semibold py-2 px-4 rounded hover:bg-gray-700">Cancel</a>
            </div>
          </form>
        </div>
      </main>

      <!-- Footer -->
      <footer class="mt-16 text-center text-sm text-gray-500 p-6">
        <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
      </footer>
    </div>
  </div>
</body>
</html>
