<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('User Management') }}
            </h2>
            <x-app.button href="{{ route('admin.users.create') }}" class="bg-gold hover:bg-gold/80">
                {{ __('Create New User') }}
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
                                    User
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                    Role
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                    Profile Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                    Joined
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </x-slot>
                        <x-slot name="body">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-800/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center mr-4">
                                                <span class="text-sm font-medium text-white">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-white">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-400">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->role === 'admin')
                                            <x-app.badge class="bg-purple-600 text-white">
                                                Admin
                                            </x-app.badge>
                                        @else
                                            <x-app.badge class="bg-blue-600 text-white">
                                                Learner
                                            </x-app.badge>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->viewerProfile)
                                            <x-app.badge class="bg-green-600 text-white">
                                                Complete
                                            </x-app.badge>
                                        @else
                                            <x-app.badge class="bg-yellow-600 text-white">
                                                Pending
                                            </x-app.badge>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <x-app.button href="{{ route('admin.users.show', $user) }}" 
                                                          class="bg-blue-600 hover:bg-blue-700 text-xs px-3 py-1">
                                                View
                                            </x-app.button>
                                            <x-app.button href="{{ route('admin.users.edit', $user) }}" 
                                                          class="bg-yellow-600 hover:bg-yellow-700 text-xs px-3 py-1">
                                                Edit
                                            </x-app.button>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.toggle-role', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <x-app.button type="submit" 
                                                                  class="bg-indigo-600 hover:bg-indigo-700 text-xs px-3 py-1">
                                                        Toggle Role
                                                    </x-app.button>
                                                </form>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-app.button type="submit" class="bg-red-600 hover:bg-red-700 text-xs px-3 py-1">
                                                        Delete
                                                    </x-app.button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                                        No users found. <a href="{{ route('admin.users.create') }}" class="text-gold hover:underline">Create your first user</a>
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
