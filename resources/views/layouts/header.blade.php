<header id="navbar" class="relative bg-white dark:bg-gray-800 shadow p-4 flex justify-between items-center sticky top-0 z-50">
    <div class="flex items-center gap-4">
        <img src="{{ asset('images/uthm.png') }}" alt="UTHM Logo" class="h-8 md:h-10">
    </div>

    <div class="flex items-center gap-6 relative">
        <!-- Dark Mode Toggle -->
        <button onclick="toggleDarkMode()" class="focus:outline-none text-gray-800 dark:text-gray-200" title="Toggle Dark Mode" aria-label="Toggle Dark Mode">
            <i id="darkModeIcon" class="fas fa-moon fa-sm"></i>
        </button>

        <!-- Settings for Non-UTHM Users Only -->
        @php $user = auth()->user(); @endphp
        @if ($user && $user->user_type === 'non-uthm')
            <a href="{{ route('users.account') }}" title="Manage your account"
               class="flex items-center gap-2 text-sm text-gray-800 dark:text-gray-200 hover:underline">
                <i class="fas fa-user-cog"></i> Manage Account
            </a>
        @endif

        <!-- Notification Bell -->
        <div class="relative">
            <button id="notificationBtn" class="focus:outline-none text-gray-800 dark:text-gray-200" title="Notifications" aria-label="Notifications">
                <i class="fas fa-bell fa-sm"></i>
                @php $unreadCount = $user->unreadNotifications->count(); @endphp
                @if ($unreadCount > 0)
                    <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-xs text-white rounded-full border-2 border-white dark:border-gray-800 flex items-center justify-center">
                        {{ $unreadCount }}
                    </span>
                @endif
            </button>

            <!-- Notification Dropdown -->
            <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-lg z-50">
                <div class="p-4 flex justify-between items-center border-b dark:border-gray-600">
                    <span class="font-semibold text-gray-700 dark:text-gray-200">Notifications</span>
                    @if ($unreadCount > 0)
                        <form action="{{ route('user.notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-blue-600 hover:underline dark:text-blue-400">Mark all as read</button>
                        </form>
                    @endif
                </div>
                <ul class="max-h-60 overflow-y-auto divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($user->unreadNotifications as $note)
                        <li class="p-4 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium">
                            <a href="{{ $note->data['url'] ?? '#' }}" class="block text-sm text-gray-800 dark:text-gray-200">
                                <p>{{ $note->data['title'] ?? 'Notification' }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $note->data['message'] ?? '' }}</p>
                                <p class="text-xs text-right text-gray-400 dark:text-gray-500">
                                    {{ \Carbon\Carbon::parse($note->data['timestamp'] ?? $note->created_at)->diffForHumans() }}
                                </p>
                            </a>
                        </li>
                    @empty
                        <li class="p-4 text-sm text-gray-500 dark:text-gray-400">No new notifications.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Logout -->
        <button onclick="logout()" class="focus:outline-none text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-600" title="Logout" aria-label="Logout">
            <i class="fas fa-sign-out-alt fa-sm"></i>
        </button>
    </div>
</header>

<!-- Dark Mode + Notification Toggle Scripts -->
<script>
    function toggleDarkMode() {
        const htmlEl = document.documentElement;
        const icon = document.getElementById('darkModeIcon');
        htmlEl.classList.toggle('dark');
        const isDark = htmlEl.classList.contains('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        icon.classList.toggle('fa-moon', !isDark);
        icon.classList.toggle('fa-sun', isDark);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const savedTheme = localStorage.getItem('theme');
        const htmlEl = document.documentElement;
        const icon = document.getElementById('darkModeIcon');
        if (savedTheme === 'dark') {
            htmlEl.classList.add('dark');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        }
    });

    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');

    notificationBtn?.addEventListener('click', function (e) {
        e.stopPropagation();
        notificationDropdown?.classList.toggle('hidden');
    });

    window.addEventListener('click', function (e) {
        if (!notificationDropdown.contains(e.target) && !notificationBtn.contains(e.target)) {
            notificationDropdown.classList.add('hidden');
        }
    });

    function logout() {
        window.location.href = "{{ route('login') }}";
    }
</script>
