@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl hover-lift border border-gray-100">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 rounded-xl shadow-lg">
                            <i class="fas fa-tools text-2xl text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Tools</dt>
                            <dd class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_tools']) }}</dd>
                            <dd class="text-sm text-gray-600 flex items-center mt-1">
                                <i class="fas fa-warehouse mr-1"></i>
                                Across {{ $stats['total_warehouses'] }} warehouses
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-3">
                <div class="text-sm">
                    <a href="{{ route('tools.index') }}" class="font-medium text-blue-600 hover:text-blue-500 flex items-center">
                        View all tools <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-xl rounded-2xl hover-lift border border-gray-100">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-4 rounded-xl shadow-lg">
                            <i class="fas fa-check-circle text-2xl text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Available Tools</dt>
                            <dd class="text-3xl font-bold text-gray-900">{{ number_format($stats['available_tools']) }}</dd>
                            <dd class="text-sm text-gray-600 flex items-center mt-1">
                                <i class="fas fa-check mr-1"></i>
                                Ready for loan
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-3">
                <div class="text-sm">
                    <a href="{{ route('tools.index') }}?availability=available" class="font-medium text-green-600 hover:text-green-500 flex items-center">
                        View available <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-xl rounded-2xl hover-lift border border-gray-100">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-gradient-to-r from-yellow-500 to-orange-600 p-4 rounded-xl shadow-lg">
                            <i class="fas fa-handshake text-2xl text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Loans</dt>
                            <dd class="text-3xl font-bold text-gray-900">{{ $stats['active_loans'] }}</dd>
                            <dd class="text-sm text-gray-600 flex items-center mt-1">
                                <i class="fas fa-clock mr-1"></i>
                                Currently out
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-3">
                <div class="text-sm">
                    <a href="{{ route('loans.index') }}?status=delivered" class="font-medium text-yellow-600 hover:text-yellow-500 flex items-center">
                        View active <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-xl rounded-2xl hover-lift border border-gray-100">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-gradient-to-r from-red-500 to-pink-600 p-4 rounded-xl shadow-lg">
                            <i class="fas fa-exclamation-triangle text-2xl text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending Actions</dt>
                            <dd class="text-3xl font-bold text-gray-900">{{ $stats['pending_loans'] }}</dd>
                            <dd class="text-sm text-gray-600 flex items-center mt-1">
                                <i class="fas fa-bell mr-1"></i>
                                Need attention
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-red-50 to-pink-50 px-6 py-3">
                <div class="text-sm">
                    <a href="{{ route('loans.index') }}?status=pending" class="font-medium text-red-600 hover:text-red-500 flex items-center">
                        Review pending <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts and Notifications -->
    @if($low_stock_tools->count() > 0)
    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border-l-4 border-yellow-400 p-6 rounded-xl shadow-lg animate-slide-in">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-yellow-800">Low Stock Alert</h3>
                <p class="text-sm text-yellow-700 mt-1">
                    {{ $low_stock_tools->count() }} tools have low inventory levels and need restocking.
                    <a href="{{ route('tools.index') }}?availability=low_stock" class="font-medium underline hover:text-yellow-600 transition-colors">
                        View details →
                    </a>
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Loans -->
        <div class="lg:col-span-2 bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl leading-6 font-bold text-gray-900 flex items-center">
                        <i class="fas fa-history mr-3 text-primary-600"></i>
                        Recent Loan Activity
                    </h3>
                    <a href="{{ route('loans.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500 flex items-center transition-colors">
                        View all <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recent_loans as $loan)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl hover:from-primary-50 hover:to-indigo-50 transition-all duration-200 border border-gray-200 hover:border-primary-200">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-semibold text-gray-900">{{ $loan->loan_number }}</p>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($loan->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($loan->status === 'approved') bg-blue-100 text-blue-800
                                        @elseif($loan->status === 'delivered') bg-green-100 text-green-800
                                        @elseif($loan->status === 'returned') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $loan->user->name }} - {{ $loan->technicalProgram->name }}</p>
                                <div class="flex items-center text-xs text-gray-500 mt-2">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <span>{{ $loan->classroom->name }} → {{ $loan->warehouse->name }}</span>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock mr-1"></i>
                                    <span>{{ $loan->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-clipboard-list text-gray-400 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg">No recent loan activity</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Low Stock Tools -->
        <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-red-50 to-pink-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl leading-6 font-bold text-gray-900 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3 text-red-600"></i>
                        Stock Alerts
                    </h3>
                    <a href="{{ route('tools.index') }}?availability=low_stock" class="text-sm font-medium text-red-600 hover:text-red-500 flex items-center transition-colors">
                        View all <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($low_stock_tools as $tool)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-pink-50 rounded-xl border border-red-200 hover:shadow-lg transition-all duration-200">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">{{ $tool->name }}</p>
                                <div class="flex items-center text-xs text-gray-600 mt-1">
                                    <i class="fas fa-warehouse mr-1"></i>
                                    <span>{{ $tool->warehouse->name }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    {{ $tool->available_quantity === 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $tool->available_quantity === 0 ? 'Out of Stock' : $tool->available_quantity . ' left' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-check-circle text-green-400 text-5xl mb-4"></i>
                            <p class="text-green-600 font-medium text-lg">All tools have sufficient stock</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-primary-50 to-indigo-50">
            <h3 class="text-xl leading-6 font-bold text-gray-900 flex items-center">
                <i class="fas fa-bolt mr-3 text-primary-600"></i>
                Quick Actions
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('loans.create') }}"
                   class="flex items-center p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-200 group hover-lift">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 rounded-xl mr-4 group-hover:from-blue-700 group-hover:to-blue-800 transition-all duration-200 shadow-lg">
                        <i class="fas fa-plus text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-blue-900">Request Loan</p>
                        <p class="text-xs text-blue-700">Borrow tools</p>
                    </div>
                </a>

                @can('manage tools')
                <a href="{{ route('tools.create') }}"
                   class="flex items-center p-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all duration-200 group hover-lift">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 p-4 rounded-xl mr-4 group-hover:from-green-700 group-hover:to-green-800 transition-all duration-200 shadow-lg">
                        <i class="fas fa-wrench text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-green-900">Add Tool</p>
                        <p class="text-xs text-green-700">New inventory</p>
                    </div>
                </a>
                @endcan

                @can('view reports')
                <a href="{{ route('reports.index') }}"
                   class="flex items-center p-6 bg-gradient-to-r from-purple-50 to-violet-50 border border-purple-200 rounded-xl hover:from-purple-100 hover:to-violet-100 transition-all duration-200 group hover-lift">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-4 rounded-xl mr-4 group-hover:from-purple-700 group-hover:to-purple-800 transition-all duration-200 shadow-lg">
                        <i class="fas fa-chart-bar text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-purple-900">View Reports</p>
                        <p class="text-xs text-purple-700">Analytics</p>
                    </div>
                </a>
                @endcan

                @can('manage users')
                <a href="{{ route('register') }}"
                   class="flex items-center p-6 bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-200 rounded-xl hover:from-orange-100 hover:to-amber-100 transition-all duration-200 group hover-lift">
                    <div class="bg-gradient-to-r from-orange-600 to-orange-700 p-4 rounded-xl mr-4 group-hover:from-orange-700 group-hover:to-orange-800 transition-all duration-200 shadow-lg">
                        <i class="fas fa-user-plus text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-orange-900">Add User</p>
                        <p class="text-xs text-orange-700">New account</p>
                    </div>
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
