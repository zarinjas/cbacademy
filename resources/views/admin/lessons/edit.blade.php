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
                    @method('PATCH')
                    
                    <!-- Hidden course_id field -->
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    
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
                            Content *
                        </label>
                        <textarea 
                            id="content" 
                            name="content" 
                            rows="6" 
                            required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                            placeholder="Enter the lesson content...">{{ old('content', $lesson->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Video Type Selection -->
                    <div>
                        <label for="video_type" class="block text-sm font-medium text-white mb-2">
                            Video Type *
                        </label>
                        <select 
                            id="video_type" 
                            name="video_type" 
                            required
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                            onchange="toggleVideoUrlField()">
                            <option value="">Select video type</option>
                            <option value="youtube" {{ old('video_type', $lesson->video_type) === 'youtube' ? 'selected' : '' }}>YouTube Video</option>
                            <option value="google_drive" {{ old('video_type', $lesson->video_type) === 'google_drive' ? 'selected' : '' }}>Google Drive Video</option>
                            <option value="local" {{ old('video_type', $lesson->video_type) === 'local' ? 'selected' : '' }}>Local File</option>
                        </select>
                        @error('video_type')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- YouTube URL Field -->
                    <div id="youtube_url_field" style="display: none;">
                        <label for="youtube_url" class="block text-sm font-medium text-white mb-2">
                            YouTube URL *
                        </label>
                        <input 
                            id="youtube_url" 
                            name="youtube_url" 
                            type="url" 
                            value="{{ old('youtube_url', $lesson->youtube_url) }}" 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                            placeholder="https://youtu.be/VIDEO_ID or https://www.youtube.com/watch?v=VIDEO_ID">
                        
                        <!-- Security Notice for YouTube -->
                        <div class="mt-3 p-3 bg-red-900/20 border border-red-600/30 rounded-xl">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-red-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-semibold text-red-300 mb-1">YouTube Security Features</h4>
                                    <ul class="text-xs text-red-200 space-y-1">
                                        <li>• Videos are embedded with security measures</li>
                                        <li>• External YouTube elements are blocked</li>
                                        <li>• Users cannot click through to YouTube</li>
                                        <li>• Video controls remain fully functional</li>
                                        <li>• ✅ Supported formats: youtu.be, youtube.com/watch, youtube.com/embed</li>
                                        <li>• 🔒 Automatic security: modestbranding, no related videos, playsinline</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        @error('youtube_url')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Google Drive URL Field -->
                    <div id="google_drive_url_field" style="display: none;">
                        <label for="google_drive_url" class="block text-sm font-medium text-white mb-2">
                            Google Drive URL *
                        </label>
                        <input 
                            id="google_drive_url" 
                            name="google_drive_url" 
                            type="url" 
                            value="{{ old('google_drive_url', $lesson->google_drive_url) }}" 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                            placeholder="https://drive.google.com/file/d/...">
                        
                        <!-- Security Notice for Google Drive -->
                        <div class="mt-3 p-3 bg-blue-900/20 border border-blue-600/30 rounded-xl">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-semibold text-blue-300 mb-1">Security Recommendations</h4>
                                    <ul class="text-xs text-blue-200 space-y-1">
                                        <li>• Set file sharing to <strong>"Anyone with the link can view"</strong></li>
                                        <li>• Use <strong>unlisted</strong> sharing for better content protection</li>
                                        <li>• The video URL will be hidden from users in the player</li>
                                        <li>• Users can only watch videos on your site</li>
                                        <li>• ✅ Correct: <code class="bg-gray-700 px-1 rounded">https://drive.google.com/file/d/FILE_ID/view</code></li>
                                        <li>• ❌ Wrong: <code class="bg-gray-700 px-1 rounded">https://drive.google.com/uc?export=download&id=FILE_ID</code></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        @error('google_drive_url')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Local filename field -->
                    <div id="local_filename_field" style="display: none;">
                        <label for="local_filename" class="block text-sm font-medium text-white mb-2">
                            Local filename (example: 1.mp4) *
                        </label>
                        <input 
                            id="local_filename" 
                            name="local_filename" 
                            type="text" 
                            value="{{ old('local_filename', $lesson->local_filename) }}" 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                            placeholder="1.mp4">
                        @error('local_filename')
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

    <script>
        function toggleVideoUrlField() {
            const videoType = document.getElementById('video_type').value;
            const youtubeUrlField = document.getElementById('youtube_url_field');
            const googleDriveUrlField = document.getElementById('google_drive_url_field');
            const localFilenameField = document.getElementById('local_filename_field');

            if (videoType === 'youtube') {
                youtubeUrlField.style.display = 'block';
                googleDriveUrlField.style.display = 'none';
                if (localFilenameField) localFilenameField.style.display = 'none';
            } else if (videoType === 'google_drive') {
                youtubeUrlField.style.display = 'none';
                googleDriveUrlField.style.display = 'block';
                if (localFilenameField) localFilenameField.style.display = 'none';
            } else if (videoType === 'local') {
                youtubeUrlField.style.display = 'none';
                googleDriveUrlField.style.display = 'none';
                if (localFilenameField) localFilenameField.style.display = 'block';
            } else {
                youtubeUrlField.style.display = 'none';
                googleDriveUrlField.style.display = 'none';
                if (localFilenameField) localFilenameField.style.display = 'none';
            }
        }

        // Initial call to set the correct field visibility on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleVideoUrlField();
        });
    </script>

</x-app-layout>
