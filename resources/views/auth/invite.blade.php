@extends('layouts.app')

@section('title', 'Invitar Usuario')
@section('header', 'Invitar Nuevo Usuario')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Invitation Form -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Enviar Invitación</h3>

            <form method="POST" action="{{ route('invitations.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                               placeholder="usuario@ejemplo.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Rol *</label>
                        <select name="role" id="role" required onchange="toggleTechnicalProgram()"
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror">
                            <option value="">Seleccionar Rol</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Profesor</option>
                            <option value="logistics" {{ old('role') === 'logistics' ? 'selected' : '' }}>Personal de Logística</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div id="technical_program_field" style="display: none;">
                    <label for="technical_program_id" class="block text-sm font-medium text-gray-700">Programa Técnico *</label>
                    <select name="technical_program_id" id="technical_program_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('technical_program_id') border-red-500 @enderror">
                        <option value="">Seleccionar Programa</option>
                        @foreach($technicalPrograms as $program)
                            <option value="{{ $program->id }}" {{ old('technical_program_id') == $program->id ? 'selected' : '' }}>
                                {{ $program->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('technical_program_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Los profesores deben tener un programa técnico asignado.</p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex">
                        <i class="fas fa-info-circle text-blue-400 mr-3 mt-0.5"></i>
                        <div class="text-sm text-blue-800">
                            <p><strong>¿Cómo funciona?</strong></p>
                            <ul class="mt-2 list-disc list-inside space-y-1">
                                <li>Se enviará un correo de invitación al usuario</li>
                                <li>El enlace será válido por 24 horas</li>
                                <li>El usuario completará su perfil y establecerá su contraseña</li>
                                <li>Una vez completado, tendrá acceso al sistema</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('users.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-paper-plane mr-2"></i>Enviar Invitación
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pending Invitations -->
    @if($pendingInvitations->count() > 0)
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Invitaciones Pendientes</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Programa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enviado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingInvitations as $invitation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $invitation->email }}</div>
                                        <div class="text-sm text-gray-500">Invitado por {{ $invitation->invitedBy->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($invitation->role === 'admin') bg-purple-100 text-purple-800
                                        @elseif($invitation->role === 'teacher') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($invitation->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $invitation->technicalProgram?->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($invitation->isExpired())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Expirada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $invitation->created_at->diffForHumans() }}
                                    <div class="text-xs text-gray-400">
                                        Expira: {{ $invitation->expires_at->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    @if(!$invitation->isExpired())
                                        <form method="POST" action="{{ route('invitations.resend', $invitation) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-redo mr-1"></i>Reenviar
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('invitations.cancel', $invitation) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('¿Estás seguro de cancelar esta invitación?')">
                                            <i class="fas fa-times mr-1"></i>Cancelar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function toggleTechnicalProgram() {
    const roleSelect = document.getElementById('role');
    const programField = document.getElementById('technical_program_field');
    const programSelect = document.getElementById('technical_program_id');

    if (roleSelect.value === 'teacher') {
        programField.style.display = 'block';
        programSelect.required = true;
    } else {
        programField.style.display = 'none';
        programSelect.required = false;
        programSelect.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleTechnicalProgram();
});
</script>
@endsection
