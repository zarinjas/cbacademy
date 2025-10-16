<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Complete Your Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-app.card>
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-chef-gold rounded-3xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-chef-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Welcome to ChefBull Academy!</h3>
                    <p class="text-gray-300">Please complete your profile to continue learning</p>
                </div>

                <form method="POST" action="{{ route('profile.store') }}" class="space-y-6">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <x-app.input 
                            id="full_name" 
                            type="text" 
                            name="full_name" 
                            label="Full Name"
                            :value="old('full_name', $profile?->full_name ?? '')" 
                            required 
                            autofocus 
                            placeholder="Enter your full name"
                        />
                        @error('full_name')
                            <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <x-app.input 
                            id="phone" 
                            type="tel" 
                            name="phone" 
                            label="Phone Number"
                            :value="old('phone', $profile?->phone ?? '')" 
                            required 
                            placeholder="e.g., 012-345-6789"
                        />
                        <p class="text-sm text-gray-400 mt-1">Please enter a valid Malaysian phone number</p>
                        @error('phone')
                            <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <x-app.button type="submit" variant="primary" class="w-full">
                            {{ __('Complete Profile & Continue') }}
                        </x-app.button>
                    </div>
                </form>

                <!-- Help Text -->
                <div class="mt-8 p-4 bg-chef-gray-light rounded-2xl border border-chef-gray">
                    <h4 class="font-semibold text-chef-gold mb-2">Why do we need this information?</h4>
                    <ul class="text-sm text-gray-300 space-y-1">
                        <li>• To personalize your learning experience</li>
                        <li>• To provide better customer support</li>
                        <li>• To ensure account security</li>
                        <li>• To comply with educational regulations</li>
                    </ul>
                </div>
            </x-app.card>
        </div>
    </div>
</x-app-layout>
