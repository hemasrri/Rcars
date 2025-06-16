<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Package</title>
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
      {{-- Header --}}
      @include('layouts.header')

      <main class="flex-grow p-6">
        <div class="container mx-auto bg-white rounded-lg shadow-md p-8 mt-5">
          <h2 class="text-2xl font-semibold mb-6">Add New Package</h2>

          @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
              <strong class="font-bold">Whoops!</strong>
              <span class="block sm:inline">There were some problems with your input.</span>
              <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('packages.store') }}" method="POST">
            @csrf

            <div class="mb-4">
              <label for="package_name" class="block text-sm font-medium text-gray-700">Package Name</label>
              <select id="package_name" name="package_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
                <option value="">-- Select Package --</option>
                <option value="PACKAGE I">PACKAGE I - Without pillow, pillowcase & bedsheets</option>
                <option value="PACKAGE II">PACKAGE II - With pillow, pillowcase & bedsheets</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
              <select id="category" name="category" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
                <option value="">-- Select Category --</option>
                <option value="SCHOOL STUDENTS">SCHOOL STUDENTS</option>
                <option value="UTHM STUDENTS">UTHM STUDENTS</option>
                <option value="IPTA/IPTS STUDENTS">IPTA/IPTS STUDENTS</option>
                <option value="UTHM PROGRAM ORGANIZERS">UTHM PROGRAM ORGANIZERS</option>
                <option value="GOVT SECTOR ORGANIZERS">GOVT SECTOR ORGANIZERS</option>
                <option value="PRIVATE SECTOR ORGANIZERS">PRIVATE SECTOR ORGANIZERS</option>
                <option value="INTERNATIONAL STUDENTS">INTERNATIONAL STUDENTS</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="price_per_day" class="block text-sm font-medium text-gray-700">Price per Day (RM)</label>
              <input type="number" id="price_per_day" name="price_per_day" step="0.01" min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>
            </div>

            <div class="mb-4">
              <label for="details" class="block text-sm font-medium text-gray-700">Details</label>
              <textarea id="details" name="details" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200" required>Without pillow, pillowcase & bedsheets</textarea>
            </div>

            <div class="flex justify-between">
              <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded hover:bg-green-700">
                Create Package
              </button>
              <a href="{{ route('packages.index') }}" class="bg-gray-600 text-white font-semibold py-2 px-4 rounded hover:bg-gray-700">
                Cancel
              </a>
            </div>
          </form>
        </div>
      </main>

      {{-- Footer --}}
      <footer class="mt-16 text-center text-sm text-gray-500 p-6">
        <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
      </footer>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
    </div>
  </div>

  <script>
    const packageSelect = document.getElementById('package_name');
    const detailsTextarea = document.getElementById('details');

    function updateDetails() {
      if (packageSelect.value === 'PACKAGE I') {
        detailsTextarea.value = 'Without pillow, pillowcase & bedsheets';
      } else if (packageSelect.value === 'PACKAGE II') {
        detailsTextarea.value = 'With pillow, pillowcase & bedsheets';
      }
    }

    updateDetails();
    packageSelect.addEventListener('change', updateDetails);
  </script>
</body>
</html>
