@extends('layouts.app')

@section('content')
<div class="h-full space-y-6 relative">
    <x-animated-background />
    
    <!-- Welcome Section -->
    <x-gradient-header 
        title="Welcome back, {{ Auth::user()->name }}! 👋"
        subtitle="Ready to manage your work efficiently? Here's what's happening in your workspace today."
        :showFloatingElements="true" />

    <!-- Main Dashboard Grid -->
    <div class="grid grid-cols-4 gap-6 h-full">
        <!-- Quick Actions - Single Column -->
        <div class="col-span-1">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-6 h-full flex flex-col">
                <h2 class="text-xl font-bold text-gray-900 mb-8 flex items-center">
                    <div class="h-10 w-10 bg-gradient-to-br from-blue-500 via-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    Quick Actions
                </h2>
                <div class="space-y-3 flex-1">
                    <a href="{{ route('sheets.index') }}" class="group block w-full p-3 bg-blue-50 border border-blue-200 rounded-xl hover:bg-blue-100 transition-all duration-200 hover:shadow-sm">
                        <div class="flex items-center">
                            <div class="h-8 w-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <x-icon name="plus" class="h-4 w-4 text-blue-600" />
                            </div>
                            <div>
                                <p class="font-medium text-blue-900 text-sm">New Sheet</p>
                                <p class="text-xs text-blue-600">Create a new data table</p>
                            </div>
                        </div>
                    </a>
                    
                    <button onclick="openImportModal()" class="group block w-full p-3 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition-all duration-200 hover:shadow-sm text-left">
                        <div class="flex items-center">
                            <div class="h-8 w-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-indigo-900 text-sm">Import</p>
                                <p class="text-xs text-indigo-600">Import from Excel, CSV, or Google Sheets</p>
                            </div>
                        </div>
                    </button>
                    
                    <a href="{{ route('sheets.index') }}" class="group block w-full p-3 bg-purple-50 border border-purple-200 rounded-xl hover:bg-purple-100 transition-all duration-200 hover:shadow-sm">
                        <div class="flex items-center">
                            <div class="h-8 w-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900 text-sm">View All Sheets</p>
                                <p class="text-xs text-purple-600">Browse your data tables</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('notifications') }}" class="group block w-full p-3 bg-green-50 border border-green-200 rounded-xl hover:bg-green-100 transition-all duration-200 hover:shadow-sm">
                        <div class="flex items-center">
                            <div class="h-8 w-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M10.268 21a2 2 0 0 0 3.464 0"/>
                                    <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-green-900 text-sm">Notifications</p>
                                <p class="text-xs text-green-600">Check your alerts</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('settings') }}" class="group block w-full p-3 bg-orange-50 border border-orange-200 rounded-xl hover:bg-orange-100 transition-all duration-200 hover:shadow-sm">
                        <div class="flex items-center">
                            <div class="h-8 w-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="h-4 w-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-orange-900 text-sm">Settings</p>
                                <p class="text-xs text-orange-600">Configure your workspace</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Insights - Three Columns -->
        <div class="col-span-3">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <div class="h-10 w-10 bg-gradient-to-br from-blue-500 via-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    Insights
                </h2>
                <div class="grid grid-cols-3 gap-4">
                    <!-- Total Sheets - Bar Chart -->
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-blue-600">Total Sheets</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $sheets->count() }}</p>
                            </div>
                            <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <!-- Chart.js Bar Chart -->
                        <div class="h-16">
                            <canvas id="totalSheetsChart" width="200" height="64"></canvas>
                        </div>
                    </div>
                    
                    <!-- Active Projects - Line Chart -->
                    <div class="bg-purple-50 rounded-xl p-4 border border-purple-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-purple-600">Active Projects</p>
                                <p class="text-2xl font-bold text-purple-900">{{ $sheets->where('updated_at', '>=', now()->subDays(7))->count() }}</p>
                            </div>
                            <div class="h-10 w-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <!-- Chart.js Line Chart -->
                        <div class="h-16">
                            <canvas id="activeProjectsChart" width="200" height="64"></canvas>
                        </div>
                    </div>
                    
                    <!-- Team Members - Doughnut Chart -->
                    <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-green-600">Team Members</p>
                                <p class="text-2xl font-bold text-green-900">{{ Auth::user()->company->users->count() ?? 1 }}</p>
                            </div>
                            <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <!-- Chart.js Doughnut Chart -->
                        <div class="h-16">
                            <canvas id="teamMembersChart" width="200" height="64"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Sheets - Below Insights -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <div class="h-10 w-10 bg-gradient-to-br from-blue-500 via-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        Recent Sheets
                    </h2>
                    <a href="{{ route('sheets.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                        View all sheets →
                    </a>
                </div>
                
                @if($sheets->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($sheets->take(6) as $sheet)
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition-all duration-200 group cursor-pointer" onclick="window.location.href='{{ route('sheets.show', $sheet) }}'">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="h-10 w-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-500 bg-white px-2 py-1 rounded-full">{{ count($sheet->headers) }} columns</span>
                                </div>
                                
                                <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">{{ $sheet->name }}</h3>
                                <p class="text-sm text-gray-600 mb-4">Last updated {{ $sheet->updated_at->diffForHumans() }}</p>
                                
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $sheet->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No sheets yet</h3>
                        <p class="text-gray-500 mb-6">Create your first sheet to get started with organizing your work.</p>
                        <a href="{{ route('sheets.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create Your First Sheet
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 backdrop-blur-xl bg-black/20 z-50 hidden animate-fade-in">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl w-full max-w-2xl mx-4 border border-white/20 animate-scale-in">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-8 border-b border-gray-200/50">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <div class="h-12 w-12 bg-gradient-to-br from-indigo-500 via-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                    </div>
                    Import Data
                </h3>
                <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600 transition-all duration-300 hover:scale-110 p-2 rounded-xl hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-8">
                <!-- Import Options Tabs -->
                <div class="flex space-x-2 mb-8 bg-gradient-to-r from-gray-50 to-blue-50 p-2 rounded-2xl border border-gray-200/50">
                    <button onclick="switchImportTab('excel')" id="excelTab" class="flex-1 py-3 px-6 text-sm font-semibold rounded-xl bg-white text-gray-900 shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 border border-gray-200/50">
                        <div class="flex items-center justify-center">
                            <svg class="h-5 w-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Excel/CSV
                        </div>
                    </button>
                    <button onclick="switchImportTab('google')" id="googleTab" class="flex-1 py-3 px-6 text-sm font-semibold rounded-xl text-gray-600 hover:text-gray-900 transition-all duration-300 hover:scale-105 border border-transparent hover:border-gray-200/50">
                        <div class="flex items-center justify-center">
                            <svg class="h-5 w-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            Google Sheets
                        </div>
                    </button>
                </div>

                <!-- Excel/CSV Import Form -->
                <div id="excelForm" class="space-y-6">
                    <div class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-indigo-400 transition-all duration-300 hover:shadow-lg group cursor-pointer" onclick="document.getElementById('fileInput').click()">
                        <div class="h-16 w-16 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="h-8 w-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                        </div>
                        <p class="text-base font-semibold text-gray-700 mb-3">Drop your Excel or CSV file here</p>
                        <p class="text-sm text-gray-500 mb-6">Supports .xlsx, .xls, and .csv files</p>
                        <input type="file" id="fileInput" accept=".xlsx,.xls,.csv" class="hidden">
                        <button class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-indigo-600 hover:via-purple-600 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            Choose File
                        </button>
                    </div>
                    <div id="fileInfo" class="hidden bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-4 animate-fade-in">
                        <div class="flex items-center">
                            <div class="h-10 w-10 bg-green-500 rounded-xl flex items-center justify-center mr-4">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-green-700" id="fileName"></span>
                                <p class="text-xs text-green-600">File selected successfully</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Google Sheets Import Form -->
                <div id="googleForm" class="hidden space-y-6">
                    <div class="space-y-4">
                        <label for="googleSheetUrl" class="block text-sm font-semibold text-gray-700">Google Sheets URL</label>
                        <div class="relative">
                            <input type="url" id="googleSheetUrl" placeholder="https://docs.google.com/spreadsheets/d/..." 
                                   class="w-full px-6 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 bg-white/80 backdrop-blur-sm hover:bg-white">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <div class="h-8 w-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 bg-gray-50 rounded-xl p-4 border border-gray-200/50">Paste the URL of your Google Sheet. Make sure it's publicly accessible or shared with the appropriate permissions.</p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-4 p-8 border-t border-gray-200/50">
                <button onclick="closeImportModal()" class="px-6 py-3 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all duration-300 hover:scale-105">
                    Cancel
                </button>
                <button onclick="processImport()" class="px-8 py-3 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-indigo-600 hover:via-purple-600 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                    Import Data
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let currentImportTab = 'excel';

// Chart.js Charts
document.addEventListener('DOMContentLoaded', function() {
    // Total Sheets Growth Chart
    const totalSheetsCtx = document.getElementById('totalSheetsChart').getContext('2d');
    const totalSheetsChart = new Chart(totalSheetsCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Total Sheets',
                data: [
                    {{ max(0, $sheets->count() - 5) }},
                    {{ max(0, $sheets->count() - 4) }},
                    {{ max(0, $sheets->count() - 3) }},
                    {{ max(0, $sheets->count() - 2) }},
                    {{ max(0, $sheets->count() - 1) }},
                    {{ $sheets->count() }}
                ],
                borderColor: 'rgba(59, 130, 246, 1)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                pointBorderColor: 'rgba(255, 255, 255, 1)',
                pointBorderWidth: 2,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgba(59, 130, 246, 1)',
                pointHoverBorderColor: 'rgba(255, 255, 255, 1)',
                pointHoverBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            },
            scales: {
                x: { 
                    display: false,
                    grid: { display: false }
                },
                y: { 
                    display: false,
                    grid: { display: false },
                    min: 0,
                    max: Math.max(5, {{ $sheets->count() }} + 2)
                }
            },
            elements: {
                line: {
                    borderWidth: 3
                },
                point: {
                    radius: 3,
                    hoverRadius: 5
                }
            }
        }
    });

    // Active Projects Line Chart
    const activeProjectsCtx = document.getElementById('activeProjectsChart').getContext('2d');
    const activeProjectsChart = new Chart(activeProjectsCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Active Projects',
                data: [
                    {{ $sheets->where('updated_at', '>=', now()->subDays(6))->count() }},
                    {{ $sheets->where('updated_at', '>=', now()->subDays(5))->count() }},
                    {{ $sheets->where('updated_at', '>=', now()->subDays(4))->count() }},
                    {{ $sheets->where('updated_at', '>=', now()->subDays(3))->count() }},
                    {{ $sheets->where('updated_at', '>=', now()->subDays(2))->count() }},
                    {{ $sheets->where('updated_at', '>=', now()->subDays(1))->count() }},
                    {{ $sheets->where('updated_at', '>=', now())->count() }}
                ],
                borderColor: 'rgba(147, 51, 234, 1)',
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: 'rgba(147, 51, 234, 1)',
                pointBorderColor: 'rgba(255, 255, 255, 1)',
                pointBorderWidth: 2,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgba(147, 51, 234, 1)',
                pointHoverBorderColor: 'rgba(255, 255, 255, 1)',
                pointHoverBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            },
            scales: {
                x: { 
                    display: false,
                    grid: { display: false }
                },
                y: { 
                    display: false,
                    grid: { display: false },
                    min: 0,
                    max: Math.max(5, Math.max(...[
                        {{ $sheets->where('updated_at', '>=', now()->subDays(6))->count() }},
                        {{ $sheets->where('updated_at', '>=', now()->subDays(5))->count() }},
                        {{ $sheets->where('updated_at', '>=', now()->subDays(4))->count() }},
                        {{ $sheets->where('updated_at', '>=', now()->subDays(3))->count() }},
                        {{ $sheets->where('updated_at', '>=', now()->subDays(2))->count() }},
                        {{ $sheets->where('updated_at', '>=', now()->subDays(1))->count() }},
                        {{ $sheets->where('updated_at', '>=', now())->count() }}
                    ]) + 1)
                }
            },
            elements: {
                line: {
                    borderWidth: 3
                },
                point: {
                    radius: 3,
                    hoverRadius: 5
                }
            }
        }
    });

    // Team Members Horizontal Bar Chart
    const teamMembersCtx = document.getElementById('teamMembersChart').getContext('2d');
    const teamMembersChart = new Chart(teamMembersCtx, {
        type: 'bar',
        data: {
            labels: ['Team Size'],
            datasets: [{
                label: 'Current Team',
                data: [{{ Auth::user()->company->users->count() ?? 0 }}],
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgba(34, 197, 94, 1)',
                borderWidth: 1,
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            },
            scales: {
                x: { 
                    display: false,
                    max: 10
                },
                y: { display: false }
            },
            elements: {
                bar: {
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                }
            }
        }
    });
});

