<div class="sidebar-wrapper">
    <aside id="sidebar" class="sidebar">
        <button id="sidebarToggle" class="toggle-btn">
            <svg id="toggleIcon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div class="sidebar-header">
            <h1>Admin Panel</h1>
        </div>
        
        <nav class="sidebar-menu">
            <div class="menu-section">
                <p class="section-title">Academic Structure</p>
                <ul>
                    <li>
                        <a href="{{ route('patients.index') }}" class="{{ request()->routeIs('patients.index') ? 'active' : '' }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Patients</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('medical-history.answer_index') }}" class="{{ request()->routeIs('medical-history.answer_index') ? 'active' : '' }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Medical History</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('check-up.checkup_answer_index') }}" class="{{ request()->routeIs('check-up.checkup_answer_index') ? 'active' : '' }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            <span>Check-up</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="menu-section">
                <p class="section-title">Students & Subjects</p>
                <ul>
                    <li>
                        <a href="{{ route('oral_examination.index_extraoral') }}" class="{{ request()->routeIs('oral_examination.index_extraoral') ? 'active' : '' }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Extra Oral Examination</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('oral_examination.index_intraoral') }}" class="{{ request()->routeIs('oral_examination.index_intraoral') ? 'active' : '' }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>Intra Oral Examination</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="menu-section">
                <p class="section-title">Academic Records</p>
                <ul>
                    <li>
                        <a href="{{ route('radiographs.index') }}" class="{{ request()->routeIs('radiographs.index') ? 'active' : '' }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <span>Radiographs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('diagnoses.index') }}" class="{{ request()->routeIs('diagnoses.index') ? 'active' : '' }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Diagnosis</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>
</div>

<style>
    /* Layout Wrapper */
.sidebar-wrapper {
    position: relative;
}

/* Sidebar Styling */
.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background: #ffffff;
    color: #4b5563;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease-in-out;
    z-index: 1000;
    border-right: 1px solid #e5e7eb;
}

/* Collapsed State for Sidebar */
.sidebar.collapsed {
    transform: translateX(-250px);
}

/* Burger Button - Attached to the SIDE of the sidebar */
.toggle-btn {
    position: absolute;
    right: -40px; /* Sticks it to the outer edge */
    top: 15px;
    background: #ffffff;
    color: #6495ed;
    border: 1px solid #e5e7eb;
    border-left: none;
    border-radius: 0 6px 6px 0;
    padding: 8px;
    cursor: pointer;
    box-shadow: 2px 0 5px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1001;
}

/* Main Content Adjustment - THIS MOVES THE TABLE */
.main-content {
    margin-left: 250px; /* Match sidebar width */
    width: calc(100% - 250px);
    transition: all 0.3s ease-in-out;
    position: relative;
    min-height: 100vh;
}

/* When the sidebar is collapsed, content takes full screen */
.main-content.sidebar-collapsed {
    margin-left: 0 !important;
    width: 100% !important;
}

/* Navigation Links */
.sidebar-header {
    padding: 20px 15px;
    border-bottom: 1px solid #f3f4f6;
}

.sidebar-header h1 {
    color: #6495ed;
    font-size: 18px;
    font-weight: bold;
    margin: 0;
}

.sidebar-menu {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
}

.sidebar-menu ul { list-style: none; padding: 0; margin: 0; }

.sidebar-menu a {
    display: flex;
    align-items: center;
    padding: 10px 12px;
    border-radius: 8px;
    color: #4b5563;
    text-decoration: none;
    transition: all 0.2s;
    margin-bottom: 4px;
}

.sidebar-menu a:hover {
    background: #f3f4f6;
}

/* Active color for all routes */
.sidebar-menu a.active {
    background: #6495ed !important;
    color: #ffffff !important;
}

.menu-icon {
    width: 18px;
    height: 18px;
    margin-right: 12px;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        width: 100%;
    }
    .sidebar {
        transform: translateX(-250px);
    }
    .sidebar.open {
        transform: translateX(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const mainContent = document.querySelector('.main-content');
    
    // Toggle function
    toggleBtn.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            // Mobile: Overlays the content
            sidebar.classList.toggle('open');
        } else {
            // Desktop: Slides out and pulls/pushes the table
            sidebar.classList.toggle('collapsed');
            
            if (mainContent) {
                mainContent.classList.toggle('sidebar-collapsed');
            }
            
            // Save state to local storage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }
    });

    // Apply saved state on page load
    const isSavedCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isSavedCollapsed && window.innerWidth > 768) {
        sidebar.classList.add('collapsed');
        if (mainContent) {
            mainContent.classList.add('sidebar-collapsed');
        }
    }
});
</script>