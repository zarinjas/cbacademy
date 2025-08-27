@props(['class' => ''])

@php
    $shortcuts = [
        'h' => 'Go to Home/Dashboard',
        'c' => 'Go to Courses',
        'p' => 'Go to Profile',
        's' => 'Go to Settings',
        'n' => 'Next Lesson',
        'b' => 'Previous Lesson',
        'f' => 'Toggle Fullscreen',
        'm' => 'Toggle Mute',
        '?' => 'Show/Hide Help',
        'Escape' => 'Close Modals/Go Back'
    ];
@endphp

<div 
    x-data="{
        showHelp: false,
        
        init() {
            this.$nextTick(() => {
                this.setupKeyboardShortcuts();
            });
        },
        
        setupKeyboardShortcuts() {
            document.addEventListener('keydown', (e) => {
                // Don't trigger shortcuts when typing in input fields
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                    return;
                }
                
                // Handle shortcuts
                switch(e.key.toLowerCase()) {
                    case 'h':
                        if (e.ctrlKey || e.metaKey) return;
                        e.preventDefault();
                        this.navigateTo('dashboard');
                        break;
                    case 'c':
                        if (e.ctrlKey || e.metaKey) return;
                        e.preventDefault();
                        this.navigateTo('courses');
                        break;
                    case 'p':
                        if (e.ctrlKey || e.metaKey) return;
                        e.preventDefault();
                        this.navigateTo('profile');
                        break;
                    case 's':
                        if (e.ctrlKey || e.metaKey) return;
                        e.preventDefault();
                        this.navigateTo('settings');
                        break;
                    case 'n':
                        if (e.ctrlKey || e.metaKey) return;
                        e.preventDefault();
                        this.navigateTo('next-lesson');
                        break;
                    case 'b':
                        if (e.ctrlKey || e.metaKey) return;
                        e.preventDefault();
                        this.navigateTo('previous-lesson');
                        break;
                    case 'f':
                        if (e.ctrlKey || e.metaKey) return;
                        e.preventDefault();
                        this.toggleFullscreen();
                        break;
                    case 'm':
                        if (e.ctrlKey || e.metaKey) return;
                        e.preventDefault();
                        this.toggleMute();
                        break;
                    case '?':
                        e.preventDefault();
                        this.showHelp = !this.showHelp;
                        break;
                    case 'escape':
                        this.handleEscape();
                        break;
                }
            });
        },
        
        navigateTo(destination) {
            const routes = {
                'dashboard': '{{ route("dashboard") }}',
                'courses': '{{ route("courses.index") }}',
                'profile': '{{ route("profile.edit") }}',
                'settings': '{{ route("admin.settings.index") }}',
                'next-lesson': 'next-lesson',
                'previous-lesson': 'previous-lesson'
            };
            
            if (routes[destination] && !routes[destination].includes('next-lesson') && !routes[destination].includes('previous-lesson')) {
                window.location.href = routes[destination];
            } else if (destination === 'next-lesson' && typeof window.goToNextLesson === 'function') {
                window.goToNextLesson();
            } else if (destination === 'previous-lesson' && typeof window.goToPreviousLesson === 'function') {
                window.goToPreviousLesson();
            }
        },
        
        toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        },
        
        toggleMute() {
            const videos = document.querySelectorAll('video');
            videos.forEach(video => {
                video.muted = !video.muted;
            });
        },
        
        handleEscape() {
            // Close any open modals
            const modals = document.querySelectorAll('[x-show]');
            modals.forEach(modal => {
                if (modal._x_dataStack && modal._x_dataStack[0].showHelp) {
                    modal._x_dataStack[0].showHelp = false;
                }
            });
            
            // Go back in history if no modals to close
            if (window.history.length > 1) {
                window.history.back();
            }
        }
    }"
    class="{{ $class }}"
>
    {{ $slot }}
    
    <!-- Keyboard Shortcuts Help Modal -->
    <div 
        x-show="showHelp" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        @click.away="showHelp = false"
    >
        <div class="bg-chef-gray border border-chef-gray-lighter rounded-2xl p-6 max-w-md w-full mx-4 shadow-card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Keyboard Shortcuts</h3>
                <button @click="showHelp = false" class="text-gray-400 hover:text-white">
                    <x-app.icon name="close" size="sm" />
                </button>
            </div>
            
            <div class="space-y-3">
                @foreach(['h', 'c', 'p', 's', 'n', 'b', 'f', 'm'] as $key)
                    <div class="flex items-center justify-between">
                        <kbd class="px-2 py-1 bg-chef-gray-light border border-chef-gray-lighter rounded text-xs text-white font-mono">
                            {{ strtoupper($key) }}
                        </kbd>
                        <span class="text-sm text-gray-300">{{ $shortcuts[$key] }}</span>
                    </div>
                @endforeach
                
                <div class="flex items-center justify-between">
                    <kbd class="px-2 py-1 bg-chef-gray-light border border-chef-gray-lighter rounded text-xs text-white font-mono">
                        ESC
                    </kbd>
                    <span class="text-sm text-gray-300">{{ $shortcuts['Escape'] }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <kbd class="px-2 py-1 bg-chef-gray-light border border-chef-gray-lighter rounded text-xs text-white font-mono">
                        ?
                    </kbd>
                    <span class="text-sm text-gray-300">Show/Hide Help</span>
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-chef-gray-lighter">
                <p class="text-xs text-gray-400 text-center">
                    Press <kbd class="px-1 py-0.5 bg-chef-gray-light border border-chef-gray-lighter rounded text-xs">?</kbd> anytime to show this help
                </p>
            </div>
        </div>
    </div>
</div>
