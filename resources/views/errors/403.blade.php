<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center text-center px-6">
        <!-- Logo -->
        <div class="mb-8">
            <img src="{{ asset('images/cropped-CB-Academy-Logo-08.png') }}" alt="CB Academy Logo" class="h-24 w-auto object-contain mx-auto">
        </div>
        
        <!-- Error Content -->
        <div class="max-w-2xl mx-auto">
            <!-- 403 Number -->
            <div class="text-9xl font-bold text-red-500 mb-6 leading-none">
                403
            </div>
            
            <!-- Error Title -->
            <h1 class="text-4xl font-bold text-white mb-4">
                Access Forbidden
            </h1>
            
            <!-- Error Description -->
            <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                Sorry! You don't have permission to access this area. 
                This is like trying to trade without proper credentials - it's just not allowed.
            </p>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center px-6 py-3 bg-chef-gold text-chef-black font-semibold rounded-2xl hover:bg-yellow-400 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Go to Dashboard
                </a>
                
                <a href="{{ route('courses.index') }}" 
                   class="inline-flex items-center px-6 py-3 border-2 border-chef-gold text-chef-gold font-semibold rounded-2xl hover:bg-chef-gold hover:text-chef-black transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Browse Courses
                </a>
            </div>
            
            <!-- Helpful Tip -->
            <div class="mt-12 p-6 bg-chef-gray border border-chef-gray-light rounded-2xl">
                <div class="flex items-center justify-center space-x-3 mb-3">
                    <div class="w-8 h-8 bg-chef-gold rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-chef-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-chef-gold">Trading Tip</h3>
                </div>
                <p class="text-gray-300">
                    "Always ensure you have the right permissions and credentials before attempting to access restricted areas. 
                    In trading, this means proper account verification and compliance."
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
