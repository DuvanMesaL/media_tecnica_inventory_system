@extends('layouts.app')

@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="page-title">
            <h1>User Details</h1>
            <p class="page-subtitle">Tuesday, June 24, 2025</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                Edit User
            </a>
        </div>
    </div>

    <div class="breadcrumb-nav">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                <li class="breadcrumb-item active">{{ $user->name }}</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="content-grid">
        <!-- Main Content -->
        <div class="main-column">
            <!-- User Profile Card -->
            <div class="profile-card">
                <div class="profile-header">
                    <div class="user-avatar large">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="user-info">
                        <h2 class="user-name">{{ $user->name }}</h2>
                        <p class="user-email">{{ $user->email }}</p>
                        <div class="user-badges">
                            <span class="status-badge {{ $user->is_active ? 'active' : 'inactive' }}">
                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                            @foreach($user->roles as $role)
                                <span class="role-badge {{ $role->name }}">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Sections -->
            <div class="info-grid">
                <!-- Personal Information -->
                <div class="info-card">
                    <div class="card-header">
                        <div class="card-icon personal">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3>Información Personal</h3>
                    </div>
                    <div class="card-content">
                        <div class="info-item">
                            <span class="label">ID de Usuario:</span>
                            <span class="value">{{ $user->id }}</span>
                        </div>
                        @if($user->employee_id)
                            <div class="info-item">
                                <span class="label">ID de Empleado:</span>
                                <span class="value">{{ $user->employee_id }}</span>
                            </div>
                        @endif
                        @if($user->phone)
                            <div class="info-item">
                                <span class="label">Teléfono:</span>
                                <span class="value">{{ $user->phone }}</span>
                            </div>
                        @endif
                        <div class="info-item">
                            <span class="label">Programa Técnico:</span>
                            <span class="value">
                                @if($user->technicalProgram)
                                    <span class="program-badge">{{ $user->technicalProgram->name }}</span>
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="info-card">
                    <div class="card-header">
                        <div class="card-icon system">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h3>Estado del Sistema</h3>
                    </div>
                    <div class="card-content">
                        <div class="info-item">
                            <span class="label">Perfil Completado:</span>
                            <span class="value">
                                <span class="status-badge {{ $user->profile_completed ? 'active' : 'pending' }}">
                                    {{ $user->profile_completed ? 'Completado' : 'Pendiente' }}
                                </span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="label">Email Verificado:</span>
                            <span class="value">
                                <span class="status-badge {{ $user->email_verified_at ? 'active' : 'inactive' }}">
                                    {{ $user->email_verified_at ? 'Verificado' : 'No verificado' }}
                                </span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="label">Registrado:</span>
                            <span class="value">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Última Actualización:</span>
                            <span class="value">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Loans -->
            @if($user->toolLoans->count() > 0)
                <div class="loans-card">
                    <div class="card-header">
                        <div class="card-icon loans">
                            <i class="fas fa-history"></i>
                        </div>
                        <h3>Préstamos Recientes</h3>
                    </div>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha Préstamo</th>
                                    <th>Estado</th>
                                    <th>Herramientas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->toolLoans->take(5) as $loan)
                                    <tr>
                                        <td class="font-semibold">#{{ $loan->id }}</td>
                                        <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="status-badge {{ $loan->status === 'active' ? 'pending' : 'active' }}">
                                                {{ $loan->status === 'active' ? 'Activo' : 'Devuelto' }}
                                            </span>
                                        </td>
                                        <td>{{ $loan->toolLoanItems->count() }} herramienta(s)</td>
                                        <td>
                                            <a href="{{ route('loans.show', $loan) }}" class="btn-action view">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($user->toolLoans->count() > 5)
                        <div class="card-footer">
                            <a href="{{ route('loans.index', ['user' => $user->id]) }}" class="btn btn-secondary">
                                Ver todos los préstamos
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="sidebar-column">
            <!-- Statistics Card -->
            <div class="stats-card">
                <div class="card-header">
                    <div class="card-icon stats">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Estadísticas</h3>
                </div>
                <div class="stats-content">
                    <div class="stat-item main">
                        <div class="stat-number">{{ $user->toolLoans->count() }}</div>
                        <div class="stat-label">Total de Préstamos</div>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number success">{{ $user->toolLoans->where('status', 'returned')->count() }}</div>
                            <div class="stat-label">Devueltos</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number warning">{{ $user->toolLoans->where('status', 'active')->count() }}</div>
                            <div class="stat-label">Activos</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Loans Warning -->
            @if($user->toolLoans->where('status', 'active')->count() > 0)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <h4>Préstamos Pendientes</h4>
                        <p>Este usuario tiene {{ $user->toolLoans->where('status', 'active')->count() }}
                        préstamo(s) activo(s) que debe(n) ser devuelto(s).</p>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="actions-card">
                <div class="card-header">
                    <div class="card-icon actions">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Acciones Rápidas</h3>
                </div>
                <div class="actions-content">
                    <a href="{{ route('users.edit', $user) }}" class="action-button edit">
                        <i class="fas fa-edit"></i>
                        <span>Editar Usuario</span>
                    </a>
                    <a href="{{ route('loans.create', ['user' => $user->id]) }}" class="action-button create">
                        <i class="fas fa-plus"></i>
                        <span>Crear Préstamo</span>
                    </a>
                    <a href="{{ route('loans.index', ['user' => $user->id]) }}" class="action-button view">
                        <i class="fas fa-history"></i>
                        <span>Ver Historial</span>
                    </a>
                </div>
            </div>
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

