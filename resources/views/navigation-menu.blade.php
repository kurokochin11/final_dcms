<nav x-data="{ open: false }" class="bg-primary border-b border-blue-400 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('tooth_logo.ico') }}" alt="Logo" class="block h-9 w-auto brightness-0 invert" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-white hover:text-blue-100 border-white">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
             
          <div class="ms-3 relative flex items-center" x-data="notificationSystem()">
    <x-dropdown align="right" width="60">
        <x-slot name="trigger">
            <button class="relative p-2 text-white hover:bg-[#3659c7] rounded-full transition focus:outline-none">
                <i class="fa fa-bell text-xl"></i>

                <!-- Dynamic Badge -->
                <span x-show="notifications.length > 0"
                      class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full border-2 border-[#4169e1]">
                    <span x-text="notifications.length"></span>
                </span>
            </button>
        </x-slot>
<x-slot name="content">
    <div class="w-64">
        <!-- Header showing number of notifications -->
        <div class="block px-4 py-2 text-sm font-semibold text-gray-700 border-b border-gray-100 text-center">
            <span x-text="'You have ' + notifications.length + ' appointment(s) today'"></span>
        </div>

        <div class="max-h-60 overflow-y-auto">

            <!-- If no notifications -->
            <template x-if="notifications.length === 0">
                <div class="px-4 py-3 text-sm text-gray-500 text-center">
                    No appointments today
                </div>
            </template>

            <!-- List notifications -->
          <template x-for="notif in notifications" :key="notif.id">
    <x-dropdown-link href="{{ route('appointments.index') }}" @click="markAsRead(notif.id)">
        <div class="flex items-center space-x-3">
            <div class="bg-blue-500 p-2 rounded-full text-white">
                <i class="fa fa-calendar text-xs"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900" 
                   x-text="notif.patient.first_name + ' ' + notif.patient.last_name"></p>
                <p class="text-xs text-gray-500" x-text="'Scheduled for ' + notif.appointment_time"></p>
            </div>
        </div>
    </x-dropdown-link>
</template>
        </div>

     <button @click="markAllAsRead()" class="w-full block py-2 text-sm text-center text-blue-600 font-bold hover:underline border-t border-gray-100 focus:outline-none">
    Mark all as read
</button>
    </div>
</x-slot>

</x-dropdown>
                </div>
            
            <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <span class="inline-flex rounded-md">
                              <button type="button" 
        class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-primary hover:bg-[#3659c7] transition focus:outline-none">

        <!-- {{-- Profile Photo --}} -->
        <img class="h-8 w-8 rounded-full object-cover border-2 border-white mr-2"
             src="{{ Auth::user()->profile_photo_url }}"
             alt="{{ Auth::user()->name }}" />

        <!-- {{-- User Name --}} -->
        {{ Auth::user()->name }}

        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>
                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <div class="border-t border-gray-200"></div>
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-[#3659c7] focus:outline-none transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('notificationSystem', () => ({
            // 1. Initial data from page load
            notifications: @json($todayScheduledAppointments ?? []),

            init() {
                console.log('Notifications initialized:', this.notifications);

                // 2. THE HEARTBEAT: Check for new appointments every 30 seconds
                setInterval(() => {
                    this.fetchUpdates();
                }, 30000); 
            },

            // 3. The function that talks to your web.php route
            async fetchUpdates() {
                try {
                    let response = await fetch('/api/notifications/updates');
                    if (response.ok) {
                        let newData = await response.json();
                        
                        // Only update the UI if the data has actually changed
                        if (JSON.stringify(newData) !== JSON.stringify(this.notifications)) {
                            this.notifications = newData;
                            console.log('Real-time update: New appointments found!');
                        }
                    }
                } catch (error) {
                    console.error('Real-time sync failed:', error);
                }
            },

            // 4. Mark a single notification as read
            markAsRead(id) {
                // Remove from the local UI list immediately for a snappy feel
                this.notifications = this.notifications.filter(notif => notif.id !== id);

                // Send request to server so it stays read after refresh
                fetch(`/api/notifications/mark-read/${id}`, { 
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }).catch(err => console.error('Failed to update server:', err));

                console.log('Notification ' + id + ' removed from view.');
            },

            // 5. Mark everything as read
            markAllAsRead() {
                // Clear the local UI
                this.notifications = [];

                // Send request to server
                fetch('/api/notifications/mark-all-read', { 
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }).catch(err => console.error('Failed to clear notifications:', err));
            },

            openModal(mode, data) {
                if (window.appointmentManager) {
                    window.appointmentManager.openModal(mode, data);
                    
                    // Mark as read automatically when the modal opens
                    this.markAsRead(data.id);
                } else {
                    console.error('appointmentManager is not defined on this page.');
                }
            }
        }));
    });
</script>