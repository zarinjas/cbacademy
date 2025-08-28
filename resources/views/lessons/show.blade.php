<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <h2 class="font-semibold text-xl text-white leading-tight">
                        {{ $lesson->title }}
                    </h2>
                    <x-app.badge variant="progress" size="sm">
                        {{ $course->publishedLessons->search($lesson) + 1 }}/{{ $course->publishedLessons->count() }}
                    </x-app.badge>
                </div>
                <p class="text-gray-400 text-sm">
                    {{ $course->title }} • Lesson {{ $course->publishedLessons->search($lesson) + 1 }} of {{ $course->publishedLessons->count() }}
                </p>
            </div>
            <a href="{{ route('courses.show', $course->slug) }}" class="text-chef-gold hover:text-chef-gold-light transition-colors duration-300">
                ← Back to Course
            </a>
        </div>
    </x-slot>

    <!-- Include YouTube Protected Player CSS -->
    <link rel="stylesheet" href="{{ asset('css/youtube-protected.css') }}">
    
    <div class="min-h-screen bg-gray-900">
        <div class="container mx-auto px-4 py-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm text-gray-400">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Dashboard</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('courses.show', $course) }}" class="hover:text-white transition-colors">{{ $course->title }}</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-white">{{ $lesson->title }}</li>
                </ol>
            </nav>

            <!-- Video Player Section -->
            <div class="mb-8">
                <x-app.card>
                    <div class="space-y-6">
                        <!-- Video Player -->
                        <div class="video-player-container">
                            @if($lesson->video_type === 'youtube')
                                <!-- Protected YouTube Video Player -->
                                <x-youtube-protected 
                                    playerId="lesson-{{ $lesson->id }}" 
                                    videoId="{{ $lesson->getYouTubeId() }}"
                                    title="{{ $lesson->title }}" />
                            @elseif($lesson->video_type === 'google_drive')
                                <!-- Google Drive Video Player -->
                                <div class="video-outer" style="position: relative; aspect-ratio: 16/9; width: 100%;">
                                    <iframe 
                                        src="{{ $lesson->video_embed_url }}" 
                                        allowfullscreen
                                        title="{{ $lesson->title }}"
                                        id="google-drive-video-iframe"
                                        style="position: absolute; inset: 0; width: 100%; height: 100%; border: 0;"
                                    ></iframe>
                                    
                                    <!-- Simple Pop-out Blocker (Top-right corner) -->
                                    <div class="absolute top-2 right-2 w-12 h-12 bg-gray-800 rounded z-30" style="pointer-events: none;"></div>
                                    
                                    <!-- Security Notice -->
                                    <div class="absolute top-2 left-2 bg-blue-600 text-white text-xs p-2 rounded z-20">
                                        <strong>Secure:</strong><br>
                                        Type: Google Drive<br>
                                        Protected: YES
                                    </div>
                                </div>
                            @else
                                <!-- Fallback for other video types -->
                                <div class="video-outer" style="position: relative; aspect-ratio: 16/9; width: 100%;">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="text-center text-gray-400">
                                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 00-2-2V8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="text-lg font-medium">Video Not Available</p>
                                            <p class="text-sm">Please contact support</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Lesson Info -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h1 class="text-2xl font-bold text-white">{{ $lesson->title }}</h1>
                                <div class="flex items-center space-x-2">
                                    @if($lesson->is_free_preview)
                                        <x-app.badge variant="gold" size="sm">Free Preview</x-app.badge>
                                    @endif
                                    <x-app.badge variant="info" size="sm">{{ $lesson->duration_formatted }}</x-app.badge>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4 text-sm text-gray-400">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Video Lesson</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $lesson->duration_formatted }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-app.card>
            </div>

            <!-- Security Notice -->
            <div class="mb-8">
                <x-app.card>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-white mb-1">Content Protection</h4>
                            <p class="text-xs text-gray-400">
                                @if($lesson->video_type === 'youtube')
                                    This YouTube video is securely embedded with custom controls. Users cannot click into YouTube or access external links while maintaining full video functionality.
                                @else
                                    This video is securely embedded and protected. Direct video links are not accessible to maintain content security.
                                @endif
                            </p>
                        </div>
                    </div>
                </x-app.card>
            </div>

            <!-- Navigation Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Previous Lesson -->
                @if($previousLesson)
                    <x-app.card>
                        <a href="{{ route('courses.lessons.show', [$course->slug, $previousLesson->slug]) }}" class="block hover:bg-gray-800 transition-colors rounded-xl p-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 mb-1">Previous Lesson</p>
                                    <h3 class="text-sm font-medium text-white">{{ $previousLesson->title }}</h3>
                                </div>
                            </div>
                        </a>
                    </x-app.card>
                @endif

                <!-- Next Lesson -->
                @if($nextLesson)
                    <x-app.card>
                        <a href="{{ route('courses.lessons.show', [$course->slug, $nextLesson->slug]) }}" class="block hover:bg-gray-800 transition-colors rounded-xl p-4">
                            <div class="flex items-center space-x-3">
                                <div class="ml-auto">
                                    <p class="text-xs text-gray-400 mb-1 text-right">Next Lesson</p>
                                    <h3 class="text-sm font-medium text-white">{{ $nextLesson->title }}</h3>
                                </div>
                                <div class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </x-app.card>
                @endif
            </div>

            <!-- Course Progress -->
            <div class="mb-8">
                <x-app.card>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-chef-gold rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-chef-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-white">{{ $course->freePreviewLessons()->count() }}</div>
                        <div class="text-sm text-gray-400">Free Previews</div>
                    </div>
                </x-app.card>
            </div>
        </div>
    </div>
    
    <!-- Include YouTube Protected Player JavaScript -->
    <script src="{{ asset('js/youtube-protected.js') }}"></script>
    
    <!-- Additional Security Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== PROTECTED VIDEO PLAYER LOADED ===');
            
            // Prevent right-click context menu on the entire page
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                return false;
            });
            
            // Prevent keyboard shortcuts for save/download
            document.addEventListener('keydown', function(e) {
                // Prevent Ctrl+S, Ctrl+Shift+S, F12, Ctrl+U, Ctrl+Shift+I
                if (
                    (e.ctrlKey && e.key === 's') ||
                    (e.ctrlKey && e.shiftKey && e.key === 'S') ||
                    e.key === 'F12' ||
                    (e.ctrlKey && e.key === 'u') ||
                    (e.ctrlKey && e.shiftKey && e.key === 'I')
                ) {
                    e.preventDefault();
                    return false;
                }
            });
            
            // Prevent drag and drop
            document.addEventListener('dragstart', function(e) {
                e.preventDefault();
                return false;
            });
            
            // Handle Google Drive video security (if present)
            const googleDriveIframe = document.getElementById('google-drive-video-iframe');
            if (googleDriveIframe) {
                console.log('Google Drive iframe loaded:', googleDriveIframe.src);
                
                googleDriveIframe.addEventListener('load', function() {
                    console.log('Google Drive iframe loaded successfully');
                });
                
                googleDriveIframe.addEventListener('error', function(e) {
                    console.error('Google Drive iframe failed to load:', e);
                });
            }
        });
    </script>
</x-app-layout>
