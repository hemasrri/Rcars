<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Package Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Nunito', sans-serif;
    }
  </style>
  <script>
    function toggleNestedLinks() {
      const nestedLinks = document.getElementById('nested-links');
      nestedLinks.classList.toggle('hidden');
    }
  </script>
</head>
<body class="bg-gray-50">
  <div class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col">
      {{-- Header --}}
      @include('layouts.header')

      <main class="p-6 space-y-6 overflow-auto">
        <div class="container mx-auto bg-white rounded-lg shadow-md p-6">
          <h2 class="text-2xl font-semibold mb-4">Manage Packages</h2>

          {{-- Success Message --}}
          @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
              {{ session('success') }}
            </div>
          @endif

          {{-- Search & Add Button --}}
          <div class="flex flex-wrap justify-between gap-4 mb-4">
            <form method="GET" action="{{ route('packages.index') }}" class="flex flex-wrap gap-2 items-center">
              <input type="text" name="search" placeholder="Search Package" value="{{ request()->get('search') }}"
                     class="border border-gray-300 rounded-md p-2" />

              <select name="category" class="border border-gray-300 rounded-md p-2">
                <option value="">All Categories</option>
                @foreach($packages->pluck('category')->unique() as $cat)
                  <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                @endforeach
              </select>

              <button type="submit" class="bg-blue-600 text-white rounded-md px-4 py-2">Filter</button>
            </form>

            <a href="{{ route('packages.create') }}" class="bg-green-600 text-white rounded-md px-4 py-2">Add Package</a>
          </div>

          {{-- Package Table --}}
          <table class="min-w-full bg-white border border-gray-300 rounded-lg">
            <thead>
              <tr class="bg-gray-200">
                <th class="py-2 px-4 border-b">Package</th>
                <th class="py-2 px-4 border-b">Details</th>
                <th class="py-2 px-4 border-b">Price per Day</th>
                <th class="py-2 px-4 border-b">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($packages as $package)
                <tr class="hover:bg-gray-100">
                  <td class="py-2 px-4 border-b font-medium">
                    {{ $package->package_name }}<br><span class="text-sm text-gray-500">{{ ucfirst($package->category) }}</span>
                  </td>
                  <td class="py-2 px-4 border-b">{{ $package->details }}</td>
                  <td class="py-2 px-4 border-b text-green-600 font-semibold">RM{{ number_format($package->price_per_day, 2) }}</td>
                  <td class="py-2 px-4 border-b flex space-x-2">
                    <a href="{{ route('packages.edit', $package->id) }}" class="bg-yellow-500 text-white rounded-md px-3 py-1 hover:bg-yellow-600">Edit</a>
                    <form action="{{ route('packages.destroy', $package->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="bg-red-600 text-white rounded-md px-3 py-1 hover:bg-red-700">Delete</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center py-4 text-gray-500">No packages found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>

          {{-- Pagination --}}
          <div class="mt-4">
            {{ $packages->appends(request()->query())->links() }}
          </div>
        </div>
      </main>

      {{-- Hidden Logout --}}
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>

      {{-- Footer --}}
      <footer class="mt-16 text-center text-sm text-gray-500 p-6">
        <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
      </footer>
    </div>
  </div>
</body>
</html>
