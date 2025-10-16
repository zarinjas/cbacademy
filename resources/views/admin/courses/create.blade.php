<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Create New Course') }}
            </h2>
            <a href="{{ route('admin.courses.index') }}" class="bg-gray-600 hover:bg-gray-700 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black px-6 py-3 rounded-2xl text-white">
                {{ __('Back to Courses') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-app.card>
                <form action="{{ route('admin.courses.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <x-app.validation-errors />
                    
                    <div>
                        <label for="title" class="block text-sm font-medium text-white mb-2">
                            Course Title *
                        </label>
                        <input 
                            id="title" 
                            name="title" 
                            type="text" 
                            value="{{ old('title') }}" 
                            required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                            placeholder="Enter course title">
                        @error('title')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-white mb-2">
                            Description
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="4" 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                            placeholder="Enter course description">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hero_image_url" class="block text-sm font-medium text-white mb-2">
                            Hero Image URL
                        </label>
                        <input 
                            id="hero_image_url" 
                            name="hero_image_url" 
                            type="url" 
                            value="{{ old('hero_image_url') }}" 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                            placeholder="https://example.com/image.jpg">
                        @error('hero_image_url')
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
                            value="{{ old('display_order', 0) }}" 
                            min="0" 
                            required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                            placeholder="0">
                        @error('display_order')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input 
                                id="is_published" 
                                name="is_published" 
                                type="checkbox" 
                                value="1" 
                                {{ old('is_published') ? 'checked' : '' }}
                                class="h-4 w-4 text-chef-gold focus:ring-chef-gold border-gray-600 rounded bg-gray-800">
                            <label for="is_published" class="ml-2 text-sm text-white">
                                Publish immediately
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
                            {{ __('Create Course') }}
                        </button>
                    </div>
                </form>
            </x-app.card>
        </div>
    </div>
</x-app-layout>
