<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ $course->title }}
            </h2>
            <a href="{{ route('courses.index') }}" class="text-chef-gold hover:text-chef-gold-light transition-colors duration-300 self-start sm:self-auto">
                ← Back to Courses
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            <!-- Course Hero Section -->
            <div class="mb-6 sm:mb-8">
                <x-app.card>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                        <!-- Course Image -->
                        <div class="lg:col-span-1">
                            <img 
                                src="{{ $course->hero_image_url }}" 
                                alt="{{ $course->title }}"
                                class="w-full h-48 sm:h-64 lg:h-full object-cover rounded-2xl"
                                onerror="this.src='https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=800&h=600&fit=crop&crop=center'"
                            >
                        </div>

                        <!-- Course Info -->
                        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold text-white mb-3 sm:mb-4">{{ $course->title }}</h1>
                                <p class="text-gray-300 text-base sm:text-lg leading-relaxed">{{ $course->description }}</p>
                            </div>

                            <!-- Course Stats -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                                <div class="text-center">
                                    <div class="text-xl sm:text-2xl font-bold text-chef-gold">{{ $course->getTotalLessonsCount() }}</div>
                                    <div class="text-xs sm:text-sm text-gray-400">Lessons</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl sm:text-2xl font-bold text-chef-gold">{{ $course->getTotalDurationFormatted() }}</div>
                                    <div class="text-xs sm:text-sm text-gray-400">Duration</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl sm:text-2xl font-bold text-chef-gold">{{ $course->freePreviewLessons()->count() }}</div>
                                    <div class="text-xs sm:text-sm text-gray-400">Free Previews</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl sm:text-2xl font-bold text-chef-gold">Beginner</div>
                                    <div class="text-xs sm:text-sm text-gray-400">Level</div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                                @if($firstLesson)
                                    <x-app.button 
                                        variant="primary" 
                                        class="w-full sm:w-auto justify-center"
                                        onclick="window.location.href='{{ route('courses.lessons.show', [$course->slug, $firstLesson->slug]) }}'"
                                    >
                                        Start Learning
                                    </x-app.button>
                                @endif
                                <x-app.button variant="secondary" class="w-full sm:w-auto justify-center">
                                    Add to Wishlist
                                </x-app.button>
                            </div>
                        </div>
                    </div>
                </x-app.card>
            </div>

            <!-- Lessons List -->
            <div class="mb-6 sm:mb-8">
                <h3 class="text-xl sm:text-2xl font-bold text-white mb-4 sm:mb-6">Course Content</h3>
                
                <div class="space-y-3 sm:space-y-4">
                    @forelse($course->publishedLessons as $index => $lesson)
                        <x-app.card hover="true" class="group cursor-pointer transition-all duration-300">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                                <div class="flex items-start sm:items-center space-x-3 sm:space-x-4">
                                    <!-- Lesson Number -->
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-chef-gold rounded-xl sm:rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <span class="text-chef-black font-bold text-base sm:text-lg">{{ $index + 1 }}</span>
                                    </div>

                                    <!-- Lesson Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 mb-2 space-y-1 sm:space-y-0">
                                            <h4 class="text-base sm:text-lg font-semibold text-white break-words">{{ $lesson->title }}</h4>
                                            @if($lesson->is_free_preview)
                                                <x-app.badge variant="gold" size="sm" class="self-start sm:self-auto">Free Preview</x-app.badge>
                                            @endif
                                        </div>
                                        
                                        <div class="flex flex-col sm:flex-row sm:items-center space-y-1 sm:space-y-0 sm:space-x-4 text-xs sm:text-sm text-gray-400">
                                            <div class="flex items-center space-x-1">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ $lesson->duration_formatted }}</span>
                                            </div>
                                            <div class="flex items-center space-x-1">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>Video Lesson</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="flex items-center justify-end sm:justify-start">
                                    @if($lesson->is_free_preview || auth()->user()->isAdmin())
                                        <x-app.button 
                                            variant="primary" 
                                            size="sm"
                                            class="w-full sm:w-auto justify-center"
                                            onclick="window.location.href='{{ route('courses.lessons.show', [$course->slug, $lesson->slug]) }}'"
                                        >
                                            {{ $index === 0 ? 'Start' : 'Watch' }}
                                        </x-app.button>
                                    @else
                                        <div class="flex items-center space-x-2 text-gray-400 w-full sm:w-auto justify-center sm:justify-start">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            <span class="text-sm">Locked</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </x-app.card>
                    @empty
                        <x-app.card>
                            <div class="text-center py-6 sm:py-8">
                                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-chef-gray-light rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <h4 class="text-base sm:text-lg font-semibold text-white mb-2">No Lessons Available</h4>
                                <p class="text-gray-400 text-sm sm:text-base">This course doesn't have any lessons yet.</p>
                            </div>
                        </x-app.card>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
