<form method="post" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

    <div>
        <label for="name" class="block text-sm font-medium text-white mb-2">
            Display Name *
        </label>
        <input 
            id="name" 
            name="name" 
            type="text" 
            value="{{ old('name', $user->name) }}" 
            required 
            autofocus 
            autocomplete="name" 
            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold focus:border-transparent"
            placeholder="Enter your display name">
        @error('name')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-4 pt-4">
        <button type="submit" class="bg-chef-gold hover:bg-chef-gold/80 inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black px-6 py-3 rounded-2xl text-chef-black">
            {{ __('Save Changes') }}
        </button>


    </div>
</form>
