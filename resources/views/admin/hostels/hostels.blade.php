<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Hostel Management</title>
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
      {{-- Include Header --}}
      @include('layouts.header')

      {{-- Dashboard Content --}}
      <main class="p-6 space-y-6 overflow-auto">
        <div class="container mx-auto bg-white rounded-lg shadow-md p-6">
          <h2 class="text-2xl font-semibold mb-4">Manage Hostels</h2>

          <div class="flex justify-end mb-4">
            <form action="{{ route('hostels.index') }}" method="GET" class="flex gap-2">
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Hostel Name" class="border border-gray-300 rounded-md p-2">
              <button type="submit" class="bg-blue-600 text-white rounded-md px-4 py-2">Search</button>
            </form>
          </div>

          <a href="{{ route('hostels.create') }}" class="inline-block bg-green-600 text-white rounded-md px-4 py-2 mb-4">Add Hostel</a>

          <table class="min-w-full bg-white border border-gray-300 rounded-lg">
            <thead>
              <tr class="bg-gray-200">
                <th class="py-2 px-4 border-b">Hostel Name</th>
                <th class="py-2 px-4 border-b">Total Blocks</th>
                <th class="py-2 px-4 border-b">Total Rooms</th>
                <th class="py-2 px-4 border-b">Gender</th>
                <th class="py-2 px-4 border-b">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($hostels as $hostel)
                <tr class="hover:bg-gray-100">
                  <td class="py-2 px-4 border-b">{{ $hostel->hostel_name }}</td>
                  <td class="py-2 px-4 border-b">{{ $hostel->total_blocks }}</td>
                  <td class="py-2 px-4 border-b">{{ $hostel->total_rooms }}</td>
                  <td class="py-2 px-4 border-b">{{ ucfirst($hostel->gender) }}</td>
                  <td class="py-2 px-4 border-b flex space-x-2">
                    <a href="{{ route('hostels.edit', $hostel->hostel_id) }}" class="bg-orange-600 text-white rounded-md px-3 py-1 hover:bg-orange-700">Edit</a>
                    <form action="{{ route('hostels.destroy', $hostel->hostel_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this hostel?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="bg-red-600 text-white rounded-md px-3 py-1 hover:bg-red-700">Delete</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-4 text-gray-500">No hostels found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>

          <div class="mt-4">
            {{ $hostels->links() }}
          </div>
        </div>
      </main>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>

      <!-- Footer -->
      <footer class="mt-16 text-center text-sm text-gray-500 p-6">
        <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
      </footer>
    </div>
  </div>
</body>
</html>
