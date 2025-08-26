<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }} - DEBUG: This is the USER view
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Debug Info -->
            <div class="mb-6 p-4 bg-blue-900/20 border border-blue-500 rounded-2xl">
                <h3 class="text-blue-400 font-bold mb-2">🔍 DEBUG INFO:</h3>
                <p class="text-blue-300 text-sm">Current Route: {{ request()->route()->getName() }}</p>
                <p class="text-blue-300 text-sm">Current URL: {{ request()->url() }}</p>
                <p class="text-blue-300 text-sm">User Role: {{ auth()->user()->role }}</p>
                <p class="text-blue-300 text-sm">Is Admin: {{ auth()->user()->isAdmin() ? 'YES' : 'NO' }}</p>
                <p class="text-blue-300 text-sm">View: dashboard (user)</p>
            </div>

            <!-- Welcome Section -->
            <div class="mb-8">
                <x-app.card>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-chef-gold rounded-3xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-chef-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-300">Ready to continue your culinary journey?</p>
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
                        <x-app.card class="hover:shadow-soft-lg transition-all duration-300 transform hover:scale-105">
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
                                    <p class="text-gray-400">Check back soon for exciting new courses!</p>
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
</x-app-layout>
