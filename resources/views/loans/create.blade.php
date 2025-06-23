@extends('layouts.app')

@section('title', 'Solicitar Préstamo')
@section('header', 'Nueva Solicitud de Préstamo')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
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
                            <span class="text-gray-700 font-medium">Nueva Solicitud</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Solicitar Préstamo de Herramientas</h1>
                    <p class="text-lg text-gray-600">Complete el formulario para solicitar herramientas</p>
                </div>
                <div class="flex space-x-3 mt-4 sm:mt-0">
                    <a href="{{ route('loans.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transform hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <form method="POST" action="{{ route('loans.store') }}" id="loanForm">
                @csrf

                @if ($errors->any())
                    <div class="m-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
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

                <!-- Información del Préstamo -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-clipboard-list mr-3"></i>Información del Préstamo
                    </h2>
                </div>

                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Programa Técnico -->
                        <div>
                            <label for="technical_program_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-graduation-cap mr-2 text-gray-500"></i>Programa Técnico <span class="text-red-500">*</span>
                            </label>
                            <select name="technical_program_id" id="technical_program_id" required onchange="loadClassrooms()"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('technical_program_id') border-red-500 ring-2 ring-red-200 @enderror">
                                <option value="">Seleccionar Programa Técnico</option>
                                @foreach($technicalPrograms as $program)
                                    <option value="{{ $program->id }}" {{ old('technical_program_id') == $program->id ? 'selected' : '' }}>
                                        🎓 {{ $program->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('technical_program_id')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Aula -->
                        <div>
                            <label for="classroom_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-door-open mr-2 text-gray-500"></i>Aula <span class="text-red-500">*</span>
                            </label>
                            <select name="classroom_id" id="classroom_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('classroom_id') border-red-500 ring-2 ring-red-200 @enderror">
                                <option value="">Seleccionar Aula</option>
                            </select>
                            @error('classroom_id')
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
                            <select name="warehouse_id" id="warehouse_id" required onchange="loadTools()"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('warehouse_id') border-red-500 ring-2 ring-red-200 @enderror">
                                <option value="">Seleccionar Almacén</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
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

                        <!-- Fecha de Préstamo -->
                        <div>
                            <label for="loan_date" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-2 text-gray-500"></i>Fecha de Préstamo <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="loan_date" id="loan_date" value="{{ old('loan_date', date('Y-m-d')) }}" required
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('loan_date') border-red-500 ring-2 ring-red-200 @enderror">
                            @error('loan_date')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Fecha de Devolución -->
                        <div>
                            <label for="expected_return_date" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-check mr-2 text-gray-500"></i>Fecha de Devolución <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="expected_return_date" id="expected_return_date" value="{{ old('expected_return_date') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('expected_return_date') border-red-500 ring-2 ring-red-200 @enderror">
                            @error('expected_return_date')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Notas -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sticky-note mr-2 text-gray-500"></i>Notas Adicionales
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('notes') border-red-500 ring-2 ring-red-200 @enderror"
                                  placeholder="Propósito del préstamo, instrucciones especiales, etc.">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Selección de Herramientas -->
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-tools mr-3"></i>Selección de Herramientas
                        </h2>
                        <button type="button" onclick="addToolRow()"
                                class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 backdrop-blur-sm">
                            <i class="fas fa-plus mr-2"></i>Agregar Herramienta
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div id="tools-container" class="space-y-4">
                        @if(old('tools'))
                            @foreach(old('tools') as $index => $tool)
                                <div class="tool-row bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl p-6 border border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-wrench mr-2 text-gray-500"></i>Herramienta
                                            </label>
                                            <select name="tools[{{ $index }}][tool_id]" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                                <option value="">Seleccionar Herramienta</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-hashtag mr-2 text-gray-500"></i>Cantidad
                                            </label>
                                            <input type="number" name="tools[{{ $index }}][quantity]" value="{{ $tool['quantity'] ?? '' }}" required min="1"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                        </div>
                                        <div class="flex items-end">
                                            <button type="button" onclick="removeToolRow(this)"
                                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-medium rounded-xl hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                                    <i class="fas fa-trash mr-2"></i>Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="tool-row bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl p-6 border border-gray-200">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-wrench mr-2 text-gray-500"></i>Herramienta
                                        </label>
                                        <select name="tools[0][tool_id]" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                            <option value="">Seleccionar Herramienta</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-hashtag mr-2 text-gray-500"></i>Cantidad
                                        </label>
                                        <input type="number" name="tools[0][quantity]" required min="1"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" onclick="removeToolRow(this)"
                                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-medium rounded-xl hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                            <i class="fas fa-trash mr-2"></i>Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @error('tools')
                        <p class="mt-4 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror

                    <!-- Información de ayuda -->
                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mr-3 mt-0.5"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-2">Instrucciones:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Seleccione primero el almacén para ver las herramientas disponibles</li>
                                    <li>Puede agregar múltiples herramientas al mismo préstamo</li>
                                    <li>Verifique la disponibilidad antes de solicitar</li>
                                    <li>Las fechas de devolución no pueden ser anteriores a la fecha de préstamo</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('loans.index') }}"
                       class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 font-medium rounded-xl border border-gray-300 hover:bg-gray-50 transform hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-paper-plane mr-2"></i>Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let toolRowIndex = 1;
let availableTools = @json($tools);

function loadClassrooms() {
    const programId = document.getElementById('technical_program_id').value;
    const classroomSelect = document.getElementById('classroom_id');

    classroomSelect.innerHTML = '<option value="">Cargando...</option>';

    if (!programId) {
        classroomSelect.innerHTML = '<option value="">Seleccionar Aula</option>';
        return;
    }

    fetch(`/api/classrooms?technical_program_id=${programId}`)
        .then(response => response.json())
        .then(data => {
            classroomSelect.innerHTML = '<option value="">Seleccionar Aula</option>';
            data.forEach(classroom => {
                classroomSelect.innerHTML += `<option value="${classroom.id}">🚪 ${classroom.name} (${classroom.code})</option>`;
            });
        })
        .catch(error => {
            console.error('Error loading classrooms:', error);
            classroomSelect.innerHTML = '<option value="">Error al cargar aulas</option>';
        });
}

function loadTools() {
    const warehouseId = document.getElementById('warehouse_id').value;
    const toolSelects = document.querySelectorAll('select[name*="[tool_id]"]');

    toolSelects.forEach(select => {
        const currentValue = select.value;
        select.innerHTML = '<option value="">Seleccionar Herramienta</option>';

        if (warehouseId) {
            const warehouseTools = availableTools.filter(tool => tool.warehouse_id == warehouseId);
            warehouseTools.forEach(tool => {
                const selected = tool.id == currentValue ? 'selected' : '';
                const availabilityIcon = tool.available_quantity > 0 ? '✅' : '❌';
                select.innerHTML += `<option value="${tool.id}" ${selected}>${availabilityIcon} ${tool.name} (${tool.code}) - Disponible: ${tool.available_quantity}</option>`;
            });
        }
    });
}

function addToolRow() {
    const container = document.getElementById('tools-container');
    const warehouseId = document.getElementById('warehouse_id').value;

    let toolOptions = '<option value="">Seleccionar Herramienta</option>';
    if (warehouseId) {
        const warehouseTools = availableTools.filter(tool => tool.warehouse_id == warehouseId);
        warehouseTools.forEach(tool => {
            const availabilityIcon = tool.available_quantity > 0 ? '✅' : '❌';
            toolOptions += `<option value="${tool.id}">${availabilityIcon} ${tool.name} (${tool.code}) - Disponible: ${tool.available_quantity}</option>`;
        });
    }

    const newRow = `
        <div class="tool-row bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl p-6 border border-gray-200 animate-fade-in">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-wrench mr-2 text-gray-500"></i>Herramienta
                    </label>
                    <select name="tools[${toolRowIndex}][tool_id]" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        ${toolOptions}
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-hashtag mr-2 text-gray-500"></i>Cantidad
                    </label>
                    <input type="number" name="tools[${toolRowIndex}][quantity]" required min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="removeToolRow(this)"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-medium rounded-xl hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-trash mr-2"></i>Eliminar
                    </button>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', newRow);
    toolRowIndex++;
}

function removeToolRow(button) {
    const toolRows = document.querySelectorAll('.tool-row');
    if (toolRows.length > 1) {
        button.closest('.tool-row').classList.add('animate-fade-out');
        setTimeout(() => {
            button.closest('.tool-row').remove();
        }, 300);
    } else {
        alert('Debe seleccionar al menos una herramienta.');
    }
}

// Validación de fechas
document.getElementById('loan_date').addEventListener('change', function() {
    const loanDate = this.value;
    const returnDateInput = document.getElementById('expected_return_date');

    if (loanDate) {
        const minReturnDate = new Date(loanDate);
        minReturnDate.setDate(minReturnDate.getDate() + 1);
        returnDateInput.min = minReturnDate.toISOString().split('T')[0];

        if (returnDateInput.value && returnDateInput.value <= loanDate) {
            returnDateInput.value = minReturnDate.toISOString().split('T')[0];
        }
    }
});

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    const programId = document.getElementById('technical_program_id').value;
    const warehouseId = document.getElementById('warehouse_id').value;

    if (programId) {
        loadClassrooms();
    }

    if (warehouseId) {
        loadTools();
    }
});
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-out {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(-10px); }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

.animate-fade-out {
    animation: fade-out 0.3s ease-out;
}
</style>
@endsection
