<!-- Footer -->
<footer class="mt-16 text-center text-sm text-gray-500 dark:text-gray-400 p-6">
    <p>&copy; {{ date('Y') }} RCARS - Residential College Accommodation Reservation System. All rights reserved.</p>
</footer>

<!-- Scripts -->
<script>
    function toggleDarkMode() {
        document.documentElement.classList.toggle('dark');
        const icon = document.getElementById('darkModeIcon');
        icon.classList.toggle('fa-moon');
        icon.classList.toggle('fa-sun');
    }

    function logout() {
        if (confirm("Are you sure you want to log out?")) {
            window.location.href = "{{ route('logout') }}";
        }
    }

    function toggleNotifications() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('hidden');
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationDropdown');
        const btn = document.getElementById('notificationBtn');
        if (dropdown && btn && !dropdown.contains(event.target) && !btn.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>

</body>
</html>
