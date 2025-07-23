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
                <p class="page-subtitle">Detalles del programa técnico</p>
            </div>
        </div>
        <div class="page-actions">
            @can('update', $program)
                <a href="{{ route('programs.edit', $program) }}" class="btn btn-secondary">
                    <i class="fas fa-edit"></i>
                    Editar
                </a>
            @endcan
            @can('delete', $program)
                <button class="btn btn-danger" onclick="confirmDelete('{{ route('programs.destroy', $program) }}')">
                    <i class="fas fa-trash"></i>
                    Eliminar
                </button>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="content-grid">
        <!-- Program Information Card -->
        <div class="info-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h2>Información del Programa</h2>
            </div>
            <div class="card-content">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nombre</label>
                        <span>{{ $program->name }}</span>
                    </div>
                    <div class="info-item">
                        <label>Código</label>
                        <span class="code-badge">{{ $program->code }}</span>
                    </div>
                    <div class="info-item">
                        <label>Estado</label>
                        <span class="status-badge {{ $program->is_active ? 'active' : 'inactive' }}">
                            {{ $program->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <label>Fecha de Creación</label>
                        <span>{{ $program->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($program->description)
                        <div class="info-item full-width">
                            <label>Descripción</label>
                            <span>{{ $program->description }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="stats-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h2>Estadísticas</h2>
            </div>
            <div class="card-content">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-icon users">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-value">{{ $program->users->count() }}</span>
                            <span class="stat-label">Usuarios Asignados</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon classrooms">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-value">{{ $program->classrooms->count() }}</span>
                            <span class="stat-label">Aulas Asociadas</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon loans">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-value">{{ $program->toolLoans->count() }}</span>
                            <span class="stat-label">Préstamos Realizados</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Associated Users -->
    @if($program->users->count() > 0)
        <div class="section-card">
            <div class="section-header">
                <h3>Usuarios Asignados ({{ $program->users->count() }})</h3>
            </div>
            <div class="users-list">
                @foreach($program->users->take(10) as $user)
                    <div class="user-item">
                        <div class="user-avatar">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <span class="user-name">{{ $user->name }}</span>
                            <span class="user-email">{{ $user->email }}</span>
                        </div>
                        <div class="user-role">
                            @if($user->roles->first())
                                <span class="role-badge">{{ $user->roles->first()->name }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
                @if($program->users->count() > 10)
                    <div class="show-more">
                        <span>Y {{ $program->users->count() - 10 }} usuarios más...</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Associated Classrooms -->
    @if($program->classrooms->count() > 0)
        <div class="section-card">
            <div class="section-header">
                <h3>Aulas Asociadas ({{ $program->classrooms->count() }})</h3>
            </div>
            <div class="classrooms-grid">
                @foreach($program->classrooms as $classroom)
                    <div class="classroom-item">
                        <div class="classroom-icon">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <div class="classroom-info">
                            <span class="classroom-name">{{ $classroom->name }}</span>
                            <span class="classroom-code">{{ $classroom->code }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmar Eliminación</h3>
        </div>
        <div class="modal-body">
            <p>¿Estás seguro de que deseas eliminar este programa técnico?</p>
            <p><strong>Nota:</strong> Solo se pueden eliminar programas que no tengan usuarios, aulas o préstamos asociados.</p>
            <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
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
    margin: 0 0 4px 0;
}

.page-subtitle {
    color: #6b7280;
    margin: 0;
    font-size: 14px;
}

.page-actions {
    display: flex;
    gap: 12px;
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
    font-size: 14px;
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

.alert-error {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin-bottom: 32px;
}

.info-card, .stats-card, .section-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.card-header, .section-header {
    padding: 24px 24px 16px 24px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 16px;
}

.card-icon {
    width: 48px;
    height: 48px;
    background: #dbeafe;
    color: #1d4ed8;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.card-header h2, .section-header h3 {
    font-size: 20px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.card-content {
    padding: 24px;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item label {
    font-size: 14px;
    font-weight: 500;
    color: #6b7280;
}

.info-item span {
    font-size: 16px;
    color: #1a1a1a;
}

.code-badge {
    background: #f3f4f6;
    color: #374151;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    width: fit-content;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    width: fit-content;
}

.status-badge.active {
    background: #dcfce7;
    color: #166534;
}

.status-badge.inactive {
    background: #fef2f2;
    color: #dc2626;
}

.stats-grid {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 16px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-icon.users {
    background: #dbeafe;
    color: #1d4ed8;
}

.stat-icon.classrooms {
    background: #d1fae5;
    color: #059669;
}

.stat-icon.loans {
    background: #fef3c7;
    color: #d97706;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}

.stat-label {
    font-size: 14px;
    color: #6b7280;
}

.section-card {
    grid-column: 1 / -1;
}

.users-list {
    padding: 24px;
}

.user-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px solid #f3f4f6;
}

.user-item:last-child {
    border-bottom: none;
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
}

.user-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.user-name {
    font-weight: 500;
    color: #1a1a1a;
}

.user-email {
    font-size: 14px;
    color: #6b7280;
}

.role-badge {
    background: #f3f4f6;
    color: #374151;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.show-more {
    padding: 16px 0;
    text-align: center;
    color: #6b7280;
    font-size: 14px;
}

.classrooms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    padding: 24px;
}

.classroom-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: #f9fafb;
    border-radius: 8px;
}

.classroom-icon {
    width: 32px;
    height: 32px;
    background: #d1fae5;
    color: #059669;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.classroom-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.classroom-name {
    font-weight: 500;
    color: #1a1a1a;
    font-size: 14px;
}

.classroom-code {
    font-size: 12px;
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
    margin: 0 0 12px 0;
    color: #6b7280;
    line-height: 1.5;
}

.text-danger {
    color: #dc2626;
}

.modal-footer {
    padding: 16px 24px 24px 24px;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

@media (max-width: 768px) {
    .content-grid {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .page-header {
        flex-direction: column;
        gap: 16px;
        align-items: stretch;
    }

    .classrooms-grid {
        grid-template-columns: 1fr;
    }
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
