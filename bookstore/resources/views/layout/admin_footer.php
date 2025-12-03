        </main>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" class="toast-container"></div>

<script>
// Sidebar toggle
function toggleSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    const body = document.body;
    sidebar.classList.toggle('collapsed');
    body.classList.toggle('sidebar-collapsed');
    
    // Save state to localStorage
    localStorage.setItem('adminSidebarCollapsed', sidebar.classList.contains('collapsed'));
}

// Restore sidebar state
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('adminSidebar');
    const savedState = localStorage.getItem('adminSidebarCollapsed');
    if (savedState === 'true') {
        sidebar.classList.add('collapsed');
        document.body.classList.add('sidebar-collapsed');
    }
    
    // Responsive: auto-collapse on mobile
    if (window.innerWidth < 768) {
        sidebar.classList.add('collapsed');
        document.body.classList.add('sidebar-collapsed');
    }
    
    window.addEventListener('resize', function() {
        if (window.innerWidth < 768) {
            sidebar.classList.add('collapsed');
            document.body.classList.add('sidebar-collapsed');
        }
    });
});

// User Dropdown Toggle
function toggleUserDropdown() {
    const dropdown = document.querySelector('.user-dropdown');
    dropdown.classList.toggle('active');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.querySelector('.user-dropdown');
    const toggle = document.querySelector('.user-dropdown-toggle');
    const menu = document.getElementById('userDropdownMenu');
    
    if (dropdown && !dropdown.contains(event.target)) {
        dropdown.classList.remove('active');
    }
});

// Toast Notification System (same as main site)
(function() {
    const toastContainer = document.getElementById('toast-container');
    
    function showToast(message, type = 'success', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            </div>
            <div class="toast-message">${message}</div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        toastContainer.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }
    
    window.showToast = showToast;
})();
</script>

</body>
</html>

