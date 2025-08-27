<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Lesson') }}: {{ $lesson->title }}
            </h2>
            <a href="{{ route('admin.courses.show', $course) }}" class="bg-gray-600 hover:bg-gray-700 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black px-6 py-3 rounded-2xl text-white">
                {{ __('Back to Course') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-app.card>
                <form action="{{ route('admin.lessons.update', [$course, $lesson]) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <x-app.validation-errors />
                    
                    <div>
                        <label for="title" class="block text-sm font-medium text-white mb-2">
                            Lesson Title *
                        </label>
                        <input 
                            id="title" 
                            name="title" 
                            type="text" 
                            value="{{ old('title', $lesson->title) }}" 
                            required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                            placeholder="Enter lesson title">
                        @error('title')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-white mb-2">
                            Lesson Content *
                        </label>
                        <textarea 
                            id="content" 
                            name="content" 
                            rows="4" 
                            required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                            placeholder="Enter lesson content or description">{{ old('content', $lesson->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="youtube_url" class="block text-sm font-medium text-white mb-2">
                            YouTube URL *
                        </label>
                        <input 
                            id="youtube_url" 
                            name="youtube_url" 
                            type="url" 
                            value="{{ old('youtube_url', $lesson->youtube_url) }}" 
                            required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                            placeholder="https://www.youtube.com/watch?v=...">
                        <p class="mt-1 text-sm text-gray-400">Enter the full YouTube video URL</p>
                        @error('youtube_url')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration_seconds" class="block text-sm font-medium text-white mb-2">
                            Duration (seconds)
                        </label>
                        <input 
                            id="duration_seconds" 
                            name="duration_seconds" 
                            type="number" 
                            value="{{ old('duration_seconds', $lesson->duration_seconds) }}" 
                            min="0" 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                            placeholder="3600 (for 1 hour)">
                        <p class="mt-1 text-sm text-gray-400">Optional: Enter duration in seconds (e.g., 3600 for 1 hour)</p>
                        @error('duration_seconds')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="display_order" class="block text-sm font-medium text-white mb-2">
                            Display Order *
                        </label>
                        <input 
                            id="display_order" 
                            name="display_order" 
                            type="number" 
                            value="{{ old('display_order', $lesson->display_order) }}" 
                            min="0" 
                            required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                            placeholder="1">
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
                                {{ old('is_free_preview', $lesson->is_free_preview) ? 'checked' : '' }}
                                class="h-4 w-4 text-chef-gold focus:ring-chef-gold border-gray-600 rounded bg-gray-800">
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
                                {{ old('is_published', $lesson->is_published) ? 'checked' : '' }}
                                class="h-4 w-4 text-chef-gold focus:ring-chef-gold border-gray-600 rounded bg-gray-800">
                            <label for="is_published" class="ml-2 text-sm text-white">
                                Publish this lesson
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 pt-6">
                        <button type="button" 
                                onclick="window.history.back()" 
                                class="bg-gray-600 hover:bg-gray-700 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black px-6 py-3 rounded-2xl text-white">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="bg-chef-gold hover:bg-chef-gold/80 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black px-6 py-3 rounded-2xl text-white">
                            {{ __('Update Lesson') }}
                        </button>
                    </div>
                </form>
            </x-app.card>
        </div>
    </div>
</x-app-layout>
