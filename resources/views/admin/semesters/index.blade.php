<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Semester Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet" />
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

      {{-- Content --}}
      <main class="p-6 space-y-6 overflow-auto">
        <div class="container mx-auto bg-white rounded-lg shadow-md p-6">
          <h2 class="text-2xl font-semibold mb-4">Semester Management</h2>

          @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
              {{ session('success') }}
            </div>
          @endif

          {{-- Add Button --}}
          <div class="flex justify-end mb-4">
            <a href="{{ route('semesters.create') }}" class="bg-green-600 text-white rounded-md px-4 py-2 hover:bg-green-700">
              <i class="fas fa-plus mr-2"></i> Add New Semester
            </a>
          </div>

          {{-- Table --}}
          <table class="min-w-full bg-white border border-gray-300 rounded-lg">
            <thead class="bg-gray-200">
              <tr>
                <th class="py-2 px-4 border-b text-left">Session</th>
                <th class="py-2 px-4 border-b text-left">Semester</th>
                <th class="py-2 px-4 border-b text-left">Start Date</th>
                <th class="py-2 px-4 border-b text-left">End Date</th>
                <th class="py-2 px-4 border-b text-left">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($semesters as $semester)
                <tr class="hover:bg-gray-100">
                  <td class="py-2 px-4 border-b">{{ $semester->session }}</td>
                  <td class="py-2 px-4 border-b">{{ $semester->semester }}</td>
                  <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($semester->start_date)->format('d/m/Y') }}</td>
<td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($semester->end_date)->format('d/m/Y') }}</td>

                  <td class="py-2 px-4 border-b flex space-x-2">
                    <a href="{{ route('semesters.edit', $semester->id) }}" class="bg-orange-600 text-white px-3 py-1 rounded-md hover:bg-orange-700">
                      Edit
                    </a>
                    <form action="{{ route('semesters.destroy', $semester->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this semester??');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700">
                        Delete
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-4 text-gray-500">No semesters found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>

          {{-- Pagination --}}
          <div class="mt-4">
            {{ $semesters->appends(request()->query())->links() }}
          </div>
        </div>
      </main>

      {{-- Hidden Logout --}}
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
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
