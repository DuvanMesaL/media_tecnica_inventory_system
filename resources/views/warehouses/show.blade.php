@extends('layouts.app')

@section('title', 'Almacén - ' . $warehouse->name)
@section('header', 'Detalles del Almacén')

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
                        <li><a href="{{ route('warehouses.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Almacenes</a></li>
                        <li><i class="fas fa-chevron-right text-gray-400 mx-2"></i></li>
                        <li class="text-blue-600 font-medium">{{ $warehouse->name }}</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    {{ $warehouse->name }}
                </h1>
                <p class="text-gray-600 mt-2">{{ $warehouse->location }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('warehouses.edit', $warehouse) }}"
                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-edit mr-2"></i>Editar
                </a>
            </div>
        </div>

        <!-- Warehouse Info and Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-warehouse text-blue-500 mr-3"></i>
                        Información del Almacén
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Código</label>
                            <div class="bg-gray-50 rounded-xl px-4 py-3 text-gray-900 font-mono">{{ $warehouse->code }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Estado</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $warehouse->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-circle text-xs mr-2"></i>
                                {{ $warehouse->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600 mb-2">Ubicación</label>
                            <div class="bg-gray-50 rounded-xl px-4 py-3 text-gray-900">{{ $warehouse->location }}</div>
                        </div>
                        @if($warehouse->description)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600 mb-2">Descripción</label>
                            <div class="bg-gray-50 rounded-xl px-4 py-3 text-gray-900">{{ $warehouse->description }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Tools in Warehouse -->
                <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-tools text-green-500 mr-3"></i>
                            Herramientas en el Almacén
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Herramienta</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stock</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Condición</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Valor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($warehouse->tools as $tool)
                                    <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $tool->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $tool->code }} - {{ $tool->category }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                Disponible: <span class="font-semibold {{ $tool->available_quantity <= 5 ? 'text-red-600' : ($tool->available_quantity <= 10 ? 'text-yellow-600' : 'text-green-600') }}">
                                                    {{ $tool->available_quantity }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-500">Total: {{ $tool->total_quantity }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                @if($tool->condition === 'good') bg-green-100 text-green-800
                                                @elseif($tool->condition === 'damaged') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                @if($tool->condition === 'good') <i class="fas fa-check mr-1"></i>
                                                @elseif($tool->condition === 'damaged') <i class="fas fa-exclamation-triangle mr-1"></i>
                                                @else <i class="fas fa-times mr-1"></i>
                                                @endif
                                                {{ ucfirst($tool->condition) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($tool->unit_price)
                                                <div>Unitario: ${{ number_format($tool->unit_price, 2) }}</div>
                                                <div class="font-semibold">Total: ${{ number_format($tool->total_quantity * $tool->unit_price, 2) }}</div>
                                            @else
                                                <span class="text-gray-400">No especificado</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('tools.show', $tool) }}"
                                               class="text-blue-600 hover:text-blue-900 transition-colors">
                                                <i class="fas fa-eye mr-1"></i>Ver
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                                                <p class="text-gray-500 text-lg">No hay herramientas en este almacén</p>
                                                <a href="{{ route('tools.create') }}" class="mt-4 text-blue-600 hover:text-blue-800 transition-colors">
                                                    <i class="fas fa-plus mr-1"></i>Agregar primera herramienta
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Stats -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-chart-bar text-purple-500 mr-3"></i>
                        Estadísticas Rápidas
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-tools text-blue-600 mr-3"></i>
                                <span class="text-sm font-medium text-gray-700">Total Herramientas</span>
                            </div>
                            <span class="text-lg font-bold text-blue-600">{{ $warehouse->tools->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-green-100 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                                <span class="text-sm font-medium text-gray-700">Disponibles</span>
                            </div>
                            <span class="text-lg font-bold text-green-600">{{ $warehouse->tools->sum('available_quantity') }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-handshake text-yellow-600 mr-3"></i>
                                <span class="text-sm font-medium text-gray-700">En Préstamo</span>
                            </div>
                            <span class="text-lg font-bold text-yellow-600">{{ $warehouse->tools->sum('total_quantity') - $warehouse->tools->sum('available_quantity') }}</span>
                        </div>

                        @if($warehouse->tools->where('unit_price', '>', 0)->count() > 0)
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-dollar-sign text-purple-600 mr-3"></i>
                                <span class="text-sm font-medium text-gray-700">Valor Total</span>
                            </div>
                            <span class="text-lg font-bold text-purple-600">
                                ${{ number_format($warehouse->tools->sum(function($tool) { return $tool->total_quantity * ($tool->unit_price ?? 0); }), 2) }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Categories Distribution -->
                @if($warehouse->tools->count() > 0)
                <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-tags text-orange-500 mr-3"></i>
                        Distribución por Categoría
                    </h3>
                    <div class="space-y-3">
                        @foreach($warehouse->tools->groupBy('category') as $category => $tools)
                            <div class="p-3 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-900">{{ $category }}</span>
                                    <span class="text-sm font-semibold text-blue-600">{{ $tools->count() }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-500"
                                         style="width: {{ ($tools->count() / $warehouse->tools->count()) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-bolt text-yellow-500 mr-3"></i>
                        Acciones Rápidas
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('tools.create', ['warehouse_id' => $warehouse->id]) }}"
                           class="flex items-center w-full p-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-plus mr-3"></i>
                            Agregar Herramienta
                        </a>

                        <a href="{{ route('tools.index', ['warehouse_id' => $warehouse->id]) }}"
                           class="flex items-center w-full p-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-list mr-3"></i>
                            Ver Todas las Herramientas
                        </a>

                        <a href="{{ route('reports.tools', ['warehouse_id' => $warehouse->id]) }}"
                           class="flex items-center w-full p-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-xl font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-chart-bar mr-3"></i>
                            Generar Reporte
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
