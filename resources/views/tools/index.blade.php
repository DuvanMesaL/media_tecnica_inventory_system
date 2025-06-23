@extends('layouts.app')

@section('title', 'Tools')
@section('header', 'Tools Management')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Tools Inventory</h2>
            <p class="text-gray-600 mt-1">Manage and track all tools in the system</p>
        </div>
        @if(auth()->user()->isAdmin() || auth()->user()->isLogistics())
            <a href="{{ route('tools.create') }}" class="bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <i class="fas fa-plus mr-2"></i>Add New Tool
            </a>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-filter mr-3 text-primary-600"></i>
                Filters
            </h3>
        </div>
        <form method="GET" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Name, code, or category..."
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Warehouse</label>
                    <select name="warehouse_id" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                        <option value="">All Warehouses</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Condition</label>
                    <select name="condition" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                        <option value="">All Conditions</option>
                        <option value="good" {{ request('condition') === 'good' ? 'selected' : '' }}>Good</option>
                        <option value="damaged" {{ request('condition') === 'damaged' ? 'selected' : '' }}>Damaged</option>
                        <option value="lost" {{ request('condition') === 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>
                <div class="flex items-end space-x-3">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('tools.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tools Table -->
    <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tool</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warehouse</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Condition</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tools as $tool)
                        <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-100 transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center">
                                            <i class="fas fa-wrench text-white"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $tool->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $tool->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    {{ $tool->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $tool->warehouse->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    Available: <span class="font-medium {{ $tool->available_quantity <= 5 ? 'text-red-600' : 'text-green-600' }}">{{ $tool->available_quantity }}</span>
                                </div>
                                <div class="text-sm text-gray-500">Total: {{ $tool->total_quantity }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @if($tool->condition === 'good') bg-green-100 text-green-800
                                    @elseif($tool->condition === 'damaged') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($tool->condition) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                <a href="{{ route('tools.show', $tool) }}" class="text-primary-600 hover:text-primary-900 transition-colors">View</a>
                                @if(auth()->user()->isAdmin() || auth()->user()->isLogistics())
                                    <a href="{{ route('tools.edit', $tool) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors">Edit</a>
                                    <form method="POST" action="{{ route('tools.destroy', $tool) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors"
                                                onclick="return confirm('Are you sure you want to delete this tool?')">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-tools text-gray-400 text-5xl mb-4"></i>
                                <p class="text-lg">No tools found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($tools->hasPages())
        <div class="flex justify-center">
            {{ $tools->links() }}
        </div>
    @endif
</div>
@endsection
