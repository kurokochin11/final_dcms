<div class="sidebar-wrapper">
    <aside id="sidebar" class="sidebar">
        <!-- Sidebar Top (Logo + Title + Toggle) -->
        <div class="sidebar-top">
            <img src="{{ asset('tooth_logo.ico') }}" alt="Logo" class="sidebar-logo">
            <span class="sidebar-title">Dental Care</span>

</div>
       <div>
        <span class="sidebar-section">Clinical Records</span>
            <button id="sidebarToggle" class="sidebar-toggle">
                <svg width="24" height="24" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Sidebar Menu -->
        <nav class="sidebar-menu">
            <div class="menu-section">
             
                <ul>
                    <li>
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('patients.index') }}" class="{{ request()->routeIs('patients.index') ? 'active' : '' }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253" />
                            </svg>
                            <span>Patients</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('radiographs.index') }}" class="{{ request()->routeIs('radiographs.index') ? 'active' : '' }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                            </svg>
                            <span>Radiographs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('oral_examination.index_extraoral') }}" class="{{ request()->routeIs('oral_examination.index_extraoral') ? 'active' : '' }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16" />
                            </svg>
                            <span>Extra Oral Examination</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('oral_examination.index_intraoral') }}" class="{{ request()->routeIs('oral_examination.index_intraoral') ? 'active' : '' }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586l5.414 5.414" />
                            </svg>
                            <span>Intraoral Examination</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('medical-history.answer_index') }}" class="{{ request()->routeIs('medical-history.*') ? 'active' : '' }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7h18M3 12h18M3 17h18" />
                            </svg>
                            <span>Medical History</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>
</div>

<style>
:root {
  --sidebar-width: 250px;
  --sidebar-rail-width: 70px;
  --transition-speed: 250ms;
  --sidebar-bg: #1f2a44;
  --rail-bg: #0d1321;
  --icon-color: #9aa6bf;
  --icon-active-bg: #2c3e70;
  --icon-active-color: #60a5fa;
}

/* Sidebar */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: var(--sidebar-width);
  height: 100vh;
  background: var(--sidebar-bg);
  display: flex;
  flex-direction: column;
  padding: 12px;
  transition: width var(--transition-speed) ease;
}

/* Sidebar top (logo + title + toggle inside sidebar) */

.sidebar-top {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 12px;
}

.sidebar-logo {
    width: 40px;
    height: 40px;
    transition: opacity 0.25s, width 0.25s, height 0.25s;
}

/* Hide logo in collapsed mode */
.sidebar.collapsed .sidebar-logo {
    opacity: 0;
    width: 0;
    height: 0;
    pointer-events: none;
}
/* Optional: hide section heading when collapsed */
.sidebar.collapsed .sidebar-section {
    opacity: 0;
    pointer-events: none;
}

/* Title text */
.sidebar-title {
    font-weight: 800;
    font-size: 1.5rem;
    color: #fff;
    white-space: nowrap;
    transition: opacity 0.25s;
}

/* Keep sidebar background the same in collapsed mode */
.sidebar,
.sidebar.collapsed {
    background: #1f2a44; /* same color */
}

.sidebar-toggle {
  margin-left: auto;
  background: none;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  padding: 4px;
}

/* Menu */
.sidebar-menu {
  flex: 1;
  margin-top: 16px;
}
.menu-section .section-title {
  font-size: 0.875rem;
  font-weight: 600;
  
  margin-bottom: 8px;
}
.menu-section ul { list-style: none; padding: 0; margin: 0; }
.menu-section ul li a {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 12px;
  border-radius: 8px;
  
  text-decoration: none;
  transition: all 0.2s;
}
.menu-section ul li a:hover { background: rgba(255,255,255,0.05); }
.menu-section ul li a.active { background: var(--icon-active-bg); }
.menu-icon { width: 24px; height: 24px; }

/* Collapsed sidebar */
.sidebar.collapsed {
  width: var(--sidebar-rail-width);
  background: var(--rail-bg);
}
.sidebar.collapsed .sidebar-title {
  opacity: 0;
  pointer-events: none;
}
.sidebar.collapsed .menu-section .section-title,
.sidebar.collapsed .sidebar-menu a span { display: none; }
.sidebar.collapsed .menu-section ul li a { justify-content: center; padding: 12px 0; }
.sidebar.collapsed .menu-icon { width: 28px; height: 28px; }

/* Keep toggle button inside collapsed sidebar */
.sidebar.collapsed .sidebar-toggle {
  position: relative;
}

/* Hover expand */
.sidebar.open-hover { width: var(--sidebar-width); }
.sidebar.open-hover .sidebar-title { opacity: 1; pointer-events: auto; }
.sidebar.open-hover .menu-section .section-title,
.sidebar.open-hover .sidebar-menu a span { display: inline; }

/* Main content push */
.main-content {
  margin-left: var(--sidebar-width);
  transition: margin-left var(--transition-speed) ease;
}
.main-content.sidebar-collapsed { margin-left: var(--sidebar-rail-width); }

/* Responsive */
@media (max-width: 768px) {
  .sidebar { transform: translateX(-100%); width: var(--sidebar-width); }
  .sidebar.open { transform: translateX(0); }
  .main-content { margin-left: 0; }
}
</style>

<script>

