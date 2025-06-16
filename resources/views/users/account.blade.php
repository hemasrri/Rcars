<!DOCTYPE html>
<html lang="en" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Update Account</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        };
    </script>

    <!-- jQuery and FontAwesome -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 min-h-screen">

    @include('layouts.user_header')

    <div class="max-w-2xl mx-auto mt-10 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg space-y-8">

        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('users.dashboard') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded-md shadow transition">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>

        <!-- Page Title -->
        <h1 class="text-2xl font-bold text-center">Update Your Account</h1>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 p-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 p-3 rounded-md">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Update Profile Form -->
        <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="user_name" class="block font-semibold">Name</label>
                <input type="text" id="user_name" name="user_name"
                       value="{{ old('user_name', $user->user_name ?? $user->name) }}" required
                       class="w-full mt-1 p-2 rounded-md border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="email" class="block font-semibold">Email</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email', $user->email) }}" required
                       class="w-full mt-1 p-2 rounded-md border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="phone" class="block font-semibold">Phone</label>
                <input type="text" id="phone" name="phone"
                       value="{{ old('phone', $user->phone) }}"
                       class="w-full mt-1 p-2 rounded-md border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="ic_number" class="block font-semibold">IC Number</label>
                <input type="text" id="ic_number" name="ic_number"
                       value="{{ old('ic_number', $user->ic_number) }}"
                       class="w-full mt-1 p-2 rounded-md border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="text-right">
                <button type="submit"
                        class="mt-4 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow">
                    Update Profile
                </button>
            </div>
        </form>

        <hr class="border-gray-300 dark:border-gray-600">

        <!-- Change Password Section -->
        <h2 class="text-xl font-bold text-center">Change Password</h2>

        <!-- Change Password Form -->
        <form method="POST" action="{{ route('users.changePassword', $user->id) }}" class="space-y-4">
            @csrf

            <div>
                <label for="current_password" class="block font-semibold">Current Password</label>
                <input type="password" id="current_password" name="current_password" required
                       class="w-full mt-1 p-2 rounded-md border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="new_password" class="block font-semibold">New Password</label>
                <input type="password" id="new_password" name="new_password" required
                       class="w-full mt-1 p-2 rounded-md border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="new_password_confirmation" class="block font-semibold">Confirm New Password</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                       class="w-full mt-1 p-2 rounded-md border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="text-right">
                <button type="submit"
                        class="mt-4 px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow">
                    Change Password
                </button>
            </div>
        </form>
    </div>
</body>
</html>
