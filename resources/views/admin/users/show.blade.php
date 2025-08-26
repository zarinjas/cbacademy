<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('User Details') }}: {{ $user->name }}
            </h2>
            <div class="flex space-x-3">
                <x-app.button href="{{ route('admin.users.edit', $user) }}" class="bg-yellow-600 hover:bg-yellow-700">
                    {{ __('Edit User') }}
                </x-app.button>
                <x-app.button href="{{ route('admin.users.index') }}" class="bg-gray-600 hover:bg-gray-700">
                    {{ __('Back to Users') }}
                </x-app.button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- User Information -->
            <x-app.card>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-white mb-4">User Information</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-gray-400">Name:</span>
                                <span class="text-white ml-2">{{ $user->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400">Email:</span>
                                <span class="text-white ml-2">{{ $user->email }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400">Role:</span>
                                @if($user->role === 'admin')
                                    <x-app.badge class="bg-purple-600 text-white ml-2">Admin</x-app.badge>
                                @else
                                    <x-app.badge class="bg-blue-600 text-white ml-2">Learner</x-app.badge>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-4">Account Status</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-gray-400">Status:</span>
                                @if($user->viewerProfile)
                                    <x-app.badge class="bg-green-600 text-white ml-2">Profile Complete</x-app.badge>
                                @else
                                    <x-app.badge class="bg-yellow-600 text-white ml-2">Profile Pending</x-app.badge>
                                @endif
                            </div>
                            <div>
                                <span class="text-gray-400">Joined:</span>
                                <span class="text-white ml-2">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400">Last Updated:</span>
                                <span class="text-white ml-2">{{ $user->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-app.card>

            <!-- Profile Information -->
            @if($user->viewerProfile)
                <x-app.card>
                    <h3 class="text-lg font-semibold text-white mb-4">Profile Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-gray-400">Phone:</span>
                                    <span class="text-white ml-2">{{ $user->viewerProfile->phone ?? 'Not provided' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400">Date of Birth:</span>
                                    <span class="text-white ml-2">
                                        {{ $user->viewerProfile->date_of_birth ? $user->viewerProfile->date_of_birth->format('M d, Y') : 'Not provided' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-400">Gender:</span>
                                    <span class="text-white ml-2">{{ $user->viewerProfile->gender ?? 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-gray-400">Country:</span>
                                    <span class="text-white ml-2">{{ $user->viewerProfile->country ?? 'Not provided' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400">City:</span>
                                    <span class="text-white ml-2">{{ $user->viewerProfile->city ?? 'Not provided' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400">Profile Created:</span>
                                    <span class="text-white ml-2">{{ $user->viewerProfile->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-app.card>
            @else
                <x-app.card>
                    <div class="text-center py-8">
                        <div class="text-gray-400 mb-4">This user hasn't completed their profile yet</div>
                        <x-app.badge class="bg-yellow-600 text-white">Profile Pending</x-app.badge>
                    </div>
                </x-app.card>
            @endif

            <!-- Quick Actions -->
            @if($user->id !== auth()->id())
                <x-app.card>
                    <h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
                    <div class="flex space-x-4">
                        <form action="{{ route('admin.users.toggle-role', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <x-app.button type="submit" class="bg-indigo-600 hover:bg-indigo-700">
                                {{ $user->role === 'admin' ? 'Make Learner' : 'Make Admin' }}
                            </x-app.button>
                        </form>
                        
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" 
                              onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <x-app.button type="submit" class="bg-red-600 hover:bg-red-700">
                                Delete User
                            </x-app.button>
                        </form>
                    </div>
                </x-app.card>
            @endif
        </div>
    </div>
</x-app-layout>
