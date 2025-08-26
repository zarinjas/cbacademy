<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ $course->title }}
            </h2>
            <div class="flex space-x-3">
                <x-app.button href="{{ route('admin.lessons.create', $course) }}" class="bg-gold hover:bg-gold/80">
                    {{ __('Add Lesson') }}
                </x-app.button>
                <x-app.button href="{{ route('admin.courses.edit', $course) }}" class="bg-yellow-600 hover:bg-yellow-700">
                    {{ __('Edit Course') }}
                </x-app.button>
                <x-app.button href="{{ route('admin.courses.index') }}" class="bg-gray-600 hover:bg-gray-700">
                    {{ __('Back to Courses') }}
                </x-app.button>
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
                                    <x-app.badge class="bg-green-600 text-white ml-2">Published</x-app.badge>
                                @else
                                    <x-app.badge class="bg-gray-600 text-white ml-2">Draft</x-app.badge>
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
                    <x-app.button href="{{ route('admin.lessons.create', $course) }}" class="bg-gold hover:bg-gold/80">
                        {{ __('Add New Lesson') }}
                    </x-app.button>
                </div>

                @if($course->lessons->count() > 0)
                    <div class="overflow-x-auto">
                        <x-app.table>
                            <x-slot name="header">
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
                            </x-slot>
                            <x-slot name="body">
                                @foreach($course->lessons as $lesson)
                                    <tr class="hover:bg-gray-800/50">
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
                                                    <x-app.badge class="bg-green-600 text-white text-xs">Published</x-app.badge>
                                                @else
                                                    <x-app.badge class="bg-gray-600 text-white text-xs">Draft</x-app.badge>
                                                @endif
                                                @if($lesson->is_free_preview)
                                                    <x-app.badge class="bg-blue-600 text-white text-xs">Free Preview</x-app.badge>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <x-app.button href="{{ route('admin.lessons.edit', [$course, $lesson]) }}" 
                                                              class="bg-yellow-600 hover:bg-yellow-700 text-xs px-3 py-1">
                                                    Edit
                                                </x-app.button>
                                                <form action="{{ route('admin.lessons.toggle-publish', [$course, $lesson]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <x-app.button type="submit" 
                                                                  class="{{ $lesson->is_published ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-xs px-3 py-1">
                                                        {{ $lesson->is_published ? 'Unpublish' : 'Publish' }}
                                                    </x-app.button>
                                                </form>
                                                <form action="{{ route('admin.lessons.toggle-free-preview', [$course, $lesson]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <x-app.button type="submit" 
                                                                  class="{{ $lesson->is_free_preview ? 'bg-orange-600 hover:bg-orange-700' : 'bg-blue-600 hover:bg-blue-700' }} text-xs px-3 py-1">
                                                        {{ $lesson->is_free_preview ? 'Remove Preview' : 'Make Preview' }}
                                                    </x-app.button>
                                                </form>
                                                <form action="{{ route('admin.lessons.destroy', [$course, $lesson]) }}" method="POST" class="inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this lesson? This action cannot be undone.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-app.button type="submit" class="bg-red-600 hover:bg-red-700 text-xs px-3 py-1">
                                                        Delete
                                                    </x-app.button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-app.table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 mb-4">No lessons yet</div>
                        <x-app.button href="{{ route('admin.lessons.create', $course) }}" class="bg-gold hover:bg-gold/80">
                            {{ __('Create First Lesson') }}
                        </x-app.button>
                    </div>
                @endif
            </x-app.card>
        </div>
    </div>
</x-app-layout>
