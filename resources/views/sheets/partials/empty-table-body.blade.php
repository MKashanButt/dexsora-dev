<!-- Empty table body after clearing all data -->
<tbody class="bg-white/60 divide-y divide-gray-200/20" id="tableBody">
    <!-- Table is now empty -->
</tbody>

<!-- Empty State -->
<div class="text-center py-16" id="emptyState">
    <div class="h-32 w-32 bg-gradient-to-br from-gray-200 via-blue-100 to-indigo-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
        <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
    </div>
    <h3 class="text-2xl font-bold text-gray-900 mb-3">All data cleared</h3>
    <p class="text-gray-600 text-lg mb-8 max-w-lg mx-auto leading-relaxed">Your table has been cleared. Add new rows to start organizing your data again.</p>
    <button onclick="addRow()" class="inline-flex items-center px-8 py-4 border border-transparent text-lg font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 hover:from-blue-700 hover:via-purple-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
        <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Add Your First Row
    </button>
</div>
