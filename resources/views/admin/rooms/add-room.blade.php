<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Room</title>
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
          <h2 class="text-2xl font-semibold mb-6">Add Room</h2>

          <form action="{{ route('rooms.store') }}" method="POST">
            @csrf

            <div class="mb-4">
  <label for="block_id" class="block text-sm font-medium text-gray-700">Block</label>
  <select name="block_id" id="block_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" onchange="updateRoomNumber()" required>
    <option value="">Select Block</option>
    @foreach($blocks as $block)
      <option value="{{ $block->block_id }}">
        {{ $block->block_name }} - {{ $block->hostel->hostel_name ?? 'Unknown Hostel' }}
      </option>
    @endforeach
  </select>
</div>

            

            <div class="mb-4">
              <label for="room_number" class="block text-sm font-medium text-gray-700">Room Number</label>
              <input type="text" name="room_number" id="room_number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" placeholder="e.g., 001" required>
            </div>

            <div class="mb-4">
              <label for="floor_number" class="block text-sm font-medium text-gray-700">Floor Number</label>
              <input type="number" name="floor_number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
            </div>

            <div class="mb-4">
              <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
              <input type="number" name="capacity" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
            </div>

            <div class="mb-4">
              <label for="room_status" class="block text-sm font-medium text-gray-700">Room Status</label>
              <select name="room_status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
                <option value="available">Available</option>
                <option value="occupied">Occupied</option>
                <option value="maintenance">Maintenance</option>
              </select>
            </div>

            <div class="flex justify-between">
              <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded hover:bg-green-700">Add Room</button>
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

      <!-- Footer -->
      <footer class="mt-16 text-center text-sm text-gray-500 p-6">
        <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
      </footer>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>

      <script>
        function updateRoomNumber() {
          const blockSelect = document.getElementById('block_id');
          const roomNumberInput = document.getElementById('room_number');
          const selectedBlockID = blockSelect.value;

          if (!selectedBlockID) {
            roomNumberInput.value = '';
            return;
          }

          const userRoomNumber = prompt("Enter room number (e.g., 001):");

          if (userRoomNumber) {
            roomNumberInput.value = userRoomNumber;
          } else {
            roomNumberInput.value = '';
          }
        }
      </script>
    </div>
  </div>
</body>
</html>
