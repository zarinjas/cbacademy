<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Admin Settings') }} - System Configuration
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-900/20 border border-green-500 rounded-2xl">
                    <p class="text-green-400">{{ session('success') }}</p>
                </div>
            @endif

            <x-app.validation-errors />

            <!-- Shared Learner Credentials -->
            <x-app.card>
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-white mb-2">Shared Learner Account</h3>
                    <p class="text-gray-400 text-sm">Manage the credentials for the shared learner account that all learners can use to access the platform.</p>
                </div>

                <form action="{{ route('admin.settings.update-learner-credentials') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="learner_email" class="block text-sm font-medium text-white mb-2">
                            Email Address (Read-only)
                        </label>
                        <input type="email" 
                               id="learner_email" 
                               value="access@chefbullacademy.local" 
                               class="w-full px-4 py-3 bg-chef-gray-light border border-chef-gray rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                               disabled
                        >
                        <p class="text-gray-400 text-xs mt-1">This email cannot be changed as it's used by the system.</p>
                    </div>

                    <div>
                        <label for="learner_name" class="block text-sm font-medium text-white mb-2">
                            Display Name
                        </label>
                        <input type="text" 
                               id="learner_name" 
                               name="learner_name" 
                               value="{{ old('learner_name', $sharedLearner->name ?? '') }}"
                               class="w-full px-4 py-3 bg-chef-gray-light border border-chef-gray rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                               placeholder="Enter display name for shared learner"
                               required
                        >
                    </div>

                    <div>
                        <label for="learner_password" class="block text-sm font-medium text-white mb-2">
                            New Password (Optional)
                        </label>
                        <input type="password" 
                               id="learner_password" 
                               name="learner_password" 
                               class="w-full px-4 py-3 bg-chef-gray-light border border-chef-gray rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                               placeholder="Leave blank to keep current password"
                               minlength="8"
                        >
                        <p class="text-gray-400 text-xs mt-1">Minimum 8 characters. Leave blank to keep the current password.</p>
                    </div>

                    <div>
                        <label for="learner_password_confirmation" class="block text-sm font-medium text-white mb-2">
                            Confirm New Password
                        </label>
                        <input type="password" 
                               id="learner_password_confirmation" 
                               name="learner_password_confirmation" 
                               class="w-full px-4 py-3 bg-chef-gray-light border border-chef-gray rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
                               placeholder="Confirm the new password"
                        >
                    </div>

                    <div class="pt-4">
                        <x-app.button type="submit" variant="primary" class="w-full">
                            Update Shared Learner Credentials
                        </x-app.button>
                    </div>
                </form>
            </x-app.card>

            <!-- Current Credentials Info -->
            <x-app.card>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-white mb-2">Current Credentials</h3>
                    <p class="text-gray-400 text-sm">These are the current credentials that learners can use to access the platform.</p>
                </div>
                
                <div class="bg-chef-gray-light rounded-2xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Email:</span>
                        <span class="text-white font-mono">access@chefbullacademy.local</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Current Name:</span>
                        <span class="text-white">{{ $sharedLearner->name ?? 'Not set' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Current Password:</span>
                        <span class="text-gray-400">•••••••• (hidden for security)</span>
                    </div>
                </div>
            </x-app.card>
        </div>
    </div>
</x-app-layout>
