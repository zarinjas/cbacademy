<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lesson') }}: {{ $lesson->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Video Player Section - Outside the card for larger display -->
            @if($lesson->has_valid_video)
                <section class="lesson-player-shell video-iphone-safe">
                    <div class="lesson-player-wrap">
                        @if($lesson->video_type === 'youtube')
                            <x-youtube-protected 
                                playerId="lesson-{{ $lesson->id }}" 
                                videoId="{{ $lesson->getYouTubeId() }}" 
                                title="{{ $lesson->title }}" 
                            />
                        @elseif($lesson->video_type === 'google_drive')
                            <x-video-protected 
                                videoUrl="{{ $lesson->video_embed_url }}" 
                                title="{{ $lesson->title }}" 
                            />
                        @elseif($lesson->video_type === 'local')
                            @if(!empty($localVideoUrl))
                                <x-local-video src="{{ $localVideoUrl }}" />
                            @else
                                <div class="p-6 text-center text-gray-300">Local video unavailable.</div>
                            @endif
                        @endif
                        
                        <div class="lesson-player-tip" role="note" aria-label="Fullscreen tip">
                            <span class="tip-dot" aria-hidden="true"></span>
                            <div class="flex-1">
                                <strong class="text-gray-800">💡 Pro Tip:</strong> 
                                <span class="text-gray-700">For the best fullscreen experience, use Google Chrome or a desktop PC.</span>
                            </div>
                            <svg class="w-4 h-4 text-amber-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </section>
            @endif

            <!-- Lesson Content Card -->
            <div class="bg-chef-gray border border-chef-gray-lighter rounded-2xl shadow-card overflow-hidden">
                <div class="p-6 text-white">
                    <!-- Breadcrumb Navigation -->
                    <nav class="flex mb-6" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-300 hover:text-chef-gold transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <a href="{{ route('courses.show', $course->slug) }}" class="ml-1 text-sm font-medium text-gray-300 hover:text-chef-gold transition-colors duration-200 md:ml-2">
                                        {{ $course->title }}
                                    </a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 text-sm font-medium text-chef-gold md:ml-2">{{ $lesson->title }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    <!-- Lesson Title and Meta -->
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-white mb-3">{{ $lesson->title }}</h1>
                        <div class="flex items-center text-sm text-gray-400 space-x-4">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-chef-gold" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838l-2.727 1.17 1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                </svg>
                                Course: <span class="text-chef-gold font-semibold ml-1">{{ $course->title }}</span>
                            </span>
                            @if($lesson->has_valid_video)
                                <span class="flex items-center px-3 py-1 bg-chef-gray-light border border-chef-gray-lighter rounded-xl">
                                    <svg class="w-4 h-4 mr-2 text-chef-gold" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6l2 2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                    </svg>
                                    <span class="text-chef-gold font-medium">Video Available</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Lesson Description -->
                    @if($lesson->description)
                        <div class="prose prose-invert max-w-none mb-6 p-4 bg-chef-gray-light border border-chef-gray-lighter rounded-xl">
                            <h3 class="text-lg font-semibold text-white mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-chef-gold" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Lesson Overview
                            </h3>
                            <p class="text-gray-300 leading-relaxed">{{ $lesson->description }}</p>
                        </div>
                    @endif

                    <!-- Security Notice -->
                    <div class="bg-chef-gray-light border border-chef-gray-lighter rounded-xl p-4 mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-3">
                                <div class="w-10 h-10 bg-chef-gold rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-chef-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-white mb-2 flex items-center">
                                    Content Protection Active
                                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-chef-gold text-chef-black">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Secure
                                    </span>
                                </h4>
                                <p class="text-gray-300 leading-relaxed mb-4">
                                    This lesson uses <span class="font-semibold text-white">protected video content</span>. The video player is secured with advanced protection to prevent external access while maintaining full functionality and user experience.
                                </p>
                                
                                <!-- Feature highlights -->
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div class="flex items-center text-sm text-gray-300">
                                        <svg class="w-4 h-4 mr-2 text-chef-gold" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Full Video Controls
                                    </div>
                                    <div class="flex items-center text-sm text-gray-300">
                                        <svg class="w-4 h-4 mr-2 text-chef-gold" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Secure Playback
                                    </div>
                                    <div class="flex items-center text-sm text-gray-300">
                                        <svg class="w-4 h-4 mr-2 text-chef-gold" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        No External Access
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lesson Navigation -->
                    <div class="flex justify-between items-center pt-6 border-t border-chef-gray-lighter">
                        @if($previousLesson)
                            <a href="{{ route('courses.lessons.show', [$course->slug, $previousLesson->slug]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-chef-gray-light border border-chef-gray-lighter rounded-xl font-semibold text-sm text-white uppercase tracking-wider hover:bg-chef-gray-lighter hover:border-chef-gold focus:outline-none focus:ring-2 focus:ring-chef-gold focus:ring-offset-2 focus:ring-offset-chef-gray transition-all duration-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Previous Lesson
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if($nextLesson)
                            <a href="{{ route('courses.lessons.show', [$course->slug, $nextLesson->slug]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-chef-gray-light border border-chef-gray-lighter rounded-xl font-semibold text-sm text-white uppercase tracking-wider hover:bg-chef-gray-lighter hover:border-chef-gold focus:outline-none focus:ring-2 focus:ring-chef-gold focus:ring-offset-2 focus:ring-offset-chef-gray transition-all duration-300">
                                Next Lesson
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <div></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($lesson->has_valid_video)
        @if($lesson->video_type === 'youtube')
            <?php $ytCss = public_path('css/youtube-protected.css'); $ytJs = public_path('js/youtube-protected.js'); ?>
            <link rel="stylesheet" href="{{ asset('css/youtube-protected.css') }}?v={{ file_exists($ytCss) ? filemtime($ytCss) : time() }}">
            <script src="{{ asset('js/youtube-protected.js') }}?v={{ file_exists($ytJs) ? filemtime($ytJs) : time() }}"></script>
        @elseif($lesson->video_type === 'google_drive')
            <link rel="stylesheet" href="{{ asset('css/video-security.css') }}">
            <script src="{{ asset('js/video-security.js') }}"></script>
        @endif
    @endif
</x-app-layout>
