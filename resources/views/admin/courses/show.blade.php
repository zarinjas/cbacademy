<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ $course->title }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.lessons.create', $course) }}" class="bg-chef-gold hover:bg-chef-gold/80 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black px-6 py-3 rounded-2xl text-white">
                    {{ __('Add Lesson') }}
                </a>
                <a href="{{ route('admin.courses.edit', $course) }}" class="bg-yellow-600 hover:bg-yellow-700 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black px-6 py-3 rounded-2xl text-white">
                    {{ __('Edit Course') }}
                </a>
                <a href="{{ route('admin.courses.index') }}" class="bg-gray-600 hover:bg-gray-700 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black px-6 py-3 rounded-2xl text-white">
                    {{ __('Back to Courses') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Course Details -->
            <x-app.card>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-white mb-4">Course Information</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-gray-400">Title:</span>
                                <span class="text-white ml-2">{{ $course->title }}</span>
                            </div>
                            @if($course->description)
                                <div>
                                    <span class="text-gray-400">Description:</span>
                                    <p class="text-white ml-2 mt-1">{{ $course->description }}</p>
                                </div>
                            @endif
                            <div>
                                <span class="text-gray-400">Slug:</span>
                                <span class="text-white ml-2">{{ $course->slug }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400">Display Order:</span>
                                <span class="text-white ml-2">{{ $course->display_order }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-4">Course Status</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-gray-400">Status:</span>
                                @if($course->is_published)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-600 text-white ml-2">Published</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-600 text-white ml-2">Draft</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-gray-400">Created:</span>
                                <span class="text-white ml-2">{{ $course->created_at->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400">Updated:</span>
                                <span class="text-white ml-2">{{ $course->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-app.card>

            <!-- Lessons Management -->
            <x-app.card>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-white">Lessons ({{ $course->lessons->count() }})</h3>
                    <a href="{{ route('admin.lessons.create', $course) }}" class="bg-chef-gold hover:bg-chef-gold/80 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black px-6 py-3 rounded-2xl text-white">
                        {{ __('Add New Lesson') }}
                    </a>
                </div>

                @if($course->lessons->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-chef-gray-light">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Lesson
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Duration
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Order
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-chef-gray-light">
                                @foreach($course->lessons as $lesson)
                                    <tr class="hover:bg-chef-gray-lighter/50 transition-colors duration-200 {{ $lesson->is_published ? 'border-l-4 border-chef-gold' : 'border-l-4 border-transparent' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-lg bg-gray-700 flex items-center justify-center mr-4">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-white">{{ $lesson->title }}</div>
                                                    <div class="text-sm text-gray-400">{{ Str::limit($lesson->youtube_url, 40) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            @if($lesson->duration_seconds)
                                                {{ gmdate('H:i:s', $lesson->duration_seconds) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $lesson->display_order }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="space-y-1">
                                                @if($lesson->is_published)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-600 text-white">Published</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-600 text-white">Draft</span>
                                                @endif
                                                @if($lesson->is_free_preview)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-600 text-white">Free Preview</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.lessons.edit', [$course, $lesson]) }}" 
                                                   class="bg-yellow-600 hover:bg-yellow-700 text-xs px-3 py-1 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black rounded-xl text-white">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.lessons.toggle-publish', [$course, $lesson]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="{{ $lesson->is_published ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-xs px-3 py-1 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black rounded-xl text-white">
                                                        {{ $lesson->is_published ? 'Unpublish' : 'Publish' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.lessons.toggle-free-preview', [$course, $lesson]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="{{ $lesson->is_free_preview ? 'bg-orange-600 hover:bg-orange-700' : 'bg-blue-600 hover:bg-blue-700' }} text-xs px-3 py-1 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black rounded-xl text-white">
                                                        {{ $lesson->is_free_preview ? 'Remove Preview' : 'Make Preview' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.lessons.destroy', [$course, $lesson]) }}" method="POST" class="inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this lesson? This action cannot be undone.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-xs px-3 py-1 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black rounded-xl text-white">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 mb-4">No lessons yet</div>
                        <a href="{{ route('admin.lessons.create', $course) }}" class="bg-chef-gold hover:bg-chef-gold/80 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black px-6 py-3 rounded-2xl text-white">
                            {{ __('Create First Lesson') }}
                        </a>
                    </div>
                @endif
            </x-app.card>
        </div>
    </div>
</x-app-layout>
