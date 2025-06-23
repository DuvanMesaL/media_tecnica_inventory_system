@extends('layouts.app')

@section('title', 'Tool Loans')
@section('header', 'Tool Loans Management')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Tool Loans</h2>
            <p class="text-gray-600 mt-1">Manage and track all tool loans</p>
        </div>
        <a href="{{ route('loans.create') }}" class="bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
            <i class="fas fa-plus mr-2"></i>Request New Loan
        </a>
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Loan number or user name..."
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="flex items-end space-x-3">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('loans.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Loans Table -->
    <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Details</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requestor</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program & Classroom</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-100 transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center">
                                            <i class="fas fa-handshake text-white"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $loan->loan_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $loan->warehouse->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $loan->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ ucfirst($loan->user->role) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $loan->technicalProgram->name }}</div>
                                <div class="text-sm text-gray-500">{{ $loan->classroom->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>Loan: {{ $loan->loan_date->format('M d, Y') }}</div>
                                <div class="text-gray-500">Return: {{ $loan->expected_return_date->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @if($loan->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($loan->status === 'approved') bg-blue-100 text-blue-800
                                    @elseif($loan->status === 'delivered') bg-green-100 text-green-800
                                    @elseif($loan->status === 'returned') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                <a href="{{ route('loans.show', $loan) }}" class="text-primary-600 hover:text-primary-900 transition-colors">View</a>

                                @if(auth()->user()->isLogistics() || auth()->user()->isAdmin())
                                    @if($loan->status === 'pending')
                                        <form method="POST" action="{{ route('loans.approve', $loan) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 transition-colors">Approve</button>
                                        </form>
                                    @elseif($loan->status === 'approved')
                                        <form method="POST" action="{{ route('loans.deliver', $loan) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-blue-600 hover:text-blue-900 transition-colors">Deliver</button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-handshake text-gray-400 text-5xl mb-4"></i>
                                <p class="text-lg">No loans found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($loans->hasPages())
        <div class="flex justify-center">
            {{ $loans->links() }}
        </div>
    @endif
</div>
@endsection
