@extends('layouts.app')

@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="page-title">
            <h1>Users</h1>
            <p class="page-subtitle">Tuesday, June 24, 2025</p>
        </div>
        <div class="page-actions">
            <button class="btn btn-primary" onclick="window.location.href='{{ route('invitations.create') }}'">
                <i class="fas fa-plus"></i>
                Quick Add
            </button>
        </div>
    </div>

    <div class="breadcrumb-nav">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </nav>
    </div>

    <div class="content-section">
        <div class="section-header">
            <h2>Gestión de Usuarios</h2>
            <p class="section-subtitle">Administra usuarios, roles y permisos del sistema</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $users->total() }}</div>
                    <div class="stat-label">Total Usuarios</div>
                    <div class="stat-sublabel">Registrados</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon admins">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['admins'] ?? 0 }}</div>
                    <div class="stat-label">Administradores</div>
                    <div class="stat-sublabel">Activos</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon teachers">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['teachers'] ?? 0 }}</div>
                    <div class="stat-label">Profesores</div>
                    <div class="stat-sublabel">Activos</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon logistics">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['logistics'] ?? 0 }}</div>
                    <div class="stat-label">Personal Logística</div>
                    <div class="stat-sublabel">Activos</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <h3>Filtros de Búsqueda</h3>
            <form method="GET" class="filters-form">
                <div class="filter-group">
                    <label>Estado</label>
                    <select name="status" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Programa</label>
                    <select name="technical_program_id" class="form-select">
                        <option value="">Todos los programas</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}" {{ request('technical_program_id') == $program->id ? 'selected' : '' }}>
                                {{ $program->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Fecha Desde</label>
                    <input type="date" name="date_from" class="form-input" value="{{ request('date_from') }}">
                </div>
                <div class="filter-group">
                    <label>Fecha Hasta</label>
                    <input type="date" name="date_to" class="form-input" value="{{ request('date_to') }}">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i>
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>

        @if($users->count() > 0)
            <!-- Users Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>USUARIO</th>
                            <th>ROL</th>
                            <th>PROGRAMA</th>
                            <th>FECHAS</th>
                            <th>ESTADO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div class="user-details">
                                            <div class="user-name">{{ $user->name }}</div>
                                            <div class="user-email">{{ $user->email }}</div>
                                            @if($user->identification_number)
                                                <div class="user-id">ID: {{ $user->identification_number }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="role-badge {{ $role->name }}">
                                            @if($role->name === 'admin') <i class="fas fa-crown"></i>
                                            @elseif($role->name === 'teacher') <i class="fas fa-chalkboard-teacher"></i>
                                            @else <i class="fas fa-truck"></i>
                                            @endif
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>
                                    @if($user->technicalProgram)
                                        <span class="program-badge">{{ $user->technicalProgram->name }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="date-info">
                                        <div class="created-date">{{ $user->created_at->format('d/m/Y') }}</div>
                                        <div class="last-login">
                                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca' }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge {{ $user->profile_completed ? 'active' : 'pending' }}">
                                        @if($user->profile_completed)
                                            <i class="fas fa-check-circle"></i> Activo
                                        @else
                                            <i class="fas fa-exclamation-triangle"></i> Pendiente
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('users.show', $user) }}" class="btn-action view" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn-action edit" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <button onclick="confirmDelete('{{ route('users.destroy', $user) }}')"
                                                    class="btn-action delete" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>No se encontraron usuarios</h3>
                <p>Intenta ajustar los filtros de búsqueda</p>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmar Eliminación</h3>
        </div>
        <div class="modal-body">
            <p>¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDeleteModal()">Cancelar</button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
        </div>
    </div>
</div>

<style>
.main-content {
    padding: 24px;
    background: #f8f9fa;
    min-height: 100vh;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
}

.page-title h1 {
    font-size: 32px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.page-subtitle {
    color: #6b7280;
    margin: 4px 0 0 0;
    font-size: 14px;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

.btn-danger {
    background: #dc2626;
    color: white;
}

.btn-danger:hover {
    background: #b91c1c;
}

.breadcrumb-nav {
    margin-bottom: 32px;
}

.breadcrumb {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 8px;
    align-items: center;
}

.breadcrumb-item {
    color: #6b7280;
    font-size: 14px;
}

.breadcrumb-item a {
    color: #3b82f6;
    text-decoration: none;
}

.breadcrumb-item:not(:last-child)::after {
    content: ">";
    margin-left: 8px;
    color: #d1d5db;
}

.content-section {
    background: white;
    border-radius: 12px;
    padding: 32px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.section-header {
    margin-bottom: 32px;
}

.section-header h2 {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 8px 0;
}

.section-subtitle {
    color: #6b7280;
    margin: 0;
    font-size: 14px;
}

.alert {
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-success {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.stat-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.2s;
}

.stat-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-icon.total {
    background: #dbeafe;
    color: #1d4ed8;
}

.stat-icon.admins {
    background: #f3e8ff;
    color: #7c3aed;
}

.stat-icon.teachers {
    background: #d1fae5;
    color: #059669;
}

.stat-icon.logistics {
    background: #fed7aa;
    color: #ea580c;
}

.stat-number {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1;
}

.stat-label {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin: 4px 0 2px 0;
}

.stat-sublabel {
    font-size: 12px;
    color: #6b7280;
}

/* Filters */
.filters-section {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 32px;
}

.filters-section h3 {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 16px 0;
}

.filters-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 8px;
}

.form-select, .form-input {
    padding: 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    background: white;
    transition: border-color 0.2s;
}

.form-select:focus, .form-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Table */
.table-container {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 24px;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: #f9fafb;
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #e5e7eb;
}

.data-table td {
    padding: 16px;
    border-bottom: 1px solid #f3f4f6;
    vertical-align: top;
}

.data-table tr:hover {
    background: #f9fafb;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    background: #3b82f6;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}

.user-name {
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 2px;
}

.user-email {
    font-size: 14px;
    color: #6b7280;
}

.user-id {
    font-size: 12px;
    color: #9ca3af;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    margin-right: 4px;
}

.role-badge.admin {
    background: #f3e8ff;
    color: #7c3aed;
}

.role-badge.teacher {
    background: #d1fae5;
    color: #059669;
}

.role-badge.logistics {
    background: #fed7aa;
    color: #ea580c;
}

.program-badge {
    background: #e0f2fe;
    color: #0369a1;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.date-info {
    font-size: 14px;
}

.created-date {
    color: #1a1a1a;
    font-weight: 500;
    margin-bottom: 2px;
}

.last-login {
    color: #6b7280;
    font-size: 12px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.active {
    background: #dcfce7;
    color: #166534;
}

.status-badge.pending {
    background: #fef3c7;
    color: #d97706;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-action {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-action.view {
    background: #e0f2fe;
    color: #0369a1;
}

.btn-action.view:hover {
    background: #bae6fd;
}

.btn-action.edit {
    background: #fef3c7;
    color: #d97706;
}

.btn-action.edit:hover {
    background: #fde68a;
}

.btn-action.delete {
    background: #fef2f2;
    color: #dc2626;
}

.btn-action.delete:hover {
    background: #fecaca;
}

.empty-state {
    text-align: center;
    padding: 64px 32px;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: #f3f4f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px auto;
    font-size: 32px;
    color: #9ca3af;
}

.empty-state h3 {
    font-size: 20px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 8px 0;
}

.empty-state p {
    color: #6b7280;
    margin: 0 0 24px 0;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 32px;
}

.text-muted {
    color: #6b7280;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}

.modal-content {
    background: white;
    border-radius: 12px;
    padding: 0;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-header {
    padding: 24px 24px 16px 24px;
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #1a1a1a;
}

.modal-body {
    padding: 16px 24px;
}

.modal-body p {
    margin: 0;
    color: #6b7280;
    line-height: 1.5;
}

.modal-footer {
    padding: 16px 24px 24px 24px;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}
</style>

<script>
function confirmDelete(url) {
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
