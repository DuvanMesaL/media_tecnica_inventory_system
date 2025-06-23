@extends('layouts.app')

@section('title', 'Editar Almacén')
@section('header', 'Editar Almacén')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header with Breadcrumb -->
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Dashboard</a></li>
                    <li><i class="fas fa-chevron-right text-gray-400 mx-2"></i></li>
                    <li><a href="{{ route('warehouses.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Almacenes</a></li>
                    <li><i class="fas fa-chevron-right text-gray-400 mx-2"></i></li>
                    <li><a href="{{ route('warehouses.show', $warehouse) }}" class="text-gray-500 hover:text-blue-600 transition-colors">{{ $warehouse->name }}</a></li>
                    <li><i class="fas fa-chevron-right text-gray-400 mx-2"></i></li>
                    <li class="text-blue-600 font-medium">Editar</li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                Editar Almacén
            </h1>
            <p class="text-gray-600 mt-2">Modifica la información del almacén {{ $warehouse->name }}</p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-edit mr-3"></i>
                        Información del Almacén
                    </h2>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('warehouses.update', $warehouse) }}" class="space-y-8">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span class="font-semibold">Por favor corrige los siguientes errores:</span>
                                </div>
                                <ul class="list-disc list-inside text-sm space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Basic Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                                Información Básica
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-warehouse text-gray-400 mr-2"></i>
                                        Nombre del Almacén *
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $warehouse->name) }}" required
                                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror"
                                           placeholder="Ej: Almacén Principal">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-barcode text-gray-400 mr-2"></i>
                                        Código *
                                    </label>
                                    <input type="text" name="code" id="code" value="{{ old('code', $warehouse->code) }}" required
                                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('code') border-red-500 @enderror"
                                           placeholder="Ej: ALM-001">
                                    @error('code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                    Ubicación *
                                </label>
                                <input type="text" name="location" id="location" value="{{ old('location', $warehouse->location) }}" required
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('location') border-red-500 @enderror"
                                       placeholder="Ej: Edificio A, Planta Baja">
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-align-left text-gray-400 mr-2"></i>
                                    Descripción
                                </label>
                                <textarea name="description" id="description" rows="4"
                                          class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('description') border-red-500 @enderror"
                                          placeholder="Descripción opcional del almacén...">{{ old('description', $warehouse->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-toggle-on text-green-500 mr-3"></i>
                                Estado del Almacén
                            </h3>

                            <div class="bg-gray-50 rounded-xl p-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1"
                                           {{ old('is_active', $warehouse->is_active) ? 'checked' : '' }}
                                           class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-all">
                                    <label for="is_active" class="ml-3 block text-sm font-medium text-gray-900">
                                        Almacén activo
                                    </label>
                                </div>
                                <p class="mt-2 text-sm text-gray-600">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Los almacenes inactivos no aparecerán en las opciones de selección para nuevas herramientas.
                                </p>
                            </div>
                        </div>

                        <!-- Warning if has tools -->
                        @if($warehouse->tools->count() > 0)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-yellow-400 mr-3 mt-0.5"></i>
                                <div class="text-sm text-yellow-800">
                                    <p class="font-semibold">Atención:</p>
                                    <p>Este almacén contiene {{ $warehouse->tools->count() }} herramientas. Si desactivas el almacén, las herramientas existentes no se verán afectadas, pero no podrás agregar nuevas herramientas a este almacén.</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('warehouses.show', $warehouse) }}"
                               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium transition-all duration-200 flex items-center">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-8 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center">
                                <i class="fas fa-save mr-2"></i>
                                Actualizar Almacén
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