.alert {
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 24px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.alert-success {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.alert-warning {
    background: #fef3c7;
    color: #d97706;
    border: 1px solid #fbbf24;
}

.alert h4 {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 600;
}

.alert p {
    margin: 0;
    font-size: 12px;
    line-height: 1.4;
}

.content-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 24px;
}

.main-column {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.sidebar-column {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* Profile Card */
.profile-card {
    background: white;
    border-radius: 12px;
    padding: 32px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 24px;
}

.user-avatar {
    width: 64px;
    height: 64px;
    background: #3b82f6;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 24px;
}

.user-avatar.large {
    width: 80px;
    height: 80px;
    font-size: 28px;
}

.user-name {
    font-size: 28px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 4px 0;
}

.user-email {
    color: #6b7280;
    margin: 0 0 12px 0;
    font-size: 16px;
}

.user-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* Info Cards */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.info-card, .loans-card, .stats-card, .actions-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.card-header {
    padding: 24px 24px 16px 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    border-bottom: 1px solid #f3f4f6;
}

.card-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.card-icon.personal {
    background: #dbeafe;
    color: #1d4ed8;
}

.card-icon.system {
    background: #f3e8ff;
    color: #7c3aed;
}

.card-icon.loans {
    background: #d1fae5;
    color: #059669;
}

.card-icon.stats {
    background: #fed7aa;
    color: #ea580c;
}

.card-icon.actions {
    background: #fef2f2;
    color: #dc2626;
}

.card-header h3 {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.card-content {
    padding: 16px 24px 24px 24px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f9fafb;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item .label {
    font-size: 14px;
    color: #6b7280;
    font-weight: 500;
}

.info-item .value {
    font-size: 14px;
    color: #1a1a1a;
    font-weight: 500;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.active {
    background: #dcfce7;
    color: #166534;
}

.status-badge.inactive {
    background: #fef2f2;
    color: #dc2626;
}

.status-badge.pending {
    background: #fef3c7;
    color: #d97706;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
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

.text-muted {
    color: #6b7280;
}

/* Table */
.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: #f9fafb;
    padding: 12px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #e5e7eb;
}

.data-table td {
    padding: 12px 16px;
    border-bottom: 1px solid #f3f4f6;
    font-size: 14px;
}

.data-table tr:hover {
    background: #f9fafb;
}

.font-semibold {
    font-weight: 600;
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

.card-footer {
    padding: 16px 24px;
    border-top: 1px solid #f3f4f6;
    text-align: center;
}

/* Stats Card */
.stats-content {
    padding: 24px;
}

.stat-item.main {
    text-align: center;
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 1px solid #f3f4f6;
}

.stat-item.main .stat-number {
    font-size: 48px;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1;
    margin-bottom: 8px;
}

.stat-item.main .stat-label {
    font-size: 14px;
    color: #6b7280;
    font-weight: 500;
}

.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.stats-grid .stat-item {
    text-align: center;
}

.stats-grid .stat-number {
    font-size: 24px;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 4px;
}

.stats-grid .stat-number.success {
    color: #059669;
}

.stats-grid .stat-number.warning {
    color: #d97706;
}

.stats-grid .stat-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
}

/* Actions Card */
.actions-content {
    padding: 16px 24px 24px 24px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.action-button {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}

.action-button.edit {
    background: #fef3c7;
    color: #d97706;
}

.action-button.edit:hover {
    background: #fde68a;
    color: #d97706;
}

.action-button.create {
    background: #dbeafe;
    color: #1d4ed8;
}

.action-button.create:hover {
    background: #bfdbfe;
    color: #1d4ed8;
}

.action-button.view {
    background: #d1fae5;
    color: #059669;
}

.action-button.view:hover {
    background: #a7f3d0;
    color: #059669;
}

@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
