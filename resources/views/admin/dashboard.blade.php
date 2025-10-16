<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Admin Dashboard') }} - Command Center
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <x-app.card>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-chef-gold">{{ $stats['total_users'] ?? App\Models\User::count() }}</div>
                        <div class="text-sm text-gray-400">Total Users</div>
                    </div>
                </x-app.card>
                
                <x-app.card hover="true" class="group">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-chef-gold group-hover:text-chef-gold-light transition-colors duration-300">{{ $stats['total_courses'] ?? App\Models\Course::count() }}</div>
                        <div class="text-sm text-gray-400">Total Courses</div>
                    </div>
                </x-app.card>
                
                <x-app.card hover="true" class="group">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-chef-gold group-hover:text-chef-gold-light transition-colors duration-300">{{ $stats['total_lessons'] ?? App\Models\Lesson::count() }}</div>
                        <div class="text-sm text-gray-400">Total Lessons</div>
                    </div>
                </x-app.card>
                
                <x-app.card hover="true" class="group">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-chef-gold group-hover:text-chef-gold-light transition-colors duration-300">{{ $stats['published_courses'] ?? App\Models\Course::where('is_published', true)->count() }}</div>
                        <div class="text-sm text-gray-400">Published Courses</div>
                    </div>
                </x-app.card>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <x-app.card>
                    <h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.courses.create') }}" class="w-full bg-chef-gold hover:bg-chef-gold/80 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black px-6 py-3 rounded-2xl text-white">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create New Course
                        </a>
                        
                        <a href="{{ route('admin.users.create') }}" class="w-full bg-blue-600 hover:bg-blue-700 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black px-6 py-3 rounded-2xl text-white">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Add New User
                        </a>
                    </div>
                </x-app.card>

                <x-app.card>
                    <h3 class="text-lg font-semibold text-white mb-4">Recent Activity</h3>
                    <div class="space-y-3">
                        @forelse($recentUsers ?? App\Models\User::latest()->take(3)->get() as $user)
                            <div class="flex items-center justify-between p-3 bg-gray-800/50 rounded-xl">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-gray-700 flex items-center justify-center mr-3">
                                        <span class="text-xs font-medium text-white">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <x-app.badge class="{{ $user->role === 'admin' ? 'bg-purple-600' : 'bg-blue-600' }} text-white text-xs">
                                    {{ ucfirst($user->role) }}
                                </x-app.badge>
                            </div>
                        @empty
                            <div class="text-gray-400 text-sm">No recent users</div>
                        @endforelse
                    </div>
                </x-app.card>
            </div>

            <!-- Management Links -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-app.card>
                    <h3 class="text-lg font-semibold text-white mb-4">Content Management</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.courses.index') }}" class="w-full bg-chef-gray-light hover:bg-chef-gray inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black px-6 py-3 rounded-2xl text-white">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Manage Courses
                        </a>
                    </div>
                </x-app.card>

                <x-app.card>
                    <h3 class="text-lg font-semibold text-white mb-4">User Management</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.users.index') }}" class="w-full bg-chef-gray-light hover:bg-chef-gray inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black px-6 py-3 rounded-2xl text-white">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Manage Users
                        </a>
                    </div>
                </x-app.card>
            </div>
        </div>
    </div>
</x-app-layout>
