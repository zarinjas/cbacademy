<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Profile') }} - Shared Learner Account
        </h2>
    </x-slot>

    <div class="py-12">
        <!-- Toast Messages -->
        @if(session('status') === 'profile-updated')
            <x-app.toast type="success" message="Profile updated successfully!" :show="true" />
        @endif
        
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Information -->
            <x-app.card>
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-white mb-2">Profile Information</h3>
                    <p class="text-gray-400 text-sm">This is a shared learner account. You can update the display name, but the email and password are managed by administrators.</p>
                </div>
                
                <x-app.validation-errors />
                
                @include('profile.partials.update-profile-information-form')
            </x-app.card>

            <!-- Account Information -->
            <x-app.card>
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-white mb-2">Account Information</h3>
                    <p class="text-gray-400 text-sm">This is a shared account used by all learners. Contact an administrator if you need assistance.</p>
                </div>
                
                <div class="bg-chef-gray-light rounded-2xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Email Address:</span>
                        <span class="text-white font-mono">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Account Type:</span>
                        <span class="text-chef-gold font-medium">Shared Learner Account</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Password Management:</span>
                        <span class="text-gray-400">Managed by Administrators</span>
                    </div>
                </div>
            </x-app.card>
        </div>
    </div>
</x-app-layout>
