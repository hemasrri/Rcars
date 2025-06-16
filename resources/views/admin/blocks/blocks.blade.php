<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Block Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
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
          <h2 class="text-2xl font-semibold mb-4">Manage Blocks</h2>

          <div class="flex justify-end mb-4">
            <form method="GET" action="{{ route('blocks.index') }}" class="flex gap-2">
  <input
    type="text"
    name="search"
    value="{{ request('search') }}"
    placeholder="Search by Block Name, Gender, or Hostel Name"
    class="border border-gray-300 rounded-md p-2"
  />
  <button type="submit" class="bg-blue-600 text-white rounded-md px-4 py-2">Search</button>
</form>

          </div>

          <a href="{{ route('blocks.create') }}" class="inline-block bg-green-600 text-white rounded-md px-4 py-2 mb-4">Add Block</a>

          <table class="min-w-full bg-white border border-gray-300 rounded-lg">
            <thead>
              <tr class="bg-gray-200">
                <th class="py-2 px-4 border-b">Hostel Name</th>
                <th class="py-2 px-4 border-b">Block Name</th>
                <th class="py-2 px-4 border-b">Gender</th>
                <th class="py-2 px-4 border-b">Total Rooms</th>
                <th class="py-2 px-4 border-b">Total Floors</th>
                <th class="py-2 px-4 border-b">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($blocks as $block)
                <tr class="hover:bg-gray-100">
                  <td class="py-2 px-4 border-b">{{ $block->hostel->hostel_name ?? 'N/A' }}</td>
                  <td class="py-2 px-4 border-b">{{ $block->block_name }}</td>
                  <td class="py-2 px-4 border-b">{{ $block->gender }}</td>
                  <td class="py-2 px-4 border-b">{{ $block->total_rooms }}</td>
                  <td class="py-2 px-4 border-b">{{ $block->total_floors }}</td>
                  <td class="py-2 px-4 border-b flex space-x-2">
                    <a href="{{ route('blocks.edit', $block->block_id) }}" class="bg-orange-600 text-white rounded-md px-3 py-1 hover:bg-orange-700">Edit</a>
                    <form action="{{ route('blocks.destroy', $block->block_id) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="bg-red-600 text-white rounded-md px-3 py-1 hover:bg-red-700" onclick="return confirm('Are you sure?');">Delete</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <div class="mt-4">
            {{ $blocks->withQueryString()->links() }}
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
