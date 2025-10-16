@props(['type' => 'success', 'message', 'show' => false])

@if($show)
<div x-data="{ show: true }" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-2"
     class="fixed top-4 right-4 z-50 max-w-sm w-full">
    
    <div class="bg-chef-gray border border-chef-gray-light rounded-2xl shadow-2xl overflow-hidden">
        <!-- Toast Header -->
        <div class="flex items-center justify-between p-4">
            <div class="flex items-center space-x-3">
                @if($type === 'success')
                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                @elseif($type === 'error')
                    <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                @elseif($type === 'warning')
                    <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                @elseif($type === 'info')
                    <div class="w-6 h-6 bg-chef-gold rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-chef-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                @endif
                
                <div>
                    <p class="text-sm font-medium text-white">
                        @if($type === 'success')
                            Success!
                        @elseif($type === 'error')
                            Error!
                        @elseif($type === 'warning')
                            Warning!
                        @elseif($type === 'info')
                            Information
                        @endif
                    </p>
                    <p class="text-sm text-gray-300">{{ $message }}</p>
                </div>
            </div>
            
            <!-- Close Button -->
            <button @click="show = false" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Progress Bar -->
        <div class="h-1 bg-chef-gray-light">
            <div class="h-full bg-chef-gold transition-all duration-5000 ease-linear" 
                 x-init="setTimeout(() => show = false, 5000)"
                 style="width: 100%"></div>
        </div>
    </div>
</div>
@endif
