document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const mainContent = document.querySelector('.main-content');

    toggleBtn.addEventListener('click', function() {
        // Toggle the classes
        sidebar.classList.toggle('collapsed');
        toggleBtn.classList.toggle('collapsed');
        
        if (mainContent) {
            mainContent.classList.toggle('collapsed');
        }
    });
});