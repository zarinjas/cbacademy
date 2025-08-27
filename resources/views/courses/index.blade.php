<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Available Courses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($courses as $course)
                    <x-app.card class="hover:shadow-soft-lg transition-all duration-300 transform hover:scale-105">
                        <!-- Course Hero Image -->
                        <div class="relative mb-4">
                            <img 
                                src="{{ $course->hero_image_url }}" 
                                alt="{{ $course->title }}"
                                class="w-full h-48 object-cover rounded-2xl"
                                onerror="this.src='https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=800&h=600&fit=crop&crop=center'"
                            >
                            @if($course->hasFreePreview())
                                <div class="absolute top-3 right-3">
                                    <x-app.badge variant="gold" size="sm">Free Preview</x-app.badge>
                                </div>
                            @endif
                        </div>

                        <!-- Course Content -->
                        <div class="space-y-3">
                            <h3 class="text-xl font-bold text-white">{{ $course->title }}</h3>
                            
                            <p class="text-gray-300 text-sm line-clamp-3">
                                {{ Str::limit($course->description, 120) }}
                            </p>

                            <!-- Course Stats -->
                            <div class="flex items-center justify-between text-sm text-gray-400">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <span>{{ $course->getTotalLessonsCount() }} lessons</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $course->getTotalDurationFormatted() }}</span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="pt-2">
                                <x-app.button 
                                    variant="primary" 
                                    class="w-full"
                                    onclick="window.location.href='{{ route('courses.show', $course->slug) }}'"
                                >
                                    Start Learning
                                </x-app.button>
                            </div>
                        </div>
                    </x-app.card>
                @empty
                    <div class="col-span-full">
                        <x-app.card>
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-chef-gray-light rounded-3xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-white mb-2">No Courses Available</h3>
                                <p class="text-gray-400">Check back soon for new trading courses!</p>
                            </div>
                        </x-app.card>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
