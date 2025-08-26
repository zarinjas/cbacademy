<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Add Lesson to') }} "{{ $course->title }}"
            </h2>
            <x-app.button href="{{ route('admin.courses.show', $course) }}" class="bg-gray-600 hover:bg-gray-700">
                {{ __('Back to Course') }}
            </x-app.button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-app.card>
                <form action="{{ route('admin.lessons.store', $course) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="title" class="block text-sm font-medium text-white mb-2">
                            Lesson Title *
                        </label>
                        <x-app.input 
                            id="title" 
                            name="title" 
                            type="text" 
                            value="{{ old('title') }}" 
                            required 
                            class="w-full"
                            placeholder="Enter lesson title">
                        </x-app.input>
                        @error('title')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="youtube_url" class="block text-sm font-medium text-white mb-2">
                            YouTube URL *
                        </label>
                        <x-app.input 
                            id="youtube_url" 
                            name="youtube_url" 
                            type="url" 
                            value="{{ old('youtube_url') }}" 
                            required 
                            class="w-full"
                            placeholder="https://www.youtube.com/watch?v=...">
                        </x-app.input>
                        <p class="mt-1 text-sm text-gray-400">Enter the full YouTube video URL</p>
                        @error('youtube_url')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration_seconds" class="block text-sm font-medium text-white mb-2">
                            Duration (seconds)
                        </label>
                        <x-app.input 
                            id="duration_seconds" 
                            name="duration_seconds" 
                            type="number" 
                            value="{{ old('duration_seconds') }}" 
                            min="0" 
                            class="w-full"
                            placeholder="3600 (for 1 hour)">
                        </x-app.input>
                        <p class="mt-1 text-sm text-gray-400">Optional: Enter duration in seconds (e.g., 3600 for 1 hour)</p>
                        @error('duration_seconds')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="display_order" class="block text-sm font-medium text-white mb-2">
                            Display Order *
                        </label>
                        <x-app.input 
                            id="display_order" 
                            name="display_order" 
                            type="number" 
                            value="{{ old('display_order', $course->lessons->count() + 1) }}" 
                            min="0" 
                            required 
                            class="w-full"
                            placeholder="1">
                        </x-app.input>
                        @error('display_order')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-6">
                        <div class="flex items-center">
                            <input 
                                id="is_free_preview" 
                                name="is_free_preview" 
                                type="checkbox" 
                                value="1" 
                                {{ old('is_free_preview') ? 'checked' : '' }}
                                class="h-4 w-4 text-gold focus:ring-gold border-gray-600 rounded bg-gray-800">
                            <label for="is_free_preview" class="ml-2 text-sm text-white">
                                Make this lesson a free preview
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input 
                                id="is_published" 
                                name="is_published" 
                                type="checkbox" 
                                value="1" 
                                {{ old('is_published') ? 'checked' : '' }}
                                class="h-4 w-4 text-gold focus:ring-gold border-gray-600 rounded bg-gray-800">
                            <label for="is_published" class="ml-2 text-sm text-white">
                                Publish immediately
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 pt-6">
                        <x-app.button type="button" 
                                      onclick="window.history.back()" 
                                      class="bg-gray-600 hover:bg-gray-700">
                            {{ __('Cancel') }}
                        </x-app.button>
                        <x-app.button type="submit" class="bg-gold hover:bg-gold/80">
                            {{ __('Create Lesson') }}
                        </x-app.button>
                    </div>
                </form>
            </x-app-card>
        </div>
    </div>
</x-app-layout>
