@extends('layouts.app')

@section('content')
    <div class="space-y-6 relative">
        <x-background-grid />
        <!-- Header -->

        <x-gradient-header :title="$sheet->name" :showFloatingElements="true">
            <div class="flex items-center space-x-6 select-none">
                <span
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-white/95 backdrop-blur-xl text-blue-600 shadow-lg">
                    <x-icon name="table" class="h-4 w-4 mr-2" />
                    {{ count($sheet->headers) }} columns
                </span>
                <span
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-white/95 backdrop-blur-xl text-blue-600 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                        <path d="M12 3v18" />
                        <path d="M3 12h18" />
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                    </svg>
                    {{ count($sheet->data ?? []) }} rows
                </span>
            </div>

            <x-slot name="buttons">
                <div>
                    <x-button variant="primary" size="sm" onclick="addRow()"
                        class="px-6 py-3 bg-white/95 text-blue-600 hover:bg-white font-semibold rounded-xl transition-all duration-300 border border-white/50 shadow-lg hover:shadow-xl hover:scale-105">
                        <x-icon name="plus" class="h-4 w-4 mr-2" />
                        Add Row
                    </x-button>
                    <x-button variant="secondary" size="md" href="{{ route('sheets.index') }}" class="ml-4">
                        <x-icon name="arrow-left" class="h-5 w-5 mr-2" />
                        Back to Sheets
                    </x-button>
                </div>
            </x-slot>
        </x-gradient-header>

        <!-- Table -->
        @if ($sheet->headers && count($sheet->headers) > 0)
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 overflow-hidden">
                <!-- Table Header Bar -->
                <x-table-header title="Data Table" subtitle="Interactive spreadsheet with real-time updates"
                    icon="table" />

                <!-- Table Toolbar -->
                <x-table-toolbar>
                    <x-button variant="primary" size="sm" onclick="addRow()">
                        <x-icon name="plus" class="h-4 w-4 mr-2" />
                        Add Row
                    </x-button>
                    
                    <button id="multiDeleteBtn" onclick="if(typeof deleteSelectedRows === 'function') { deleteSelectedRows(); } else { console.error('deleteSelectedRows function not available'); }" class="hidden inline-flex items-center px-3 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition-all duration-200 border border-red-500 hover:border-red-600">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Selected (<span id="selectedCount">0</span>)
                    </button>

                    <div class="relative group">
                        <button onclick="toggleExportMenu()"
                            class="inline-flex items-center px-3 py-2 bg-white/80 text-gray-700 text-sm font-semibold rounded-lg hover:bg-white hover:text-gray-900 transition-all duration-300 border border-gray-200/50 hover:shadow-md hover:scale-105">
                            <x-icon name="export" class="h-4 w-4 mr-2" />
                            Export
                            <svg class="h-4 w-4 ml-2 transition-transform duration-200 group-hover:rotate-180"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <!-- Export Dropdown Menu -->
                        <div id="exportMenu"
                            class="absolute right-0 mt-2 w-44 bg-white/95 rounded-lg shadow-lg border border-gray-200/50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <div class="py-1">
                                <button onclick="exportTable('csv')"
                                    class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 flex items-center">
                                    <x-icon name="export" class="h-4 w-4 mr-2 text-blue-500" />
                                    Export as CSV
                                </button>
                                <button onclick="exportTable('json')"
                                    class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 flex items-center">
                                    <x-icon name="export" class="h-4 w-4 mr-2 text-green-500" />
                                    Export as JSON
                                </button>
                                <button onclick="exportTable('excel')"
                                    class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 flex items-center">
                                    <x-icon name="export" class="h-4 w-4 mr-2 text-green-600" />
                                    Export as Excel
                                </button>
                            </div>
                        </div>
                    </div>

                    <button hx-get="{{ route('sheets.clear-confirmation', $sheet) }}" hx-target="#modalContainer"
                        hx-swap="innerHTML" hx-trigger="click"
                        class="inline-flex items-center px-3 py-2 bg-white/80 text-gray-700 text-sm font-semibold rounded-lg hover:bg-white hover:text-gray-900 transition-all duration-300 border border-gray-200/50 hover:shadow-md hover:scale-105 group/clear"
                        title="Clear all table data (Ctrl+Shift+Delete)">
                        <x-icon name="delete" class="h-4 w-4 mr-2" />
                        Clear All
                        <span class="ml-2 text-xs text-gray-400 group-hover/clear:text-gray-600">Ctrl+Shift+Del</span>
                    </button>

                    <x-slot name="actions">
                        <x-search-bar placeholder="Search in table..." />

                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <span class="flex items-center space-x-2">
                                <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span>Ctrl+Enter: Add Row</span>
                            </span>
                            <span class="flex items-center space-x-2">
                                <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Ctrl+S: Save</span>
                            </span>
                        </div>
                    </x-slot>
                </x-table-toolbar>

                @if ($sheet->data && count($sheet->data) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead
                                class="bg-gradient-to-r from-gray-50/80 via-blue-50/60 to-indigo-50/60 sticky top-0 z-10">
                                <tr>
                                    <th class="w-24 px-2 py-4 text-center text-sm font-bold text-gray-800 uppercase tracking-wider border border-gray-200">
                                        <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" onchange="if(typeof toggleSelectAll === 'function') { toggleSelectAll(); } else { console.error('toggleSelectAll function not available'); }">
                                    </th>
                                    @foreach ($sheet->headers as $headerIndex => $header)
                                        <th
                                            class="px-6 py-4 text-left text-sm font-bold text-gray-800 uppercase tracking-wider border border-gray-200 relative group">
                                            <div class="flex items-center space-x-2">
                                                <span>{{ $header }}</span>
                                                <div
                                                    class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    <div class="h-2 w-2 bg-blue-400 rounded-full"></div>
                                                </div>
                                            </div>
                                            <div
                                                 class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 transform scale-x-0 transition-transform duration-300 origin-left">
                                             </div>
                                        </th>
                                    @endforeach
                                    <th
                                        class="px-6 py-4 text-left text-sm font-bold text-gray-800 uppercase tracking-wider border border-gray-200">
                                        <div class="flex items-center space-x-2">
                                            <span>Actions</span>
                                            <div class="h-2 w-2 bg-red-400 rounded-full"></div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100" id="tableBody">
                                @foreach ($sheet->data as $rowIndex => $row)
                                    <tr class="group hover:bg-gray-50 transition-all duration-200 table-row-hover focus-ring"
                                        data-row="{{ $rowIndex }}">
                                        <td class="px-2 py-4 border border-gray-200 text-center">
                                            <input type="checkbox" class="row-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" value="{{ $rowIndex }}" onchange="if(typeof updateRowSelection === 'function') { updateRowSelection(); } else { console.error('updateRowSelection function not available'); }">
                                        </td>
                                        @foreach ($sheet->headers as $headerIndex => $header)
                                            <td class="border border-gray-200 relative">
                                                <div class="relative">
                                                    <input type="text" value="{{ $row[$headerIndex] ?? '' }}"
                                                     class="w-100 border-0 bg-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none px-6 py-2 focus:bg-white transition-all duration-200 text-gray-700 font-normal placeholder-gray-400 table-input"
                                                     onchange="updateCell({{ $rowIndex }}, {{ $headerIndex }}, this.value)"
                                                     onblur="saveData(); unhighlightRow({{ $rowIndex }})"
                                                     onfocus="highlightRow({{ $rowIndex }})"
                                                     placeholder="Enter {{ strtolower(e($header)) }}"
                                                     style="cursor: text;">
                                                </div>
                                            </td>
                                        @endforeach
                                        <td class="px-6 py-4 border border-gray-200 text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <button onclick="if(typeof deleteRow === 'function') { deleteRow({{ $rowIndex }}); } else { console.error('deleteRow function not available'); }"
                                                    class="text-gray-400 hover:text-red-600 p-1.5 rounded hover:bg-gray-100 transition-all duration-200 group/delete">
                                                    <svg class="h-4 w-4" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State - Displayed inside the table container -->
                    <div class="py-16">
                        <x-empty-state title="No data yet"
                            description="Add your first row to start organizing your data in this table."
                            buttonText="Add Your First Row" buttonAction="addRow()" icon="table" />
                    </div>
                @endif

                <!-- Table Footer -->
                <x-table-footer :columns="count($sheet->headers)" :rows="count($sheet->data ?? [])" status="Real-time editing" />
            </div>
        @endif
    </div>

    <!-- Save Status Indicator -->
    <div id="saveStatus" class="fixed top-8 right-8 z-50 hidden">
        <div class="bg-white/95 backdrop-blur-xl rounded-xl shadow-lg border border-white/20 px-6 py-4 min-w-64">
            <div class="flex items-center">
                <div id="saveIcon" class="mr-4">
                    <!-- Icon will be set by JavaScript -->
                </div>
                <div>
                    <span id="saveMessage" class="text-base font-bold text-gray-900"></span>
                    <p class="text-sm text-gray-500 mt-1">Data is being saved automatically</p>
                </div>
            </div>
        </div>
    </div>

    <!-- HTMX Modal Container -->
    <div id="modalContainer"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden flex items-center justify-center">
        <!-- Modal content will be loaded here via HTMX -->
    </div>

    <!-- HTMX Script -->
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>

    <!-- Lazy Loaded JavaScript -->
    <script>
        // Configuration data for the external JavaScript file
        window.sheetConfig = {
            data: @json($sheet->data ?? []),
            headers: @json($sheet->headers),
            updateUrl: '{{ route('sheets.updateData', $sheet) }}',
            csrfToken: '{{ csrf_token() }}',
            sheetName: '{{ $sheet->name }}'
        };

        // Lazy load the JavaScript file
        function loadSheetJavaScript() {
            return new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = '/js/sheets-table.js';
                script.onload = () => {
                    console.log('Sheet JavaScript loaded successfully');
                    resolve();
                };
                script.onerror = () => {
                    console.error('Failed to load Sheet JavaScript');
                    reject(new Error('Failed to load Sheet JavaScript'));
                };
                document.head.appendChild(script);
            });
        }

        // Initialize the table when the page loads
        document.addEventListener('DOMContentLoaded', async function() {
            try {
                await loadSheetJavaScript();

                // Initialize the table with configuration
                if (window.initializeTable) {
                    window.initializeTable(
                        window.sheetConfig.data,
                        window.sheetConfig.headers,
                        window.sheetConfig.updateUrl,
                        window.sheetConfig.csrfToken,
                        window.sheetConfig.sheetName
                    );
                }
            } catch (error) {
                console.error('Error initializing table:', error);
            }
        });
    </script>
@endsection
