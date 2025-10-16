<x-app-layout>
    <x-app.keyboard-nav>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-white leading-tight">
                        {{ __('Dashboard') }} - Learning Hub
                    </h2>
                    <p class="text-gray-400">Welcome back! Ready to continue your trading journey?</p>
                </div>
                <div class="text-xs text-gray-400">
                    Press <kbd class="px-1 py-0.5 bg-chef-gray-light border border-chef-gray-lighter rounded">?</kbd> for shortcuts
                </div>
            </div>
        </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <!-- Welcome Section -->
            <div class="mb-8">
                <x-app.card>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-chef-gold rounded-3xl flex items-center justify-center mx-auto mb-4">
                            <x-app.icon name="user" size="2xl" class="text-chef-black" />
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Welcome back!</h3>
                        <p class="text-gray-300">Ready to continue your trading journey?</p>
                    </div>
                </x-app.card>
            </div>

            <!-- Featured Courses Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-white">Featured Courses</h3>
                    <a href="{{ route('courses.index') }}" class="text-chef-gold hover:text-chef-gold-light transition-colors duration-300">
                        View All Courses →
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($courses->take(3) as $course)
                        <x-app.card hover="true" class="group cursor-pointer transition-all duration-300 transform hover:scale-105">
                            <!-- Course Hero Image -->
                            <div class="relative mb-4">
                                <img 
                                    src="{{ $course->hero_image_url }}" 
                                    alt="{{ $course->title }}"
                                    class="w-full h-32 object-cover rounded-2xl"
                                    onerror="this.src='https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=800&h=600&fit=crop&crop=center'"
                                >
                                @if($course->hasFreePreview())
                                    <div class="absolute top-2 right-2">
                                        <x-app.badge variant="gold" size="sm">Free Preview</x-app.badge>
                                    </div>
                                @endif
                            </div>

                            <!-- Course Content -->
                            <div class="space-y-3">
                                <h4 class="text-lg font-bold text-white">{{ $course->title }}</h4>
                                
                                <p class="text-gray-300 text-sm line-clamp-2">
                                    {{ Str::limit($course->description, 80) }}
                                </p>

                                <!-- Course Stats -->
                                <div class="flex items-center justify-between text-sm text-gray-400">
                                    <div class="flex items-center space-x-2">
                                        <x-app.icon name="course" size="sm" class="text-gray-400" />
                                        <span>{{ $course->getTotalLessonsCount() }} lessons</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <x-app.icon name="time" size="sm" class="text-gray-400" />
                                        <span>{{ $course->getTotalDurationFormatted() }}</span>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="pt-2">
                                    <x-app.button 
                                        variant="primary" 
                                        size="sm"
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
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-chef-gray-light rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-white mb-2">No Courses Available Yet</h4>
                                    <p class="text-gray-400">Check back soon for exciting new trading courses!</p>
                                </div>
                            </x-app.card>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-app.card>
                    <h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('courses.index') }}" class="btn-secondary text-center block">
                            Browse All Courses
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn-secondary text-center block">
                            Edit Profile
                        </a>
                    </div>
                </x-app.card>

                <x-app.card>
                    <h3 class="text-lg font-semibold text-white mb-4">Your Progress</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300">Courses Started</span>
                            <span class="text-chef-gold font-semibold">{{ $userProgress['courses_started'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300">Lessons Completed</span>
                            <span class="text-chef-gold font-semibold">{{ $userProgress['lessons_completed'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300">Total Learning Time</span>
                            <span class="text-chef-gold font-semibold">{{ $userProgress['total_learning_time'] }}h 0m</span>
                        </div>
                    </div>
                </x-app.card>
            </div>
        </div>
    </div>
    </x-app.keyboard-nav>
</x-app-layout>