function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function switchImportTab(tab) {
    currentImportTab = tab;
    
    // Update tab buttons
    document.getElementById('excelTab').classList.toggle('bg-white', tab === 'excel');
    document.getElementById('excelTab').classList.toggle('text-gray-900', tab === 'excel');
    document.getElementById('excelTab').classList.toggle('text-gray-600', tab !== 'excel');
    document.getElementById('excelTab').classList.toggle('shadow-sm', tab === 'excel');
    
    document.getElementById('googleTab').classList.toggle('bg-white', tab === 'google');
    document.getElementById('googleTab').classList.toggle('text-gray-900', tab === 'google');
    document.getElementById('googleTab').classList.toggle('text-gray-600', tab !== 'google');
    document.getElementById('googleTab').classList.toggle('shadow-sm', tab === 'google');
    
    // Show/hide forms
    document.getElementById('excelForm').classList.toggle('hidden', tab !== 'excel');
    document.getElementById('googleForm').classList.toggle('hidden', tab !== 'google');
}

function processImport() {
    if (currentImportTab === 'excel') {
        const fileInput = document.getElementById('fileInput');
        if (fileInput.files.length === 0) {
            alert('Please select a file to import.');
            return;
        }
        // Handle file import logic here
        console.log('Importing file:', fileInput.files[0].name);
    } else {
        const urlInput = document.getElementById('googleSheetUrl');
        if (!urlInput.value.trim()) {
            alert('Please enter a Google Sheets URL.');
            return;
        }
        // Handle Google Sheets import logic here
        console.log('Importing from Google Sheets:', urlInput.value);
    }
    
    // Close modal after processing
    closeImportModal();
}

// File input change handler
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            fileName.textContent = file.name;
            fileInfo.classList.remove('hidden');
        }
    });
});

// Close modal when clicking outside
document.getElementById('importModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImportModal();
    }
});
</script>
@endsection
