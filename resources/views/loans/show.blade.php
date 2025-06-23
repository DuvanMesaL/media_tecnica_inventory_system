@extends('layouts.app')

@section('title', 'Préstamo #' . $loan->loan_number)
@section('header', 'Detalles del Préstamo')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header con breadcrumb -->
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('loans.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Préstamos</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-700 font-medium">{{ $loan->loan_number }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Préstamo #{{ $loan->loan_number }}</h1>
                    <p class="text-lg text-gray-600">Detalles completos del préstamo</p>
                </div>
                <div class="flex space-x-3 mt-4 sm:mt-0">
                    @if($loan->status === 'pending' && (auth()->user()->hasRole('admin') || $loan->user_id === auth()->id()))
                        <form action="{{ route('loans.cancel', $loan) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white font-medium rounded-lg hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl"
                                    onclick="return confirm('¿Estás seguro de cancelar este préstamo?')">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('loans.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transform hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Estado del préstamo -->
        <div class="mb-8">
            @php
                $statusConfig = [
                    'pending' => ['bg' => 'bg-yellow-100 border-yellow-200', 'text' => 'text-yellow-800', 'icon' => 'fas fa-clock', 'label' => 'Pendiente'],
                    'approved' => ['bg' => 'bg-blue-100 border-blue-200', 'text' => 'text-blue-800', 'icon' => 'fas fa-check', 'label' => 'Aprobado'],
                    'delivered' => ['bg' => 'bg-green-100 border-green-200', 'text' => 'text-green-800', 'icon' => 'fas fa-truck', 'label' => 'Entregado'],
                    'returned' => ['bg' => 'bg-gray-100 border-gray-200', 'text' => 'text-gray-800', 'icon' => 'fas fa-undo', 'label' => 'Devuelto'],
                    'cancelled' => ['bg' => 'bg-red-100 border-red-200', 'text' => 'text-red-800', 'icon' => 'fas fa-times', 'label' => 'Cancelado'],
                    'overdue' => ['bg' => 'bg-red-100 border-red-200', 'text' => 'text-red-800', 'icon' => 'fas fa-exclamation-triangle', 'label' => 'Vencido']
                ];

                $currentStatus = $loan->status;
                if ($loan->status === 'delivered' && $loan->expected_return_date < now()) {
                    $currentStatus = 'overdue';
                }

                $config = $statusConfig[$currentStatus];
            @endphp

            <div class="inline-flex items-center px-4 py-2 rounded-xl border {{ $config['bg'] }} {{ $config['text'] }} font-medium">
                <i class="{{ $config['icon'] }} mr-2"></i>
                {{ $config['label'] }}
                @if($currentStatus === 'overdue')
                    <span class="ml-2 text-sm">
                        ({{ now()->diffInDays($loan->expected_return_date) }} días de retraso)
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información del Préstamo -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-info-circle mr-3"></i>Información del Préstamo
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-hashtag text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <dt class="text-sm font-medium text-gray-600">Número de Préstamo</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $loan->loan_number }}</dd>
                                    </div>
                                </div>

                                <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-100">
                                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user text-green-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <dt class="text-sm font-medium text-gray-600">Solicitante</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $loan->user->name }}</dd>
                                        <dd class="text-sm text-gray-500">{{ $loan->user->email }}</dd>
                                    </div>
                                </div>

                                <div class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                                    <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-graduation-cap text-purple-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <dt class="text-sm font-medium text-gray-600">Programa Técnico</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $loan->technicalProgram->name }}</dd>
                                    </div>
                                </div>

                                <div class="flex items-center p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-100">
                                    <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-door-open text-yellow-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <dt class="text-sm font-medium text-gray-600">Aula</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $loan->classroom->name }}</dd>
                                        <dd class="text-sm text-gray-500">{{ $loan->classroom->code }} - {{ $loan->classroom->location }}</dd>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl border border-indigo-100">
                                    <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-warehouse text-indigo-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <dt class="text-sm font-medium text-gray-600">Almacén</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $loan->warehouse->name }}</dd>
                                        <dd class="text-sm text-gray-500">{{ $loan->warehouse->location }}</dd>
                                    </div>
                                </div>

                                <div class="flex items-center p-4 bg-gradient-to-r from-teal-50 to-cyan-50 rounded-xl border border-teal-100">
                                    <div class="flex-shrink-0 w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-teal-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <dt class="text-sm font-medium text-gray-600">Fecha de Préstamo</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $loan->loan_date->format('d/m/Y') }}</dd>
                                    </div>
                                </div>

                                <div class="flex items-center p-4 bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl border border-rose-100">
                                    <div class="flex-shrink-0 w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-check text-rose-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <dt class="text-sm font-medium text-gray-600">Fecha de Devolución</dt>
                                        <dd class="text-lg font-semibold text-gray-900 {{ $loan->expected_return_date < now() && $loan->status !== 'returned' ? 'text-red-600' : '' }}">
                                            {{ $loan->expected_return_date->format('d/m/Y') }}
                                        </dd>
                                        @if($loan->actual_return_date)
                                            <dd class="text-sm text-green-600">Devuelto: {{ $loan->actual_return_date->format('d/m/Y H:i') }}</dd>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($loan->notes)
                            <div class="mt-6 p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-100">
                                <dt class="text-sm font-medium text-gray-600 mb-2 flex items-center">
                                    <i class="fas fa-sticky-note mr-2"></i>Notas
                                </dt>
                                <dd class="text-gray-900 leading-relaxed">{{ $loan->notes }}</dd>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Herramientas del Préstamo -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-tools mr-3"></i>Herramientas Solicitadas
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Herramienta</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Código</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Solicitado</th>
                                        @if($loan->status !== 'pending')
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Entregado</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Estado Entrega</th>
                                        @endif
                                        @if($loan->status === 'returned')
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Devuelto</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Estado Devolución</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($loan->toolLoanItems as $item)
                                        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                            <td class="py-4 px-4">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $item->tool->name }}</div>
                                                    @if($item->tool->description)
                                                        <div class="text-sm text-gray-500">{{ Str::limit($item->tool->description, 50) }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $item->tool->code }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="text-lg font-semibold text-blue-600">{{ $item->quantity_requested }}</span>
                                            </td>
                                            @if($loan->status !== 'pending')
                                                <td class="py-4 px-4">
                                                    <span class="text-lg font-semibold text-green-600">{{ $item->quantity_delivered ?? 0 }}</span>
                                                </td>
                                                <td class="py-4 px-4">
                                                    @if($item->condition_delivered)
                                                        @php
                                                            $conditionConfig = [
                                                                'good' => ['bg' => 'bg-green-100 text-green-800 border-green-200', 'icon' => 'fas fa-check-circle'],
                                                                'damaged' => ['bg' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 'icon' => 'fas fa-exclamation-triangle'],
                                                                'lost' => ['bg' => 'bg-red-100 text-red-800 border-red-200', 'icon' => 'fas fa-times-circle']
                                                            ];
                                                            $config = $conditionConfig[$item->condition_delivered];
                                                        @endphp
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $config['bg'] }}">
                                                            <i class="{{ $config['icon'] }} mr-1"></i>
                                                            {{ ucfirst($item->condition_delivered) }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                            @endif
                                            @if($loan->status === 'returned')
                                                <td class="py-4 px-4">
                                                    <span class="text-lg font-semibold text-purple-600">{{ $item->quantity_returned ?? 0 }}</span>
                                                </td>
                                                <td class="py-4 px-4">
                                                    @if($item->condition_returned)
                                                        @php
                                                            $config = $conditionConfig[$item->condition_returned];
                                                        @endphp
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $config['bg'] }}">
                                                            <i class="{{ $config['icon'] }} mr-1"></i>
                                                            {{ ucfirst($item->condition_returned) }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                        @if($item->notes && $loan->status === 'returned')
                                            <tr>
                                                <td colspan="7" class="px-4 py-2 text-sm text-gray-600 bg-gradient-to-r from-gray-50 to-slate-50">
                                                    <div class="flex items-start">
                                                        <i class="fas fa-comment-alt mr-2 mt-0.5 text-gray-400"></i>
                                                        <div>
                                                            <strong>Notas de devolución:</strong> {{ $item->notes }}
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Acciones -->
                @can('manage', $loan)
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                            <h3 class="text-lg font-bold text-white flex items-center">
                                <i class="fas fa-bolt mr-3"></i>Acciones
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            @if($loan->status === 'pending')
                                @can('approve', $loan)
                                    <form action="{{ route('loans.approve', $loan) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-medium rounded-lg hover:from-green-700 hover:to-green-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                            <i class="fas fa-check mr-2"></i>Aprobar Préstamo
                                        </button>
                                    </form>
                                @endcan
                            @elseif($loan->status === 'approved')
                                @can('deliver', $loan)
                                    <a href="{{ route('loans.deliver.form', $loan) }}"
                                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                        <i class="fas fa-truck mr-2"></i>Procesar Entrega
                                    </a>
                                @endcan
                            @elseif($loan->status === 'delivered')
                                @can('return', $loan)
                                    <a href="{{ route('loans.return.form', $loan) }}"
                                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-lg hover:from-purple-700 hover:to-purple-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                        <i class="fas fa-undo mr-2"></i>Procesar Devolución
                                    </a>
                                @endcan
                            @endif
                        </div>
                    </div>
                @endcan

                <!-- Historial de Estados -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <i class="fas fa-history mr-3"></i>Historial
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Creado -->
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-plus text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Préstamo Creado</p>
                                    <p class="text-sm text-gray-500">{{ $loan->created_at->format('d/m/Y H:i') }}</p>
                                    <p class="text-sm text-gray-500">por {{ $loan->user->name }}</p>
                                </div>
                            </div>

                            @if($loan->approved_by)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-green-600 text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Préstamo Aprobado</p>
                                        <p class="text-sm text-gray-500">{{ $loan->updated_at->format('d/m/Y H:i') }}</p>
                                        <p class="text-sm text-gray-500">por {{ $loan->approvedBy->name }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($loan->delivered_by)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-truck text-blue-600 text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Préstamo Entregado</p>
                                        <p class="text-sm text-gray-500">{{ $loan->updated_at->format('d/m/Y H:i') }}</p>
                                        <p class="text-sm text-gray-500">por {{ $loan->deliveredBy->name }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($loan->received_by)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-undo text-purple-600 text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Préstamo Devuelto</p>
                                        <p class="text-sm text-gray-500">{{ $loan->actual_return_date->format('d/m/Y H:i') }}</p>
                                        <p class="text-sm text-gray-500">por {{ $loan->receivedBy->name }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Resumen de Herramientas -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-600 to-orange-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <i class="fas fa-chart-pie mr-3"></i>Resumen
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-list text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-blue-900">Total Herramientas</p>
                                        <p class="text-2xl font-bold text-blue-600">{{ $loan->toolLoanItems->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-cubes text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-900">Cantidad Total</p>
                                        <p class="text-2xl font-bold text-green-600">{{ $loan->toolLoanItems->sum('quantity_requested') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($loan->status !== 'pending')
                            <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-xl border border-purple-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-truck text-white"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-purple-900">Entregado</p>
                                            <p class="text-2xl font-bold text-purple-600">{{ $loan->toolLoanItems->sum('quantity_delivered') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