document.addEventListener('DOMContentLoaded', function() {
    var sidebar = document.getElementById('sidebar');
    var toggleBtn = document.getElementById('sidebarToggle');
    var toggleIcon = document.getElementById('toggleIcon');
    var mainContent = document.querySelector('.main-content');

    // If main content doesn't exist, create a wrapper (keeps your original logic)
    if (!mainContent) {
        var sidebarWrapper = document.querySelector('.sidebar-wrapper');
        mainContent = document.createElement('div');
        mainContent.className = 'main-content';

        // Move element nodes after sidebar-wrapper into mainContent (skip scripts)
        var currentNode = sidebarWrapper.nextElementSibling;
        var nodesToMove = [];
        while (currentNode) {
            if (currentNode.tagName && currentNode.tagName.toLowerCase() === 'script') {
                currentNode = currentNode.nextElementSibling;
                continue;
            }
            nodesToMove.push(currentNode);
            currentNode = currentNode.nextElementSibling;
        }

        sidebarWrapper.parentNode.insertBefore(mainContent, sidebarWrapper.nextSibling);
        nodesToMove.forEach(function(node) {
            mainContent.appendChild(node);
        });
    }

    // Original behavior variables
    var STORAGE_KEY = 'sidebarCollapsed';

    function checkScreenSize() {
        if (window.innerWidth <= 768) {
            // Mobile: sidebar hidden by default (overlay)
            sidebar.classList.remove('collapsed');
            toggleBtn.classList.remove('collapsed');

            if (sidebar.classList.contains('open')) {
                toggleBtn.classList.add('open');
                mainContent.classList.add('sidebar-open');
            } else {
                toggleBtn.classList.remove('open');
                mainContent.classList.remove('sidebar-open');
            }
            // ensure no hover state applied on mobile
            sidebar.classList.remove('open-hover');
        } else {
            // Desktop: check saved state
            if (localStorage.getItem(STORAGE_KEY) === 'true') {
                sidebar.classList.add('collapsed');
                toggleBtn.classList.add('collapsed');
                mainContent.classList.add('sidebar-collapsed');
            } else {
                sidebar.classList.remove('collapsed');
                toggleBtn.classList.remove('collapsed');
                mainContent.classList.remove('sidebar-collapsed');
            }
            // remove mobile overlay classes
            sidebar.classList.remove('open');
            toggleBtn.classList.remove('open');
            mainContent.classList.remove('sidebar-open');
        }
    }

    // Original click toggle (kept exactly)
    function toggleSidebar() {
        if (window.innerWidth <= 768) {
            // Mobile toggle
            sidebar.classList.toggle('open');
            toggleBtn.classList.toggle('open');
            mainContent.classList.toggle('sidebar-open');
        } else {
            // Desktop toggle
            sidebar.classList.toggle('collapsed');
            toggleBtn.classList.toggle('collapsed');
            mainContent.classList.toggle('sidebar-collapsed');

            // Save state
            localStorage.setItem(STORAGE_KEY, sidebar.classList.contains('collapsed'));
        }
    }

    toggleBtn.addEventListener('click', function() {
        toggleSidebar();
    });

    // ------------------ NEW: Hover / auto-close behavior ------------------
    // Only active on desktop (width > 768)
    var HIDE_DELAY = 300; // milliseconds before auto-close after pointerleave
    var hideTimer = null;

    function clearHideTimer() {
        if (hideTimer) {
            clearTimeout(hideTimer);
            hideTimer = null;
        }
    }

    function openOnHover() {
        if (window.innerWidth <= 768) return; // don't use hover on mobile
        clearHideTimer();
        // only open if currently collapsed OR closed state
        sidebar.classList.remove('collapsed');
        sidebar.classList.add('open-hover'); // cosmetic class (you can style)
        toggleBtn.classList.remove('collapsed');
        // ensure mainContent pushed if you use that behavior
        if (mainContent) mainContent.classList.remove('sidebar-collapsed');
    }

    function scheduleCloseOnLeave() {
        if (window.innerWidth <= 768) return;
        clearHideTimer();
        hideTimer = setTimeout(function() {
            sidebar.classList.add('collapsed');
            sidebar.classList.remove('open-hover');
            toggleBtn.classList.add('collapsed');
            if (mainContent) mainContent.classList.add('sidebar-collapsed');
            hideTimer = null;
        }, HIDE_DELAY);
    }

    // Attach pointer events to both sidebar and toggle button so hovering either opens
    [sidebar, toggleBtn].forEach(function(el) {
        el.addEventListener('pointerenter', function() {
            if (window.innerWidth <= 768) return;
            // If user explicitly clicked to collapse (localStorage true), we still allow hover to temporarily open.
            openOnHover();
        });
        el.addEventListener('pointerleave', function() {
            if (window.innerWidth <= 768) return;
            scheduleCloseOnLeave();
        });
    });

    // Cancel auto-close while pointer moves inside the sidebar
    sidebar.addEventListener('pointermove', function() {
        if (window.innerWidth <= 768) return;
        clearHideTimer();
    });

    // Keyboard focus handling (keeps sidebar open while focused)
    sidebar.addEventListener('focusin', function() {
        if (window.innerWidth <= 768) return;
        clearHideTimer();
        openOnHover();
    });
    sidebar.addEventListener('focusout', function() {
        if (window.innerWidth <= 768) return;
        scheduleCloseOnLeave();
    });

    // Re-apply initial state on resize (debounced)
    var resizeTimer = null;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            checkScreenSize();
        }, 120);
    });

    // Initialize
    checkScreenSize();
});
</script>
