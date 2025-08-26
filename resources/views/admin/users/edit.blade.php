<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit User') }}: {{ $user->name }}
            </h2>
            <x-app.button href="{{ route('admin.users.index') }}" class="bg-gray-600 hover:bg-gray-700">
                {{ __('Back to Users') }}
            </x-app.button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-app.card>
                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-white mb-2">
                            Full Name *
                        </label>
                        <x-app.input 
                            id="name" 
                            name="name" 
                            type="text" 
                            value="{{ old('name', $user->name) }}" 
                            required 
                            class="w-full"
                            placeholder="Enter full name">
                        </x-app.input>
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-white mb-2">
                            Email Address *
                        </label>
                        <x-app.input 
                            id="email" 
                            name="email" 
                            type="email" 
                            value="{{ old('email', $user->email) }}" 
                            required 
                            class="w-full"
                            placeholder="user@example.com">
                        </x-app.input>
                        @error('email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-white mb-2">
                            New Password
                        </label>
                        <x-app.input 
                            id="password" 
                            name="password" 
                            type="password" 
                            class="w-full"
                            placeholder="Leave blank to keep current password">
                        </x-app.input>
                        <p class="mt-1 text-sm text-gray-400">Leave blank to keep the current password</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-white mb-2">
                            User Role *
                        </label>
                        <select 
                            id="role" 
                            name="role" 
                            required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-gold focus:border-transparent">
                            <option value="">Select a role</option>
                            <option value="learner" {{ old('role', $user->role) === 'learner' ? 'selected' : '' }}>Learner</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-4 pt-6">
                        <x-app.button type="button" 
                                      onclick="window.history.back()" 
                                      class="bg-gray-600 hover:bg-gray-700">
                            {{ __('Cancel') }}
                        </x-app.button>
                        <x-app.button type="submit" class="bg-gold hover:bg-gold/80">
                            {{ __('Update User') }}
                        </x-app.button>
                    </div>
                </form>
            </x-app-card>
        </div>
    </div>
</x-app-layout>
