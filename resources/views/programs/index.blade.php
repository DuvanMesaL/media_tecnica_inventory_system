@extends('layouts.app')

@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="page-title">
            <h1>Programs</h1>
            <p class="page-subtitle">Tuesday, June 24, 2025</p>
        </div>
        <div class="page-actions">
            @can('create', App\Models\TechnicalProgram::class)
                <button class="btn btn-primary" onclick="window.location.href='{{ route('programs.create') }}'">
                    <i class="fas fa-plus"></i>
                    Quick Add
                </button>
            @endcan
        </div>
    </div>

    <div class="breadcrumb-nav">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Programs</li>
            </ol>
        </nav>
    </div>

    <div class="content-section">
        <div class="section-header">
            <h2>Programas Técnicos</h2>
            <p class="section-subtitle">Gestiona los programas técnicos de la institución</p>
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

        @if($programs->count() > 0)
            <div class="programs-grid">
                @foreach($programs as $program)
                    <div class="program-card">
                        <div class="card-header">
                            <div class="program-info">
                                <h3 class="program-name">{{ $program->name }}</h3>
                                <div class="program-meta">
                                    <span class="program-code">{{ $program->code }}</span>
                                    <span class="status-badge {{ $program->is_active ? 'active' : 'inactive' }}">
                                        {{ $program->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-actions">
                                <div class="dropdown-container">
                                    <button class="btn-icon dropdown-toggle" onclick="toggleDropdown(this)">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('programs.show', $program) }}">
                                            <i class="fas fa-eye"></i> Ver detalles
                                        </a>
                                        @can('update', $program)
                                            <a class="dropdown-item" href="{{ route('programs.edit', $program) }}">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                        @endcan
                                        @can('delete', $program)
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#"
                                               onclick="confirmDelete('{{ route('programs.destroy', $program) }}')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($program->description)
                            <div class="card-body">
                                <p class="program-description">{{ Str::limit($program->description, 120) }}</p>
                            </div>
                        @endif

                        <div class="card-footer">
                            <div class="program-stats">
                                <div class="stat-item">
                                    <div class="stat-icon users">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="stat-info">
                                        <span class="stat-label">Usuarios Asignados</span>
                                        <span class="stat-value">{{ $program->users->count() }}</span>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon classrooms">
                                        <i class="fas fa-door-open"></i>
                                    </div>
                                    <div class="stat-info">
                                        <span class="stat-label">Aulas Asociadas</span>
                                        <span class="stat-value">{{ $program->classrooms->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="program-date">
                                <small>Creado: {{ $program->created_at->format('d/m/Y') }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrapper">
                {{ $programs->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>No se encontraron programas</h3>
                <p>Intenta ajustar los filtros de búsqueda</p>
                @can('create', App\Models\TechnicalProgram::class)
                    <a href="{{ route('programs.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Crear Primer Programa
                    </a>
                @endcan
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

.alert-error {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.programs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.program-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s;
}

.program-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.card-header {
    padding: 24px 24px 16px 24px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.program-name {
    font-size: 18px;
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
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge {
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

.dropdown-container {
    position: relative;
}

.btn-icon {
    width: 32px;
    height: 32px;
    border: none;
    background: none;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-icon:hover {
    background: #f3f4f6;
    color: #374151;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 8px 0;
    min-width: 160px;
    z-index: 1000;
    display: none;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-item {
    padding: 8px 16px;
    color: #374151;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.2s;
}

.dropdown-item:hover {
    background: #f3f4f6;
    color: #374151;
}

.dropdown-item.text-danger {
    color: #dc2626;
}

.dropdown-divider {
    margin: 8px 0;
    border-top: 1px solid #e5e7eb;
}

.card-body {
    padding: 0 24px 16px 24px;
}

.program-description {
    color: #6b7280;
    margin: 0;
    line-height: 1.5;
}

.card-footer {
    padding: 16px 24px 24px 24px;
    border-top: 1px solid #f3f4f6;
}

.program-stats {
    display: flex;
    gap: 24px;
    margin-bottom: 16px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.stat-icon.users {
    background: #dbeafe;
    color: #1d4ed8;
}

.stat-icon.classrooms {
    background: #d1fae5;
    color: #059669;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 2px;
}

.stat-value {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
}

.program-date {
    color: #9ca3af;
    font-size: 12px;
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
function toggleDropdown(button) {
    // Close all other dropdowns
    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
        if (menu !== button.nextElementSibling) {
            menu.classList.remove('show');
        }
    });

    // Toggle current dropdown
    const menu = button.nextElementSibling;
    menu.classList.toggle('show');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown-container')) {
        document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});

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
