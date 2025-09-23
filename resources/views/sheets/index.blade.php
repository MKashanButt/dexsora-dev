@extends('layouts.app')

@section('content')
<div class="space-y-6 relative">
    <x-animated-background />
    
    <x-success-alert :message="session('success')" />
    
    <!-- Header -->
    <x-gradient-header 
        title="Data Sheets 📊"
        subtitle="Manage your custom data tables and organize information efficiently"
        buttonText="Create New Sheet"
        buttonAction="openCreateModal()"
        buttonIcon="plus"
        :showFloatingElements="true" />

    <!-- Sheets Grid -->
    @if($sheets->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($sheets as $sheet)
                <x-card 
                    class="cursor-pointer" 
                    :clickable="true"
                    :onclick="'window.location.href=\'' . route('sheets.show', $sheet) . '\''">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="h-16 w-16 bg-gradient-to-br from-indigo-500 via-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                                <x-icon name="table" class="h-8 w-8 text-white" />
                            </div>
                            <span class="text-xs font-medium text-gray-500 bg-gray-100/80 px-3 py-2 rounded-full">{{ $sheet->updated_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">{{ $sheet->name }}</h3>
                        <div class="flex items-center space-x-3 mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <x-icon name="table" class="h-3 w-3 mr-1" />
                                {{ count($sheet->headers) }} columns
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <x-icon name="check" class="h-3 w-3 mr-1" />
                                Active
                            </span>
                        </div>
                        <div class="flex space-x-3">
                            <x-button 
                                variant="primary" 
                                size="sm" 
                                onclick="event.stopPropagation(); window.location.href='{{ route('sheets.show', $sheet) }}'"
                                class="flex-1">
                                <x-icon name="eye" class="h-4 w-4 mr-2" />
                                View
                            </x-button>
                            <x-button 
                                variant="outline" 
                                size="sm" 
                                onclick="event.stopPropagation(); editSheet({{ $sheet->id }})"
                                class="flex-1">
                                <x-icon name="edit" class="h-4 w-4 mr-2" />
                                Edit
                            </x-button>
                            <x-button 
                                variant="danger" 
                                size="sm" 
                                onclick="event.stopPropagation(); deleteSheet({{ $sheet->id }}, '{{ $sheet->name }}')">
                                <x-icon name="delete" class="h-4 w-4" />
                            </x-button>
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>
    @else
        <x-empty-state 
            title="No sheets yet"
            description="Get started by creating your first data sheet to organize and manage your information efficiently."
            buttonText="Create Your First Sheet"
            buttonAction="openCreateModal()"
            icon="table" />
    @endif
</div>

<!-- Create Sheet Modal -->
<div id="createModal" class="fixed inset-0 backdrop-blur-xl bg-black/20 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center animate-fade-in">
    <div class="relative mx-auto p-8 w-full max-w-lg shadow-2xl rounded-3xl bg-white/95 backdrop-blur-xl border border-white/20 animate-scale-in">
        <!-- Modal Header -->
        <div class="mb-8 text-center">
            <div class="h-16 w-16 bg-gradient-to-br from-blue-500 via-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-3">Create New Sheet</h3>
            <p class="text-gray-600 text-lg">Set up a new data table with custom columns</p>
        </div>
        
        <form action="{{ route('sheets.store') }}" method="POST" id="createSheetForm" onsubmit="return validateForm()">
            @csrf
            <div class="mb-8">
                <label for="sheet_name" class="block text-sm font-semibold text-gray-700 mb-3">Sheet Name</label>
                <input type="text" id="sheet_name" name="name" required 
                       class="w-full px-6 py-4 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 bg-white/80 backdrop-blur-sm hover:bg-white"
                       placeholder="Enter sheet name">
            </div>
            
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-4">Table Headers</label>
                <div id="headersContainer" class="space-y-4">
                    <div class="flex space-x-4">
                        <input type="text" name="headers[]" required 
                               class="flex-1 px-6 py-4 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 bg-white/80 backdrop-blur-sm hover:bg-white"
                               placeholder="e.g., Name, Email, Phone">
                        <button type="button" onclick="removeHeader(this)" 
                                class="px-4 py-4 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-2xl transition-all duration-300 hover:scale-110">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <button type="button" onclick="addHeader()" 
                        class="mt-4 text-blue-600 hover:text-blue-700 font-semibold text-sm hover:bg-blue-50 px-4 py-3 rounded-xl transition-all duration-300 hover:scale-105 flex items-center mx-auto">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Header
                </button>
            </div>

            <div class="flex space-x-4">
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 text-white px-8 py-4 rounded-2xl hover:from-blue-700 hover:via-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold hover:scale-105">
                    Create Sheet
                </button>
                <button type="button" onclick="closeCreateModal()" 
                        class="flex-1 bg-gray-100 text-gray-700 px-8 py-4 rounded-2xl hover:bg-gray-200 transition-all duration-300 font-semibold hover:scale-105">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function addHeader() {
    const container = document.getElementById('headersContainer');
    const headerDiv = document.createElement('div');
    headerDiv.className = 'flex space-x-4 animate-slide-up';
    headerDiv.innerHTML = `
        <input type="text" name="headers[]" required 
               class="flex-1 px-6 py-4 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 bg-white/80 backdrop-blur-sm hover:bg-white"
               placeholder="Column name">
        <button type="button" onclick="removeHeader(this)" 
                class="px-4 py-4 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-2xl transition-all duration-300 hover:scale-110">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    `;
    container.appendChild(headerDiv);
}

function removeHeader(button) {
    button.parentElement.remove();
}

function editSheet(sheetId) {
    window.location.href = `/sheets/${sheetId}`;
}

function deleteSheet(sheetId, sheetName) {
    if (confirm(`Are you sure you want to delete "${sheetName}"? This action cannot be undone.`)) {
        // Create a form to submit the DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/sheets/${sheetId}`;
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        // Add method override for DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function validateForm() {
    const headers = document.querySelectorAll('input[name="headers[]"]');
    let hasValidHeaders = false;
    
    // Check if at least one header has a value
    headers.forEach(header => {
        if (header.value.trim() !== '') {
            hasValidHeaders = true;
        }
    });
    
    if (!hasValidHeaders) {
        alert('Please add at least one table header.');
        return false;
    }
    
    // Remove empty header inputs before submission
    headers.forEach(header => {
        if (header.value.trim() === '') {
            header.remove();
        }
    });
    
    return true;
}

// Close modal when clicking outside
document.getElementById('createModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateModal();
    }
});
</script>
@endsection
