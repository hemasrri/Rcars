<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet" />
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
    }
    .modal {
      display: none;
      position: fixed;
      z-index: 50;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
      background-color: #fff;
      margin: 5% auto;
      padding: 1.5rem;
      border-radius: 0.5rem;
      width: 90%;
      max-width: 500px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .close {
      float: right;
      font-size: 1.5rem;
      font-weight: bold;
      cursor: pointer;
      color: #888;
    }
    .close:hover {
      color: #444;
    }
    .form-input {
      width: 100%;
      padding: 0.5rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 0.375rem;
      font-size: 1rem;
    }
  </style>
</head>
<body class="bg-gray-50">

@php $loggedInAdminId = Auth::guard('admin')->user()->staff_id; @endphp

<div class="flex min-h-screen">
  @include('layouts.sidebar')

  <div class="flex-1 flex flex-col">
    @include('layouts.header')

    <main class="p-6 space-y-6 overflow-auto">
      <div class="container mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold mb-4">Admin Management</h2>

        {{-- Flash Messages --}}
        @if(session('success'))
          <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
          </div>
        @endif

        @if(session('error'))
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="mb-4 text-right">
  <button onclick="document.getElementById('addUserModal').style.display='block'" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
    <i class="fas fa-user-plus mr-1"></i> Add Admin
  </button>
</div>
        <table class="w-full border-collapse">
          <thead>
            <tr>
              <th class="bg-gray-100 text-left px-4 py-2">Staff ID</th>
              <th class="bg-gray-100 text-left px-4 py-2">Staff Name</th>
              <th class="bg-gray-100 text-left px-4 py-2">Email</th>
              <th class="bg-gray-100 text-left px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $user)
            <tr class="border-b">
              <td class="px-4 py-2">{{ $user->staff_id }}</td>
              <td class="px-4 py-2">{{ $user->staff_name }}</td>
              <td class="px-4 py-2">{{ $user->email }}</td>
              <td class="px-4 py-2 flex flex-wrap gap-2">
                <form action="{{ route('user.delete', $user->staff_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded">Delete</button>
                </form>
                @if ($user->staff_id === $loggedInAdminId)
                <button onclick="handleEditInfo(this)" data-staff-id="{{ $user->staff_id }}" data-staff-name="{{ $user->staff_name }}" data-email="{{ $user->email }}" class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded">Edit Info</button>
                <button onclick="handleChangePassword(this)" data-staff-id="{{ $user->staff_id }}" class="bg-indigo-500 hover:bg-indigo-700 text-white px-3 py-1 rounded">Change Password</button>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="4" class="text-center py-4 text-gray-500">No users found.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </main>

    <footer class="text-center text-sm text-gray-500 p-6">
      <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
    </footer>
  </div>
</div>

<!-- Edit Info Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('editModal')">&times;</span>
    <h2 class="text-xl font-bold mb-4">Edit Admin Info</h2>
    <form id="editForm" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" name="staff_id" id="editStaffId" />
      <label for="editStaffName" class="block text-sm font-medium">Name</label>
      <input type="text" id="editStaffName" name="staff_name" class="form-input" required />

      <label for="editEmail" class="block text-sm font-medium">Email</label>
      <input type="email" id="editEmail" name="email" class="form-input" required />

      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        Save Changes
      </button>
    </form>
  </div>
</div>

<!-- Change Password Modal -->
<div id="passwordModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('passwordModal')">&times;</span>
    <h2 class="text-xl font-bold mb-4">Change Password</h2>
    <form id="passwordForm" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" name="staff_id" id="passwordStaffId" />

      <label for="newPassword" class="block text-sm font-medium">New Password</label>
      <input type="password" id="newPassword" name="new_password" class="form-input" required />

      <label for="confirmPassword" class="block text-sm font-medium">Confirm Password</label>
      <input type="password" id="confirmPassword" name="new_password_confirmation" class="form-input" required />

      <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
        Change Password
      </button>
    </form>
  </div>
</div>
<!-- Add Admin Modal -->
<div id="addUserModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('addUserModal')">&times;</span>
    <h2 class="text-xl font-bold mb-4">Add New Admin</h2>
    <form method="POST" action="{{ route('user.store') }}">
      @csrf
      <label for="newStaffId" class="block text-sm font-medium">Staff ID</label>
      <input type="text" id="newStaffId" name="staff_id" class="form-input" required />

      <label for="newStaffName" class="block text-sm font-medium">Name</label>
      <input type="text" id="newStaffName" name="staff_name" class="form-input" required />

      <label for="newEmail" class="block text-sm font-medium">Email</label>
      <input type="email" id="newEmail" name="email" class="form-input" required />

      <label for="newPassword" class="block text-sm font-medium">Password</label>
      <input type="password" id="newPassword" name="password" class="form-input" required />

      <label for="confirmNewPassword" class="block text-sm font-medium">Confirm Password</label>
      <input type="password" id="confirmNewPassword" name="password_confirmation" class="form-input" required />

      <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
        Create Admin
      </button>
    </form>
  </div>
</div>
<!-- Footer -->
      <footer class="mt-16 text-center text-sm text-gray-500 p-6">
        <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
      </footer>
<!-- JS for Modals -->
<script>
  function handleEditInfo(button) {
    const staffId = button.getAttribute('data-staff-id');
    const staffName = button.getAttribute('data-staff-name');
    const email = button.getAttribute('data-email');

    document.getElementById('editStaffId').value = staffId;
    document.getElementById('editStaffName').value = staffName;
    document.getElementById('editEmail').value = email;
    document.getElementById('editForm').action = `/admin/user/${staffId}/update`;

    document.getElementById('editModal').style.display = 'block';
  }

  function handleChangePassword(button) {
    const staffId = button.getAttribute('data-staff-id');

    document.getElementById('passwordStaffId').value = staffId;
    document.getElementById('passwordForm').action = `/admin/user/${staffId}/change-password`;

    document.getElementById('passwordModal').style.display = 'block';
  }

  function closeModal(id) {
    document.getElementById(id).style.display = 'none';
  }

  window.onclick = function (event) {
    ['editModal', 'passwordModal'].forEach(id => {
      const modal = document.getElementById(id);
      if (event.target === modal) {
        modal.style.display = "none";
      }
    });
  }
  window.onclick = function (event) {
  ['editModal', 'passwordModal', 'addUserModal'].forEach(id => {
    const modal = document.getElementById(id);
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
}
</script>

</body>
</html>
