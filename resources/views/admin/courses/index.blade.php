<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Course Management') }}
            </h2>
            <x-app.button href="{{ route('admin.courses.create') }}" class="bg-gold hover:bg-gold/80">
                {{ __('Create New Course') }}
            </x-app.button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-app.card>
                <div class="overflow-x-auto">
                    <x-app.table>
                        <x-slot name="header">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                    Course
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                    Lessons
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
                            @forelse($courses as $course)
                                <tr class="hover:bg-gray-800/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($course->hero_image_url)
                                                <img class="h-12 w-12 rounded-lg object-cover mr-4" 
                                                     src="{{ $course->hero_image_url }}" 
                                                     alt="{{ $course->title }}">
                                            @else
                                                <div class="h-12 w-12 rounded-lg bg-gray-700 flex items-center justify-center mr-4">
                                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-white">{{ $course->title }}</div>
                                                @if($course->description)
                                                    <div class="text-sm text-gray-400">{{ Str::limit($course->description, 50) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $course->lessons->count() }} lessons
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $course->display_order }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($course->is_published)
                                            <x-app.badge class="bg-green-600 text-white">
                                                Published
                                            </x-app.badge>
                                        @else
                                            <x-app.badge class="bg-gray-600 text-white">
                                                Draft
                                            </x-app.badge>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <x-app.button href="{{ route('admin.courses.show', $course) }}" 
                                                          class="bg-blue-600 hover:bg-blue-700 text-xs px-3 py-1">
                                                View
                                            </x-app.button>
                                            <x-app.button href="{{ route('admin.courses.edit', $course) }}" 
                                                          class="bg-yellow-600 hover:bg-yellow-700 text-xs px-3 py-1">
                                                Edit
                                            </x-app.button>
                                            <form action="{{ route('admin.courses.toggle-publish', $course) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <x-app.button type="submit" 
                                                              class="{{ $course->is_published ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-xs px-3 py-1">
                                                    {{ $course->is_published ? 'Unpublish' : 'Publish' }}
                                                </x-app.button>
                                            </form>
                                            <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this course? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <x-app.button type="submit" class="bg-red-600 hover:bg-red-700 text-xs px-3 py-1">
                                                    Delete
                                                </x-app.button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                                        No courses found. <a href="{{ route('admin.courses.create') }}" class="text-gold hover:underline">Create your first course</a>
                                    </td>
                                </tr>
                            @endforelse
                        </x-slot>
                    </x-app.table>
                </div>
            </x-app.card>
        </div>
    </div>
</x-app-layout>
