<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Package</title>
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
          <h2 class="text-2xl font-semibold mb-6">Edit Package</h2>

          {{-- Display Validation Errors --}}
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

          @if(isset($package))
          <form action="{{ route('packages.update', $package->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
              <label for="package_name" class="block text-sm font-medium text-gray-700">Package Name</label>
              <select id="package_name" name="package_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200">
                <option value="">-- Select Package --</option>
                <option value="PACKAGE I" {{ old('package_name', $package->package_name) == 'PACKAGE I' ? 'selected' : '' }}>PACKAGE I</option>
                <option value="PACKAGE II" {{ old('package_name', $package->package_name) == 'PACKAGE II' ? 'selected' : '' }}>PACKAGE II</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="details" class="block text-sm font-medium text-gray-700">Details</label>
              <textarea id="details" name="details" rows="3" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200">{{ old('details', $package->details) }}</textarea>
            </div>

            <div class="mb-4">
              <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
              <select id="category" name="category" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200">
                <option value="">-- Select Category --</option>
                @foreach(['SCHOOL STUDENTS', 'UTHM STUDENTS', 'IPTA/IPTS STUDENTS', 'UTHM PROGRAM ORGANIZERS', 'GOVT SECTOR ORGANIZERS', 'PRIVATE SECTOR ORGANIZERS', 'INTERNATIONAL STUDENTS'] as $cat)
                  <option value="{{ $cat }}" {{ old('category', $package->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-4">
              <label for="price_per_day" class="block text-sm font-medium text-gray-700">Price per Day (RM)</label>
              <input type="number" id="price_per_day" name="price_per_day" step="0.01" min="0" required
                     class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200"
                     value="{{ old('price_per_day', $package->price_per_day) }}">
            </div>

            <div class="flex justify-between">
              <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded hover:bg-green-700">
                Update Package
              </button>
              <a href="{{ route('packages.index') }}" class="bg-gray-600 text-white font-semibold py-2 px-4 rounded hover:bg-gray-700">
                Cancel
              </a>
            </div>
          </form>
          @else
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
              <p>No package data found.</p>
            </div>
          @endif
        </div>
      </main>

      <!-- Footer -->
      <footer class="mt-16 text-center text-sm text-gray-500 p-6">
        <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
      </footer>
    </div>
  </div>

  {{-- Auto-fill Package Details --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const packageSelect = document.getElementById('package_name');
      const detailsTextarea = document.getElementById('details');

      const defaultDetails = {
        'PACKAGE I': 'Without pillow, pillowcase & bedsheets',
        'PACKAGE II': 'With pillow, pillowcase & bedsheets'
      };

      function updateDetails() {
        const selectedPackage = packageSelect.value;
        const currentDetails = detailsTextarea.value.trim();
        if (
          currentDetails === '' ||
          Object.values(defaultDetails).includes(currentDetails)
        ) {
          detailsTextarea.value = defaultDetails[selectedPackage] || '';
        }
      }

      packageSelect.addEventListener('change', updateDetails);
      updateDetails(); // Set initial value
    });
  </script>
</body>
</html>
