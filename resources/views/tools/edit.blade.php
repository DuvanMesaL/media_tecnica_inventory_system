@extends('layouts.app')

@section('title', 'Editar Herramienta')
@section('header', 'Editar Herramienta')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('tools.show', $tool) }}" class="text-gray-500 hover:text-blue-600 transition-colors">{{ $tool->name }}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-700 font-medium">Editar</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Editar Herramienta</h1>
                    <p class="text-lg text-gray-600">Actualizar información de {{ $tool->name }}</p>
                </div>
                <div class="flex space-x-3 mt-4 sm:mt-0">
                    <a href="{{ route('tools.show', $tool) }}"
                       class="inline-flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transform hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-edit mr-3"></i>Información de la Herramienta
                </h2>
            </div>

            <form action="{{ route('tools.update', $tool) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span class="font-medium">Por favor corrige los siguientes errores:</span>
                        </div>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Información Básica -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>Información Básica
                        </h3>

                        <div class="space-y-4">
                            <!-- Código -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-barcode mr-2 text-gray-500"></i>Código <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="code"
                                       name="code"
                                       value="{{ old('code', $tool->code) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('code') border-red-500 ring-2 ring-red-200 @enderror"
                                       required>
                                @error('code')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-tag mr-2 text-gray-500"></i>Nombre <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $tool->name) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('name') border-red-500 ring-2 ring-red-200 @enderror"
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Categoría -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-layer-group mr-2 text-gray-500"></i>Categoría <span class="text-red-500">*</span>
                                </label>
                                <select id="category"
                                        name="category"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('category') border-red-500 ring-2 ring-red-200 @enderror"
                                        required>
                                    <option value="">Seleccionar categoría</option>
                                    <option value="herramientas_manuales" {{ old('category', $tool->category) === 'herramientas_manuales' ? 'selected' : '' }}>
                                        🔧 Herramientas Manuales
                                    </option>
                                    <option value="herramientas_electricas" {{ old('category', $tool->category) === 'herramientas_electricas' ? 'selected' : '' }}>
                                        ⚡ Herramientas Eléctricas
                                    </option>
                                    <option value="equipos_medicion" {{ old('category', $tool->category) === 'equipos_medicion' ? 'selected' : '' }}>
                                        📏 Equipos de Medición
                                    </option>
                                    <option value="equipos_seguridad" {{ old('category', $tool->category) === 'equipos_seguridad' ? 'selected' : '' }}>
                                        🦺 Equipos de Seguridad
                                    </option>
                                    <option value="materiales" {{ old('category', $tool->category) === 'materiales' ? 'selected' : '' }}>
                                        📦 Materiales
                                    </option>
                                    <option value="otros" {{ old('category', $tool->category) === 'otros' ? 'selected' : '' }}>
                                        🔹 Otros
                                    </option>
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Almacén -->
                            <div>
                                <label for="warehouse_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-warehouse mr-2 text-gray-500"></i>Almacén <span class="text-red-500">*</span>
                                </label>
                                <select id="warehouse_id"
                                        name="warehouse_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('warehouse_id') border-red-500 ring-2 ring-red-200 @enderror"
                                        required>
                                    <option value="">Seleccionar almacén</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $tool->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                            🏢 {{ $warehouse->name }} - {{ $warehouse->location }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Inventario y Detalles -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-boxes mr-2 text-green-600"></i>Inventario y Detalles
                        </h3>

                        <div class="space-y-4">
                            <!-- Cantidad Total -->
                            <div>
                                <label for="total_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-cubes mr-2 text-gray-500"></i>Cantidad Total <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       id="total_quantity"
                                       name="total_quantity"
                                       value="{{ old('total_quantity', $tool->total_quantity) }}"
                                       min="0"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('total_quantity') border-red-500 ring-2 ring-red-200 @enderror"
                                       required>
                                @error('total_quantity')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Cantidad Disponible -->
                            <div>
                                <label for="available_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-check-circle mr-2 text-gray-500"></i>Cantidad Disponible <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       id="available_quantity"
                                       name="available_quantity"
                                       value="{{ old('available_quantity', $tool->available_quantity) }}"
                                       min="0"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('available_quantity') border-red-500 ring-2 ring-red-200 @enderror"
                                       required>
                                @error('available_quantity')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Condición -->
                            <div>
                                <label for="condition" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-heart-pulse mr-2 text-gray-500"></i>Condición <span class="text-red-500">*</span>
                                </label>
                                <select id="condition"
                                        name="condition"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('condition') border-red-500 ring-2 ring-red-200 @enderror"
                                        required>
                                    <option value="">Seleccionar condición</option>
                                    <option value="good" {{ old('condition', $tool->condition) === 'good' ? 'selected' : '' }}>
                                        ✅ Buena - Listo para usar
                                    </option>
                                    <option value="damaged" {{ old('condition', $tool->condition) === 'damaged' ? 'selected' : '' }}>
                                        ⚠️ Dañado - Necesita reparación
                                    </option>
                                    <option value="lost" {{ old('condition', $tool->condition) === 'lost' ? 'selected' : '' }}>
                                        ❌ Perdido - No disponible
                                    </option>
                                </select>
                                @error('condition')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Precio Unitario -->
                            <div>
                                <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-dollar-sign mr-2 text-gray-500"></i>Precio Unitario
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                    <input type="number"
                                           id="unit_price"
                                           name="unit_price"
                                           value="{{ old('unit_price', $tool->unit_price) }}"
                                           min="0"
                                           step="0.01"
                                           class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('unit_price') border-red-500 ring-2 ring-red-200 @enderror"
                                           placeholder="0.00">
                                </div>
                                @error('unit_price')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="mt-8">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-gray-500"></i>Descripción
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('description') border-red-500 ring-2 ring-red-200 @enderror"
                              placeholder="Descripción detallada de la herramienta, especificaciones técnicas, etc.">{{ old('description', $tool->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Imagen -->
                <div class="mt-8">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-image mr-2 text-gray-500"></i>Imagen de la Herramienta
                    </label>

                    @if($tool->image)
                        <div class="mb-4 p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200">
                            <p class="text-sm text-gray-600 mb-3 flex items-center">
                                <i class="fas fa-eye mr-2"></i>Imagen actual:
                            </p>
                            <img src="{{ Storage::url($tool->image) }}"
                                 alt="{{ $tool->name }}"
                                 class="w-32 h-32 object-cover rounded-xl shadow-lg border border-gray-200">
                        </div>
                    @endif

                    <div class="relative">
                        <input type="file"
                               id="image"
                               name="image"
                               accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('image') border-red-500 ring-2 ring-red-200 @enderror">
                    </div>
                    <p class="mt-2 text-sm text-gray-500 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB
                    </p>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="mt-8 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('tools.show', $tool) }}"
                       class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 font-medium rounded-xl border border-gray-300 hover:bg-gray-50 transform hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-save mr-2"></i>Actualizar Herramienta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Validación en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const totalQuantityInput = document.getElementById('total_quantity');
    const availableQuantityInput = document.getElementById('available_quantity');

    function validateQuantities() {
        const total = parseInt(totalQuantityInput.value) || 0;
        const available = parseInt(availableQuantityInput.value) || 0;

        if (available > total) {
            availableQuantityInput.setCustomValidity('La cantidad disponible no puede ser mayor que la cantidad total');
        } else {
            availableQuantityInput.setCustomValidity('');
        }
    }

    totalQuantityInput.addEventListener('input', validateQuantities);
    availableQuantityInput.addEventListener('input', validateQuantities);
});
</script>
@endsection
