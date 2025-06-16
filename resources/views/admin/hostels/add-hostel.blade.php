<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Hostel Management</title>
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
          <h2 class="text-2xl font-semibold mb-6">Add New Hostel</h2>
          
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

          <form action="{{ route('hostels.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
              <label for="name" class="block text-sm font-medium text-gray-700">Hostel Name</label>
              <input type="text" name="hostel_name" id="name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
            </div>
            <div class="mb-4">
              <label for="total_blocks" class="block text-sm font-medium text-gray-700">Total Blocks</label>
              <input type="number" name="total_blocks" id="total_blocks" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
            </div>
            <div class="mb-4">
              <label for="total_rooms" class="block text-sm font-medium text-gray-700">Total Rooms</label>
              <input type="number" name="total_rooms" id="total_rooms" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
            </div>
            <div class="mb-4">
              <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
              <select name="gender" id="gender" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="mix">Mixed</option>
              </select>
            </div>
            <div class="mb-4">
              <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
              <input type="file" name="image" id="image" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200">
            </div>
            <div class="mb-4">
              <label for="facilities" class="block text-sm font-medium text-gray-700">Facilities</label>
              <textarea name="facilities" id="facilities" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" rows="4"></textarea>
            </div>
            <div class="flex justify-between">
              <button type="submit" class="btn btn-success bg-green-600 text-white font-semibold py-2 px-4 rounded hover:bg-green-700">Add Hostel</button>
              <a href="{{ route('hostels.index') }}" class="btn btn-secondary bg-gray-600 text-white font-semibold py-2 px-4 rounded hover:bg-gray-700">Cancel</a>
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

      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </div>
  </div>
</body>
</html>
