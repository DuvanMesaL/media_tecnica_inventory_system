@extends('layouts.app')

@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="page-title">
            <div class="back-button">
                <a href="{{ route('programs.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="title-content">
                <h1>{{ $program->name }}</h1>
                <div class="program-meta">
                    <span class="program-code">{{ $program->code }}</span>
                    <span class="status-badge {{ $program->is_active ? 'active' : 'inactive' }}">
                        {{ $program->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="page-actions">
            @can('update', $program)
                <a href="{{ route('programs.edit', $program) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    Editar
                </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="content-grid">
        <div class="main-column">
            <!-- Program Information -->
            <div class="info-section">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h2>Información del Programa</h2>
                </div>

                @if($program->description)
                    <div class="description-block">
                        <h3>Descripción:</h3>
                        <p>{{ $program->description }}</p>
                    </div>
                @endif

                <div class="details-grid">
                    <div class="detail-group">
                        <h3>Detalles Generales</h3>
                        <div class="detail-list">
                            <div class="detail-item">
                                <span class="detail-label">ID:</span>
                                <span class="detail-value">{{ $program->id }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Código:</span>
                                <span class="detail-value">{{ $program->code }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Estado:</span>
                                <span class="status-badge {{ $program->is_active ? 'active' : 'inactive' }}">
                                    {{ $program->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-group">
                        <h3>Fechas</h3>
                        <div class="detail-list">
                            <div class="detail-item">
                                <span class="detail-label">Creado:</span>
                                <span class="detail-value">{{ $program->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Actualizado:</span>
                                <span class="detail-value">{{ $program->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Section -->
            @if($program->users->count() > 0)
                <div class="users-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h2>Usuarios Asignados ({{ $program->users->count() }})</h2>
                    </div>

                    <div class="users-table">
                        <div class="table-header">
                            <div class="header-cell">Usuario</div>
                            <div class="header-cell">Rol</div>
                            <div class="header-cell">Estado</div>
                        </div>
                        @foreach($program->users as $user)
                            <div class="table-row">
                                <div class="user-info">
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-email">{{ $user->email }}</div>
                                </div>
                                <div class="user-roles">
                                    @foreach($user->roles as $role)
                                        <span class="role-badge">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                </div>
                                <div class="user-status">
                                    <span class="status-badge {{ $user->is_active ? 'active' : 'inactive' }}">
                                        {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Classrooms Section -->
            @if($program->classrooms->count() > 0)
                <div class="classrooms-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <h2>Aulas Asociadas ({{ $program->classrooms->count() }})</h2>
                    </div>

                    <div class="classrooms-grid">
                        @foreach($program->classrooms as $classroom)
                            <div class="classroom-card">
                                <div class="classroom-name">{{ $classroom->name }}</div>
                                <div class="classroom-code">{{ $classroom->code }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="sidebar">
            <!-- Statistics -->
            <div class="stats-section">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h2>Estadísticas</h2>
                </div>

                <div class="stats-grid">
                    <div class="stat-card users">
                        <div class="stat-number">{{ $program->users->count() }}</div>
                        <div class="stat-label">Usuarios Asignados</div>
                    </div>
                    <div class="stat-card classrooms">
                        <div class="stat-number">{{ $program->classrooms->count() }}</div>
                        <div class="stat-label">Aulas Asociadas</div>
                    </div>
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
    margin-bottom: 32px;
}

.page-title {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.btn-back {
    width: 40px;
    height: 40px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-back:hover {
    background: #f3f4f6;
    color: #374151;
}

.title-content h1 {
    font-size: 32px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 8px 0;
}

.program-meta {
    display: flex;
    gap: 12px;
    align-items: center;
}

.program-code {
    background: #f3f4f6;
    color: #374151;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
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

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
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

.content-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 32px;
}

.info-section, .users-section, .classrooms-section, .stats-section {
    background: white;
    border-radius: 12px;
    padding: 32px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 24px;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}

.section-icon {
    width: 40px;
    height: 40px;
    background: #dbeafe;
    color: #1d4ed8;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.section-header h2 {
    font-size: 20px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.description-block {
    margin-bottom: 32px;
}

.description-block h3 {
    font-size: 16px;
    font-weight: 600;
    color: #374151;
    margin: 0 0 8px 0;
}

.description-block p {
    color: #6b7280;
    line-height: 1.6;
    margin: 0;
}

.details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
}

.detail-group h3 {
    font-size: 16px;
    font-weight: 600;
    color: #374151;
    margin: 0 0 16px 0;
}

.detail-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.detail-label {
    color: #6b7280;
    font-size: 14px;
}

.detail-value {
    color: #1a1a1a;
    font-weight: 500;
    font-size: 14px;
}

.users-table {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}

.table-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    background: #f9fafb;
    padding: 16px 24px;
    border-bottom: 1px solid #e5e7eb;
}

.header-cell {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.table-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    padding: 16px 24px;
    border-bottom: 1px solid #f3f4f6;
    align-items: center;
}

.table-row:last-child {
    border-bottom: none;
}

.user-name {
    font-weight: 500;
    color: #1a1a1a;
    margin-bottom: 4px;
}

.user-email {
    font-size: 14px;
    color: #6b7280;
}

.role-badge {
    background: #dbeafe;
    color: #1d4ed8;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.classrooms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
}

.classroom-card {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    text-align: center;
}

.classroom-name {
    font-weight: 500;
    color: #1a1a1a;
    margin-bottom: 4px;
}

.classroom-code {
    font-size: 14px;
    color: #6b7280;
}

.stats-grid {
    display: grid;
    gap: 16px;
}

.stat-card {
    padding: 24px;
    border-radius: 8px;
    text-align: center;
}

.stat-card.users {
    background: #dbeafe;
}

.stat-card.classrooms {
    background: #d1fae5;
}

.stat-number {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 8px;
}

.stat-card.users .stat-number {
    color: #1d4ed8;
}

.stat-card.classrooms .stat-number {
    color: #059669;
}

.stat-label {
    font-size: 14px;
    color: #6b7280;
    font-weight: 500;
}

@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
    }

    .details-grid {
        grid-template-columns: 1fr;
    }

    .table-header, .table-row {
        grid-template-columns: 1fr;
        gap: 8px;
    }

    .classrooms-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
