<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Hostel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <script>
    function toggleNestedLinks() {
      const nestedLinks = document.getElementById('nested-links');
      nestedLinks.classList.toggle('hidden');
    }
  </script>
  <style>
    body {
      font-family: 'Nunito', sans-serif;
      background-color: #f4f4f4;
    }
  </style>
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
          <h2 class="text-2xl font-semibold mb-6">Edit Hostel</h2>

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

          <form action="{{ route('hostels.update', $hostel->hostel_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
              <label for="hostel_name" class="block text-sm font-medium text-gray-700">Hostel Name</label>
              <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" name="hostel_name" id="hostel_name" value="{{ old('hostel_name', $hostel->hostel_name) }}" required>
            </div>

            <div class="mb-4">
              <label for="total_blocks" class="block text-sm font-medium text-gray-700">Total Blocks</label>
              <input type="number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" name="total_blocks" id="total_blocks" value="{{ old('total_blocks', $hostel->total_blocks) }}" required>
            </div>

            <div class="mb-4">
              <label for="total_rooms" class="block text-sm font-medium text-gray-700">Total Rooms</label>
              <input type="number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" name="total_rooms" id="total_rooms" value="{{ old('total_rooms', $hostel->total_rooms) }}" required>
            </div>

            <div class="mb-4">
              <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
              <select name="gender" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200">
                <option value="male" {{ $hostel->gender == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ $hostel->gender == 'female' ? 'selected' : '' }}>Female</option>
                <option value="mix" {{ $hostel->gender == 'mix' ? 'selected' : '' }}>Mix</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="image" class="block text-sm font-medium text-gray-700">Hostel Image</label>
              <input type="file" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" name="image" id="image">
              <small class="text-gray-500">Leave blank to keep the current image.</small>

              @if($hostel->image)
                <div class="mt-2">
                  <p>Current Image:</p>
                  <img src="{{ asset('storage/' . $hostel->image) }}" alt="Current Hostel Image" class="max-w-xs rounded-md">
                </div>
              @endif
            </div>

            <div class="mb-4">
              <label for="facilities" class="block text-sm font-medium text-gray-700">Facilities</label>
              <textarea class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" name="facilities" id="facilities" rows="4">{{ old('facilities', $hostel->facilities) }}</textarea>
            </div>

            <div class="flex justify-between">
              <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded hover:bg-green-700">Update Hostel</button>
              <a href="{{ route('hostels.index') }}" class="bg-gray-600 text-white font-semibold py-2 px-4 rounded hover:bg-gray-700">Cancel</a>
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
