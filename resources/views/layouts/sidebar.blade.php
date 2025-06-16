<aside class="flex flex-col bg-[#e9e7e7] w-64 text-gray-800">
  {{-- Sidebar Header --}}
  <div class="flex flex-col items-center px-6 py-6 border-b border-gray-300 text-center space-y-4">
    <div class="logo-wrapper">
      <img src="{{ asset('images/uthm.png') }}" alt="UTHM Logo" class="h-10 w-auto mx-auto" />
    </div>
    <div class="text-sm leading-tight">
      <p class="font-semibold"><strong>Administrator:</strong></p>
      <p class="text-sm">{{ Auth::guard('admin')->user()->staff_name }}</p>
      <p class="text-xs text-gray-500 mt-1">
        Last login:<br>
        {{ optional(Auth::guard('admin')->user()->last_login_at)->format('d.m.y h:i A') }}
      </p>
    </div>
  </div>

  {{-- Navigation --}}
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded text-gray-700 hover:text-white hover:bg-[#2a52be] transition">
      <i class="fas fa-tachometer-alt text-yellow-400"></i> <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.applications.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-gray-700 hover:text-white hover:bg-[#2a52be] transition">
      <i class="fas fa-file-alt text-orange-600"></i> <span>Application</span>
    </a>
    <button onclick="toggleNestedLinks()" class="flex items-center gap-3 px-3 py-2 rounded text-gray-700 hover:text-white hover:bg-[#2a52be] transition w-full text-left">
      <i class="fas fa-building text-yellow-500"></i> <span>Hostel Management</span>
      <i class="fas fa-caret-down ml-auto text-gray-700"></i>
    </button>
    <div class="nested pl-8 space-y-1 hidden" id="nested-links">
      <a href="{{ route('hostels.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-gray-700 hover:text-white hover:bg-[#2a52be] transition">
        <i class="fas fa-hotel text-purple-600"></i> Hostels
      </a>
      <a href="{{ route('blocks.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-gray-700 hover:text-white hover:bg-[#2a52be] transition">
        <i class="fas fa-th text-purple-600"></i> Blocks
      </a>
      <a href="{{ route('rooms.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-gray-700 hover:text-white hover:bg-[#2a52be] transition">
        <i class="fas fa-bed text-purple-600"></i> Rooms
      </a>
    </div>
    <a href="{{ route('packages.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-gray-700 hover:text-white hover:bg-[#2a52be] transition">
      <i class="fas fa-box text-blue-600"></i> <span>Packages</span>
    </a>
    <a href="{{ route('payments.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-gray-700 hover:text-white hover:bg-[#2a52be] transition">
      <i class="fas fa-credit-card text-blue-500"></i> <span>Payments</span>
    </a>
    <a href="{{ route('semesters.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-gray-700 hover:text-white hover:bg-[#2a52be] transition">
      <i class="fas fa-calendar-alt text-green-600"></i> <span>Semester Management</span>
    </a>
    <a href="{{ route('admin.user-management') }}" class="flex items-center gap-3 px-3 py-2 rounded text-gray-700 hover:text-white hover:bg-[#2a52be] transition">
      <i class="fas fa-users-cog text-purple-600"></i> <span>User Management</span>
    </a>
  </nav>
</aside>
