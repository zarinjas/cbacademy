<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-app.input 
                id="email" 
                type="email" 
                name="email" 
                label="Email Address"
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" 
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-app.input 
                id="password" 
                type="password" 
                name="password" 
                label="Password"
                required 
                autocomplete="current-password" 
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-chef-gray-light text-chef-gold shadow-sm focus:ring-chef-gold bg-chef-gray-light" name="remember">
                <span class="ms-2 text-sm text-gray-300">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-center">
            <x-app.button type="submit" variant="primary" class="w-full">
                {{ __('Sign In') }}
            </x-app.button>
        </div>
    </form>
</x-guest-layout>
