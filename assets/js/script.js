function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('closed'); // Toggle the 'closed' class to show/hide the sidebar
}

function toggleSubmenu(submenuId) {
    const submenu = document.getElementById(submenuId);
    submenu.classList.toggle('show'); // Toggle the visibility of the submenu
}