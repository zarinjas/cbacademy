<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    {{ $lesson->title }}
                </h2>
                <p class="text-gray-400 text-sm mt-1">
                    {{ $course->title }} • Lesson {{ $course->publishedLessons->search($lesson) + 1 }} of {{ $course->publishedLessons->count() }}
                </p>
            </div>
            <a href="{{ route('courses.show', $course->slug) }}" class="text-chef-gold hover:text-chef-gold-light transition-colors duration-300">
                ← Back to Course
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Progress Bar -->
            <div class="mb-8">
                <x-app.card>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-300">Course Progress</span>
                            <span class="text-sm font-medium text-chef-gold">{{ $progressPercentage }}%</span>
                        </div>
                        <div class="w-full bg-chef-gray-light rounded-full h-2">
                            <div class="bg-chef-gold h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-400">
                            <span>{{ $course->publishedLessons->search($lesson) + 1 }} of {{ $course->publishedLessons->count() }} lessons completed</span>
                            <span>{{ $course->getTotalDurationFormatted() }} total duration</span>
                        </div>
                    </div>
                </x-app.card>
            </div>

            <!-- Video Player Section -->
            <div class="mb-8">
                <x-app.card>
                    <div class="space-y-6">
                        <!-- YouTube Video Player -->
                        <div class="relative w-full" style="padding-bottom: 56.25%;">
                            <iframe 
                                src="{{ $lesson->youtube_embed_url }}?rel=0&modestbranding=1" 
                                class="absolute top-0 left-0 w-full h-full rounded-2xl"
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen
                                title="{{ $lesson->title }}"
                            ></iframe>
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

            <!-- Navigation Section -->
            <div class="mb-8">
                <x-app.card>
                    <div class="flex items-center justify-between">
                        <!-- Previous Lesson -->
                        <div class="flex-1">
                            @if($previousLesson)
                                <a href="{{ route('courses.lessons.show', [$course->slug, $previousLesson->slug]) }}" 
                                   class="group flex items-center space-x-3 text-left">
                                    <div class="w-10 h-10 bg-chef-gray-light rounded-xl flex items-center justify-center group-hover:bg-chef-gold transition-colors duration-300">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-chef-black transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-400">Previous Lesson</div>
                                        <div class="text-sm font-medium text-white group-hover:text-chef-gold transition-colors duration-300">
                                            {{ Str::limit($previousLesson->title, 40) }}
                                        </div>
                                    </div>
                                </a>
                            @else
                                <div class="flex items-center space-x-3 text-gray-400">
                                    <div class="w-10 h-10 bg-chef-gray-light rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs">Previous Lesson</div>
                                        <div class="text-sm">No previous lesson</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Course Progress -->
                        <div class="text-center mx-4">
                            <div class="text-2xl font-bold text-chef-gold">{{ $progressPercentage }}%</div>
                            <div class="text-xs text-gray-400">Complete</div>
                        </div>

                        <!-- Next Lesson -->
                        <div class="flex-1 text-right">
                            @if($nextLesson)
                                <a href="{{ route('courses.lessons.show', [$course->slug, $nextLesson->slug]) }}" 
                                   class="group flex items-center justify-end space-x-3">
                                    <div class="text-right">
                                        <div class="text-xs text-gray-400">Next Lesson</div>
                                        <div class="text-sm font-medium text-white group-hover:text-chef-gold transition-colors duration-300">
                                            {{ Str::limit($nextLesson->title, 40) }}
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 bg-chef-gray-light rounded-xl flex items-center justify-center group-hover:bg-chef-gold transition-colors duration-300">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-chef-black transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </a>
                            @else
                                <div class="flex items-center justify-end space-x-3 text-gray-400">
                                    <div class="text-right">
                                        <div class="text-xs">Next Lesson</div>
                                        <div class="text-sm">Course Complete!</div>
                                    </div>
                                    <div class="w-10 h-10 bg-chef-gray-light rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </x-app.card>
            </div>

            <!-- Course Overview -->
            <div>
                <x-app.card>
                    <h3 class="text-xl font-semibold text-white mb-4">Course Overview</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-chef-gold rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-chef-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-white">{{ $course->getTotalLessonsCount() }}</div>
                            <div class="text-sm text-gray-400">Total Lessons</div>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-chef-gold rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-chef-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-white">{{ $course->getTotalDurationFormatted() }}</div>
                            <div class="text-sm text-gray-400">Total Duration</div>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-chef-gold rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-chef-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-white">{{ $course->freePreviewLessons()->count() }}</div>
                            <div class="text-sm text-gray-400">Free Previews</div>
                        </div>
                    </div>
                </x-app.card>
            </div>
        </div>
    </div>
</x-app-layout>
