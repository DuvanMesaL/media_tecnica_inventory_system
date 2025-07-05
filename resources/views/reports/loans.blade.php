@extends('layouts.app')

@section('title', 'Reporte de Préstamos')
@section('header', 'Reporte de Préstamos')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8 space-y-8">
        <!-- Header with Breadcrumb -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Dashboard</a></li>
                        <li><i class="fas fa-chevron-right text-gray-400 mx-2"></i></li>
                        <li><a href="{{ route('reports.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Reportes</a></li>
                        <li><i class="fas fa-chevron-right text-gray-400 mx-2"></i></li>
                        <li class="text-blue-600 font-medium">Préstamos</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Reporte de Préstamos
                </h1>
                <p class="text-gray-600 mt-2">Análisis detallado de préstamos y devoluciones</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('reports.export', 'loans') }}"
                   class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-download mr-2"></i>Exportar CSV
                </a>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Préstamos</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($summary['total_loans']) }}</p>
                        <p class="text-sm text-blue-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>{{ $summary['loans_this_month'] }} este mes
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 rounded-xl">
                        <i class="fas fa-handshake text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Préstamos Activos</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $summary['active_loans'] }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            <i class="fas fa-check-circle mr-1"></i>En curso
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-4 rounded-xl">
                        <i class="fas fa-clock text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Préstamos Vencidos</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $summary['overdue_loans'] }}</p>
                        <p class="text-sm text-red-600 mt-1">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Requieren atención
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-red-500 to-red-600 p-4 rounded-xl">
                        <i class="fas fa-exclamation-triangle text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tasa de Devolución</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($summary['return_rate'], 1) }}%</p>
                        <p class="text-sm text-blue-600 mt-1">
                            <i class="fas fa-undo mr-1"></i>Promedio mensual
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-4 rounded-xl">
                        <i class="fas fa-percentage text-2xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtros de Búsqueda</h3>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">Todos los estados</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprobado</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Entregado</option>
                        <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Devuelto</option>
                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Vencido</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Programa</label>
                    <select name="technical_program_id" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">Todos los programas</option>
                        @foreach($technicalPrograms as $program)
                            <option value="{{ $program->id }}" {{ request('technical_program_id') == $program->id ? 'selected' : '' }}>
                                {{ $program->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-filter mr-2"></i>Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>

        <!-- Loans Table -->
        <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Préstamo</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Programa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Herramientas</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fechas</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($loans as $loan)
                            <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $loan->loan_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $loan->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-sm mr-3">
                                            {{ substr($loan->user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $loan->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $loan->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $loan->technicalProgram->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $loan->classroom->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $loan->toolLoanItems->count() }} herramientas</div>
                                    <div class="text-sm text-gray-500">{{ $loan->toolLoanItems->sum('quantity_requested') }} unidades</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>Solicitado: {{ $loan->requested_date ? $loan->requested_date->format('d/m/Y') : ($loan->loan_date ? $loan->loan_date->format('d/m/Y') : 'N/A') }}</div>
                                    <div class="{{ $loan->expected_return_date && $loan->expected_return_date < now() && $loan->status !== 'returned' ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                        Retorno: {{ $loan->expected_return_date ? $loan->expected_return_date->format('d/m/Y') : 'N/A' }}
                                        @if($loan->expected_return_date && $loan->expected_return_date < now() && $loan->status !== 'returned')
                                            <span class="ml-1 text-xs">({{ now()->diffInDays($loan->expected_return_date) }} días vencido)</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($loan->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($loan->status === 'approved') bg-blue-100 text-blue-800
                                        @elseif($loan->status === 'delivered') bg-green-100 text-green-800
                                        @elseif($loan->status === 'returned') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        @if($loan->status === 'pending') <i class="fas fa-clock mr-1"></i>
                                        @elseif($loan->status === 'approved') <i class="fas fa-check mr-1"></i>
                                        @elseif($loan->status === 'delivered') <i class="fas fa-truck mr-1"></i>
                                        @elseif($loan->status === 'returned') <i class="fas fa-undo mr-1"></i>
                                        @else <i class="fas fa-exclamation-triangle mr-1"></i>
                                        @endif
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('loans.show', $loan) }}"
                                       class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-500 text-lg">No se encontraron préstamos</p>
                                        <p class="text-gray-400 text-sm">Intenta ajustar los filtros de búsqueda</p>
                                    </div>
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
                {{ $loans->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
