    document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const closeBtn = document.getElementById('close-sidebar-btn');
    const dropdownBtn = document.getElementById('events-dropdown-btn');
    const dropdownMenu = document.getElementById('events-dropdown-menu');
    const dropdownIcon = document.getElementById('events-dropdown-icon');

    if (!sidebar) return; // <-- Sidebar isn't on this page, EXIT immediately

    if (hamburger) {
        hamburger.addEventListener('click', () => sidebar.classList.remove('-translate-x-full'));
    }
    if (closeBtn) {
        closeBtn.addEventListener('click', () => sidebar.classList.add('-translate-x-full'));
    }
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-20');
            sidebar.classList.toggle('sidebar-collapsed');

            if (sidebar.classList.contains('sidebar-collapsed')) {
                dropdownMenu?.classList.add('hidden');
                dropdownIcon?.classList.remove('rotate-180');
                dropdownIcon?.classList.add('hidden');
            } else {
                dropdownIcon?.classList.remove('hidden');
            }
        });
    }

    if (dropdownBtn) {
        dropdownBtn.addEventListener('click', () => {
            if (sidebar.classList.contains('sidebar-collapsed')) {
                sidebar.classList.remove('w-20', 'sidebar-collapsed');
                sidebar.classList.add('w-64');
                dropdownIcon?.classList.remove('hidden');
            }

            dropdownMenu?.classList.toggle('hidden');
            dropdownIcon?.classList.toggle('rotate-180');
        });
    }
});