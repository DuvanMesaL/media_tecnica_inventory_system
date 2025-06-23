@extends('layouts.app')

@section('title', 'Reports Dashboard')
@section('header', 'Reports & Analytics')

@section('content')
<div class="space-y-6">
    <!-- Overview Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-tools text-3xl text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Tools</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_tools']) }}</dd>
                            <dd class="text-sm text-green-600">{{ number_format($stats['available_tools']) }} available</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-handshake text-3xl text-yellow-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Loans</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $stats['active_loans'] }}</dd>
                            <dd class="text-sm text-red-600">{{ $stats['overdue_loans'] }} overdue</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-dollar-sign text-3xl text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Value</dt>
                            <dd class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_value'], 2) }}</dd>
                            <dd class="text-sm text-gray-500">Inventory worth</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Report Categories</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('reports.tools') }}"
                   class="block p-6 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-wrench text-2xl text-blue-600 mr-4"></i>
                        <div>
                            <h4 class="text-lg font-medium text-blue-900">Tools Report</h4>
                            <p class="text-sm text-blue-700">Inventory status, stock levels, and tool details</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('reports.loans') }}"
                   class="block p-6 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-clipboard-list text-2xl text-yellow-600 mr-4"></i>
                        <div>
                            <h4 class="text-lg font-medium text-yellow-900">Loans Report</h4>
                            <p class="text-sm text-yellow-700">Loan history, overdue items, and borrowing patterns</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('reports.usage') }}"
                   class="block p-6 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-chart-bar text-2xl text-green-600 mr-4"></i>
                        <div>
                            <h4 class="text-lg font-medium text-green-900">Usage Analytics</h4>
                            <p class="text-sm text-green-700">Usage statistics, popular tools, and trends</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Top Categories -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Loans -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Loan Activity</h3>
                <div class="space-y-3">
                    @forelse($recent_loans as $loan)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $loan->loan_number }}</p>
                                <p class="text-sm text-gray-500">{{ $loan->user->name }} - {{ $loan->technicalProgram->name }}</p>
                                <p class="text-xs text-gray-400">{{ $loan->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($loan->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($loan->status === 'approved') bg-blue-100 text-blue-800
                                @elseif($loan->status === 'delivered') bg-green-100 text-green-800
                                @elseif($loan->status === 'returned') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No recent activity</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Categories -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Most Used Categories</h3>
                <div class="space-y-3">
                    @forelse($top_categories as $category)
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-900">{{ $category->category }}</span>
                                    <span class="text-sm text-gray-500">{{ $category->total_loaned }} loans</span>
                                </div>
                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full"
                                         style="width: {{ ($category->total_loaned / $top_categories->max('total_loaned')) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No usage data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trends Chart -->
    @if($monthly_trends->count() > 0)
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Loan Trends</h3>
            <div class="h-64 flex items-end justify-between space-x-2">
                @foreach($monthly_trends->reverse() as $trend)
                    <div class="flex flex-col items-center flex-1">
                        <div class="bg-blue-600 rounded-t"
                             style="height: {{ ($trend->total_loans / $monthly_trends->max('total_loans')) * 200 }}px; min-height: 20px; width: 100%;"></div>
                        <div class="text-xs text-gray-500 mt-2 text-center">
                            {{ date('M', mktime(0, 0, 0, $trend->month, 1)) }}<br>{{ $trend->year }}
                        </div>
                        <div class="text-xs font-medium text-gray-900">{{ $trend->total_loans }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
