@extends('layouts.app')

@section('title', $tool->name)
@section('header', 'Detalles de Herramienta')

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
                            <a href="{{ route('tools.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Herramientas</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-700 font-medium">{{ $tool->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $tool->name }}</h1>
                    <p class="text-lg text-gray-600">{{ $tool->code }}</p>
                </div>
                <div class="flex space-x-3 mt-4 sm:mt-0">
                    @can('update', $tool)
                        <a href="{{ route('tools.edit', $tool) }}"
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-edit mr-2"></i>Editar
                        </a>
                    @endcan
                    <a href="{{ route('tools.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transform hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card de Información Básica -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-info-circle mr-3"></i>Información General
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-tag text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <dt class="text-sm font-medium text-gray-600">Categoría</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $tool->category)) }}</dd>
                                    </div>
                                </div>

                                <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-100">
                                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-warehouse text-green-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <dt class="text-sm font-medium text-gray-600">Almacén</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $tool->warehouse->name }}</dd>
                                        <dd class="text-sm text-gray-500">{{ $tool->warehouse->location }}</dd>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                                    <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-heart-pulse text-purple-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <dt class="text-sm font-medium text-gray-600">Condición</dt>
                                        <dd class="mt-1">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                @if($tool->condition === 'good') bg-green-100 text-green-800 border border-green-200
                                                @elseif($tool->condition === 'damaged') bg-yellow-100 text-yellow-800 border border-yellow-200
                                                @else bg-red-100 text-red-800 border border-red-200
                                                @endif">
                                                @if($tool->condition === 'good')
                                                    <i class="fas fa-check-circle mr-2"></i>
                                                @elseif($tool->condition === 'damaged')
                                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                                @else
                                                    <i class="fas fa-times-circle mr-2"></i>
                                                @endif
                                                {{ ucfirst($tool->condition) }}
                                            </span>
                                        </dd>
                                    </div>
                                </div>

                                <div class="flex items-center p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-100">
                                    <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-dollar-sign text-yellow-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <dt class="text-sm font-medium text-gray-600">Precio Unitario</dt>
                                        <dd class="text-lg font-semibold text-gray-900">
                                            @if($tool->unit_price)
                                                ${{ number_format($tool->unit_price, 2) }}
                                            @else
                                                <span class="text-gray-400">No especificado</span>
                                            @endif
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($tool->description)
                            <div class="mt-6 p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-100">
                                <dt class="text-sm font-medium text-gray-600 mb-2 flex items-center">
                                    <i class="fas fa-align-left mr-2"></i>Descripción
                                </dt>
                                <dd class="text-gray-900 leading-relaxed">{{ $tool->description }}</dd>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Historial de Préstamos -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-history mr-3"></i>Historial de Préstamos
                        </h2>
                    </div>
                    <div class="p-6">
                        @if($loanHistory->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Préstamo</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Solicitante</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Programa</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Cantidad</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Estado</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Fechas</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($loanHistory as $item)
                                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                                <td class="py-4 px-4">
                                                    <a href="{{ route('loans.show', $item->toolLoan) }}"
                                                       class="text-blue-600 hover:text-blue-800 font-medium hover:underline transition-colors">
                                                        {{ $item->toolLoan->loan_number }}
                                                    </a>
                                                </td>
                                                <td class="py-4 px-4 text-gray-900">{{ $item->toolLoan->user->name }}</td>
                                                <td class="py-4 px-4 text-gray-700">{{ $item->toolLoan->technicalProgram->name }}</td>
                                                <td class="py-4 px-4">
                                                    <div class="text-gray-900">{{ $item->quantity_requested }}</div>
                                                    @if($item->quantity_returned)
                                                        <div class="text-sm text-green-600">({{ $item->quantity_returned }} devueltos)</div>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-4">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                        @if($item->toolLoan->status === 'pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                                                        @elseif($item->toolLoan->status === 'approved') bg-blue-100 text-blue-800 border border-blue-200
                                                        @elseif($item->toolLoan->status === 'delivered') bg-green-100 text-green-800 border border-green-200
                                                        @elseif($item->toolLoan->status === 'returned') bg-gray-100 text-gray-800 border border-gray-200
                                                        @else bg-red-100 text-red-800 border border-red-200
                                                        @endif">
                                                        {{ ucfirst($item->toolLoan->status) }}
                                                    </span>
                                                </td>
                                                <td class="py-4 px-4 text-sm">
                                                    <div class="text-gray-900">{{ $item->toolLoan->loan_date->format('d/m/Y') }}</div>
                                                    @if($item->toolLoan->actual_return_date)
                                                        <div class="text-green-600">Devuelto: {{ $item->toolLoan->actual_return_date->format('d/m/Y') }}</div>
                                                    @else
                                                        <div class="text-gray-500">Esperado: {{ $item->toolLoan->expected_return_date->format('d/m/Y') }}</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6">
                                {{ $loanHistory->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-history text-3xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Sin historial de préstamos</h3>
                                <p class="text-gray-500">Esta herramienta aún no ha sido prestada.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Estadísticas de Stock -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <i class="fas fa-chart-bar mr-3"></i>Estado del Stock
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-boxes text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-blue-900">Total</p>
                                        <p class="text-2xl font-bold text-blue-600">{{ $tool->total_quantity }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-check-circle text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-900">Disponible</p>
                                        <p class="text-2xl font-bold text-green-600">{{ $tool->available_quantity }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-4 rounded-xl border border-yellow-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-handshake text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-yellow-900">En Préstamo</p>
                                        <p class="text-2xl font-bold text-yellow-600">{{ $tool->total_quantity - $tool->available_quantity }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barra de progreso -->
                        <div class="mt-4">
                            @php
                                $usage_percentage = $tool->total_quantity > 0 ? (($tool->total_quantity - $tool->available_quantity) / $tool->total_quantity) * 100 : 0;
                            @endphp
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Uso del inventario</span>
                                <span>{{ round($usage_percentage) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-500"
                                     style="width: {{ $usage_percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Valor Total -->
                @if($tool->unit_price)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-600 to-orange-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <i class="fas fa-calculator mr-3"></i>Valor del Inventario
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900 mb-2">
                                ${{ number_format($tool->total_quantity * $tool->unit_price, 2) }}
                            </div>
                            <p class="text-gray-600">Valor total del stock</p>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Disponible:</span>
                                    <span class="font-medium">${{ number_format($tool->available_quantity * $tool->unit_price, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm mt-1">
                                    <span class="text-gray-600">En préstamo:</span>
                                    <span class="font-medium">${{ number_format(($tool->total_quantity - $tool->available_quantity) * $tool->unit_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Acciones Rápidas -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <i class="fas fa-bolt mr-3"></i>Acciones Rápidas
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @can('update', $tool)
                            <a href="{{ route('tools.edit', $tool) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-edit mr-2"></i>Editar Herramienta
                            </a>
                        @endcan

                        <a href="{{ route('loans.create', ['tool' => $tool->id]) }}"
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-medium rounded-lg hover:from-green-700 hover:to-green-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-plus mr-2"></i>Solicitar Préstamo
                        </a>

                        <a href="{{ route('tools.index', ['category' => $tool->category]) }}"
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-layer-group mr-2"></i>Ver Categoría
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
