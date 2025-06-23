@extends('layouts.app')

@section('title', 'Usuarios')
@section('header', 'Gestión de Usuarios')

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
                        <li class="text-blue-600 font-medium">Usuarios</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Gestión de Usuarios
                </h1>
                <p class="text-gray-600 mt-2">Administra usuarios, roles y permisos del sistema</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('invitations.create') }}"
                   class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-user-plus mr-2"></i>Invitar Usuario
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Usuarios</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $users->total() }}</p>
                        <p class="text-sm text-blue-600 mt-1">
                            <i class="fas fa-users mr-1"></i>Registrados
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
                        <p class="text-sm font-medium text-gray-600">Administradores</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['admins'] ?? 0 }}</p>
                        <p class="text-sm text-purple-600 mt-1">
                            <i class="fas fa-crown mr-1"></i>Activos
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-4 rounded-xl">
                        <i class="fas fa-crown text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Profesores</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['teachers'] ?? 0 }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            <i class="fas fa-chalkboard-teacher mr-1"></i>Activos
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-4 rounded-xl">
                        <i class="fas fa-chalkboard-teacher text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Personal Logística</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['logistics'] ?? 0 }}</p>
                        <p class="text-sm text-orange-600 mt-1">
                            <i class="fas fa-truck mr-1"></i>Activos
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-4 rounded-xl">
                        <i class="fas fa-truck text-2xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtros de Búsqueda</h3>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nombre, email..."
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                    <select name="role" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">Todos los roles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>Profesor</option>
                        <option value="logistics" {{ request('role') === 'logistics' ? 'selected' : '' }}>Personal de Logística</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Programa</label>
                    <select name="technical_program_id" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">Todos los programas</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}" {{ request('technical_program_id') == $program->id ? 'selected' : '' }}>
                                {{ $program->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                        <option value="incomplete" {{ request('status') === 'incomplete' ? 'selected' : '' }}>Perfil Incompleto</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-filter mr-2"></i>Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white/70 backdrop-blur-sm border border-white/20 rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Programa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Último Acceso</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-lg mr-4">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            @if($user->identification_number)
                                                <div class="text-xs text-gray-400">ID: {{ $user->identification_number }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mr-1
                                            @if($role->name === 'admin') bg-purple-100 text-purple-800
                                            @elseif($role->name === 'teacher') bg-green-100 text-green-800
                                            @else bg-orange-100 text-orange-800
                                            @endif">
                                            @if($role->name === 'admin') <i class="fas fa-crown mr-1"></i>
                                            @elseif($role->name === 'teacher') <i class="fas fa-chalkboard-teacher mr-1"></i>
                                            @else <i class="fas fa-truck mr-1"></i>
                                            @endif
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $user->technicalProgram?->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->profile_completed)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perfil Incompleto
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('users.show', $user) }}"
                                       class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>Ver
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}"
                                       class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>Editar
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <button onclick="confirmDelete({{ $user->id }})"
                                                class="text-red-600 hover:text-red-900 transition-colors">
                                            <i class="fas fa-trash mr-1"></i>Eliminar
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-500 text-lg">No se encontraron usuarios</p>
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
        @if($users->hasPages())
            <div class="flex justify-center">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Confirmar Eliminación</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    ¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.
                </p>
            </div>
            <div class="items-center px-4 py-3 flex justify-center space-x-4">
                <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-xl shadow-sm hover:bg-gray-400 transition-colors">
                    Cancelar
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-xl shadow-sm hover:bg-red-700 transition-colors">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(userId) {
    document.getElementById('deleteForm').action = `/users/${userId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
