@extends('layouts.app')

@section('title', 'Análisis de Uso')
@section('header', 'Análisis de Uso')

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
                        <li class="text-blue-600 font-medium">Análisis de Uso</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Análisis de Uso
                </h1>
                <p class="text-gray-600 mt-2">Estadísticas y tendencias de uso de herramientas</p>
            </div>
        </div>

        <!-- Usage Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Herramientas Más Usadas</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $most_used_tools->count() }}</p>
                        <p class="text-sm text-blue-600 mt-1">
                            <i class="fas fa-star mr-1"></i>Top herramientas
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 rounded-xl">
                        <i class="fas fa-trophy text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Promedio de Uso</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($averageUsage, 1) }}%</p>
                        <p class="text-sm text-green-600 mt-1">
                            <i class="fas fa-chart-line mr-1"></i>Utilización general
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-4 rounded-xl">
                        <i class="fas fa-percentage text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Categorías Activas</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $activeCategories }}</p>
                        <p class="text-sm text-purple-600 mt-1">
                            <i class="fas fa-tags mr-1"></i>En uso activo
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-4 rounded-xl">
                        <i class="fas fa-layer-group text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tiempo Promedio</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $averageLoanDuration }}</p>
                        <p class="text-sm text-orange-600 mt-1">
                            <i class="fas fa-clock mr-1"></i>Días por préstamo
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-4 rounded-xl">
                        <i class="fas fa-hourglass-half text-2xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Most Used Tools -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl">
                <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-trophy text-yellow-500 mr-3"></i>
                    Herramientas Más Utilizadas
                </h3>
                <div class="space-y-4">
                    @forelse($most_used_tools as $index => $tool)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white font-bold text-sm mr-4">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $tool->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $tool->category }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-blue-600">{{ $tool->loan_count }}</div>
                                <div class="text-sm text-gray-500">préstamos</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-chart-bar text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No hay datos de uso disponibles</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Usage by Category -->
            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl">
                <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-chart-pie text-green-500 mr-3"></i>
                    Uso por Categoría
                </h3>
                <div class="space-y-4">
                    @forelse($usage_by_category as $category)
                        <div class="p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-900">{{ $category->category }}</span>
                                <span class="text-sm font-semibold text-blue-600">{{ $category->loan_count }} préstamos</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-500"
                                     style="width: {{ ($category->loan_count / $usage_by_category->max('loan_count')) * 100 }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>{{ $category->tools_count }} herramientas</span>
                                <span>{{ number_format(($category->loan_count / $usage_by_category->sum('loan_count')) * 100, 1) }}%</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-tags text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No hay datos de categorías disponibles</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Usage by Program -->
        <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl">
            <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-graduation-cap text-purple-500 mr-3"></i>
                Uso por Programa Técnico
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($usage_by_program as $program)
                    <div class="p-6 bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-100 hover:shadow-lg transition-all duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">{{ $program->name }}</h4>
                            <span class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                {{ $program->total_loans }}
                            </span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Préstamos activos:</span>
                                <span class="font-medium text-green-600">{{ $program->active_loans }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Herramientas únicas:</span>
                                <span class="font-medium text-blue-600">{{ $program->unique_tools }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Promedio duración:</span>
                                <span class="font-medium text-orange-600">{{ $program->avg_duration }} días</span>
                            </div>
                        </div>
                        <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2 rounded-full transition-all duration-500"
                                 style="width: {{ ($program->total_loans / $usage_by_program->max('total_loans')) * 100 }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <i class="fas fa-graduation-cap text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No hay datos de programas disponibles</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Monthly Trends -->
        @if($monthlyTrends->count() > 0)
        <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl">
            <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-chart-line text-blue-500 mr-3"></i>
                Tendencias Mensuales
            </h3>
            <div class="h-80 flex items-end justify-between space-x-2">
                @foreach($monthlyTrends->reverse() as $trend)
                    <div class="flex flex-col items-center flex-1 group">
                        <div class="bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg hover:from-blue-600 hover:to-blue-500 transition-all duration-300 group-hover:scale-105"
                             style="height: {{ ($trend->total_loans / $monthlyTrends->max('total_loans')) * 250 }}px; min-height: 20px; width: 100%;">
                        </div>
                        <div class="text-xs text-gray-600 mt-3 text-center">
                            {{ date('M', mktime(0, 0, 0, $trend->month, 1)) }}<br>{{ $trend->year }}
                        </div>
                        <div class="text-sm font-semibold text-gray-900 mt-1">{{ $trend->total_loans }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Additional Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Usuarios con más préstamos</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $top_borrowers->count() }}</p>
                        <p class="text-sm text-blue-600 mt-1">
                            <i class="fas fa-user mr-1"></i>Top usuarios
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 rounded-xl">
                        <i class="fas fa-users text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Herramientas dañadas/perdidas</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $damaged_lost_tools }}</p>
                        <p class="text-sm text-red-600 mt-1">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Incidentes
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-red-500 to-red-600 p-4 rounded-xl">
                        <i class="fas fa-wrench text-2xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
