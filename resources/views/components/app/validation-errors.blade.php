@if ($errors->any())
    <div class="bg-red-900/20 border border-red-500/30 rounded-2xl p-4 mb-6">
        <div class="flex items-center space-x-3 mb-3">
            <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-red-400">Please fix the following errors:</h3>
        </div>
        
        <ul class="list-disc list-inside space-y-2">
            @foreach ($errors->all() as $error)
                <li class="text-red-300 text-sm">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
