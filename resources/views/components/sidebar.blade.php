<div class="sidebar-wrapper">
    <aside id="sidebar" class="sidebar">
        <button id="sidebarToggle" class="toggle-btn">
            <svg id="toggleIcon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div class="sidebar-header">
            <a href="{{ url('/') }}" class="sidebar-logo-link">
        <img src="{{ asset('tooth_logo.ico') }}" alt="Dr. Phua's Dental Clinic" class="sidebar-logo">
    </a>
            <h1>Dental Clinic</h1>
        </div>
<nav class="sidebar-menu">
    <div class="menu-section">
        <h2 class="section-title">Main Content</h2>
        <ul>
            <li>
                <a href="{{ route('patients.index') }}" class="{{ request()->routeIs('patients.index') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Patients</span>
                </a>
            </li>
            <li>
                  <li>
                <a href="{{ route('appointments.index') }}" class="{{ request()->routeIs('appointments.index') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"/>
                        <line x1="16" y1="2" x2="16" y2="6" stroke-width="2" stroke-linecap="round"/>
                        <line x1="8" y1="2" x2="8" y2="6" stroke-width="2" stroke-linecap="round"/>
                        <line x1="3" y1="10" x2="21" y2="10" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span>Appointments</span>
                </a>
            </li>
             <div class="menu-section">
        <h2 class="section-title">Physical Examination</h2>

                <li>
                <a href="{{ route('medical-history.answer_index') }}" class="{{ request()->routeIs('medical-history.answer_index') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Medical History</span>
                </a>
            </li>
            <li>
                <a href="{{ route('check-up.checkup_answer_index') }}" class="{{ request()->routeIs('check-up.checkup_answer_index') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <polyline points="22 4 12 14.01 9 11.01" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Check-up</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="menu-section">
        <h2 class="section-title">Oral Examination</h2>
        <ul>
            <li>
                <a href="{{ route('oral_examination.index_extraoral') }}" class="{{ request()->routeIs('oral_examination.index_extraoral') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2M9 9h.01M15 9h.01" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Extra Oral Examination</span>
                </a>
            </li>
            <li>
                <a href="{{ route('oral_examination.index_intraoral') }}" class="{{ request()->routeIs('oral_examination.index_intraoral') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 16a6 6 0 1 1 6-6 6 6 0 0 1-6 6z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Intra Oral Examination</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="menu-section">
        <p class="section-title">Other Contents</p>
        <ul>
            <li>
                <a href="{{ route('radiographs.index') }}" class="{{ request()->routeIs('radiographs.index') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke-width="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5" stroke-width="2"/>
                        <polyline points="21 15 16 10 5 21" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Radiographs</span>
                </a>
            </li>
            <li>
                <a href="{{ route('diagnoses.index') }}" class="{{ request()->routeIs('diagnoses.index') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1" stroke-width="2"/>
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
.section-title {
    font-size: 14px;       /* Adjust size as needed */
    font-weight: bold;     /* Makes the text bold */
    color: #ffffff;        /* A dark professional blue-grey */
    margin-bottom: 10px;   /* Adds space below the title */
     /* Optional: adds a thin light blue line below */
    display: inline-block; /* Ensures the border only goes as far as the text */
    padding-bottom: 5px;
    
}

/* Sidebar Styling */
.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background: #4169e1;
    color: #ffffff;
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
    background: #4169e1;  
    color: #ffffff;
    border: 1px solid #ffffff;
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
    background-color: #4169e1; 
    padding: 15px 20px;        
   display: flex;
    align-items: center;    
}
.header-content {
    display: flex;
    align-items: center; 
    gap: 12px;           
}

.sidebar-logo {
    width: 40px;               
    height: auto;
    filter: brightness(0) invert(1); 
}

.sidebar-header h1 {
    color: #ffffff;            
    font-size: 18px;
    font-weight: bold;
    margin: 0;
    line-height: 1;
    
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
    color: #ffffff;
    text-decoration: none;
    transition: all 0.2s;
    margin-bottom: 4px;
}

.sidebar-menu a:hover {
    background: #6495ed;
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