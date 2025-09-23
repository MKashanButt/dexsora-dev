<div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 max-w-lg mx-4">
    <div class="text-center">
        <!-- Warning Icon -->
        <div class="h-20 w-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>
        
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Clear All Data?</h3>
        
        <div class="text-gray-600 mb-8">
            <p class="mb-3">This will remove:</p>
            <ul class="text-left space-y-2">
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-3"></span>
                    <strong>{{ $rowCount }}</strong> row{{ $rowCount !== 1 ? 's' : '' }}
                </li>
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-3"></span>
                    <strong>{{ $columnCount }}</strong> column{{ $columnCount !== 1 ? 's' : '' }}
                </li>
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-3"></span>
                    All cell contents
                </li>
            </ul>
        </div>
        
        <div class="bg-red-50/80 border border-red-200/50 rounded-2xl p-4 mb-8">
            <p class="text-red-700 text-sm font-medium">⚠️ This action cannot be undone!</p>
        </div>
        
        <div class="flex space-x-4">
            <button onclick="closeModal()" 
                    class="flex-1 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-2xl transition-all duration-300 hover:scale-105">
                Cancel
            </button>
            <button hx-post="{{ route('sheets.clear', $sheet) }}"
                    hx-target="#tableBody"
                    hx-swap="innerHTML"
                    onclick="closeModal()"
                    class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-2xl transition-all duration-300 hover:scale-105 hover:shadow-lg">
                Clear All Data
            </button>
        </div>
    </div>
</div>
