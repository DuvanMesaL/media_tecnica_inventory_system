@extends('layouts.app')

@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="page-title">
            <h1>Edit User</h1>
            <p class="page-subtitle">Tuesday, June 24, 2025</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">
                <i class="fas fa-eye"></i>
                View User
            </a>
        </div>
    </div>

    <div class="breadcrumb-nav">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.show', $user) }}">{{ $user->name }}</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="content-section">
        <div class="section-header">
            <h2>Editar Usuario: {{ $user->name }}</h2>
            <p class="section-subtitle">Modifica la información del usuario</p>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST" class="user-form">
            @csrf
            @method('PUT')

            <div class="form-section">
                <div class="section-title">
                    <div class="section-icon personal">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3>Información Personal</h3>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Nombre Completo <span class="required">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                               class="form-input @error('name') error @enderror" required>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Correo Electrónico <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                               class="form-input @error('email') error @enderror" required>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="employee_id">ID de Empleado</label>
                        <input type="text" id="employee_id" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}"
                               class="form-input @error('employee_id') error @enderror">
                        @error('employee_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="form-input @error('phone') error @enderror">
                        @error('phone')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">
                    <div class="section-icon security">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Cambiar Contraseña</h3>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Deja los campos de contraseña en blanco si no deseas cambiarla.
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="password">Nueva Contraseña</label>
                        <input type="password" id="password" name="password"
                               class="form-input @error('password') error @enderror">
                        <div class="form-help">Mínimo 8 caracteres</div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-input">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">
                    <div class="section-icon system">
                        <i class="fas fa-cog"></i>
                    </div>
                    <h3>Información del Sistema</h3>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="technical_program_id">Programa Técnico</label>
                        <select id="technical_program_id" name="technical_program_id"
                                class="form-select @error('technical_program_id') error @enderror">
                            <option value="">Seleccionar programa...</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}"
                                        {{ old('technical_program_id', $user->technical_program_id) == $program->id ? 'selected' : '' }}>
                                    {{ $program->name }} ({{ $program->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('technical_program_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="role">Rol <span class="required">*</span></label>
                        <select id="role" name="role"
                                class="form-select @error('role') error @enderror" required>
                            <option value="">Seleccionar rol...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}"
                                        {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">
                    <div class="section-icon options">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3>Estado del Usuario</h3>
                </div>

                <div class="form-options">
                    <div class="form-switch">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <label for="is_active">
                            <span class="switch-label">Usuario Activo</span>
                            <span class="switch-help">Los usuarios inactivos no podrán acceder al sistema</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Actualizar Usuario
                </button>
            </div>
        </form>
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

.alert-warning {
    background: #fef3c7;
    color: #d97706;
    border: 1px solid #fbbf24;
}

.user-form {
    max-width: 800px;
}

.form-section {
    margin-bottom: 40px;
    padding-bottom: 32px;
    border-bottom: 1px solid #f3f4f6;
}

.form-section:last-of-type {
    border-bottom: none;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}

.section-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.section-icon.personal {
    background: #dbeafe;
    color: #1d4ed8;
}

.section-icon.security {
    background: #fef2f2;
    color: #dc2626;
}

.section-icon.system {
    background: #f3e8ff;
    color: #7c3aed;
}

.section-icon.options {
    background: #d1fae5;
    color: #059669;
}

.section-title h3 {
    font-size: 18px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 8px;
}

.required {
    color: #dc2626;
}

.form-input, .form-select {
    padding: 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    background: white;
    transition: all 0.2s;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-input.error, .form-select.error {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.form-help {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}

.error-message {
    font-size: 12px;
    color: #dc2626;
    margin-top: 4px;
}

.form-options {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-switch {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.form-switch input[type="checkbox"] {
    width: 20px;
    height: 20px;
    margin: 0;
    accent-color: #3b82f6;
}

.form-switch label {
    display: flex;
    flex-direction: column;
    cursor: pointer;
}

.switch-label {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 2px;
}

.switch-help {
    font-size: 12px;
    color: #6b7280;
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid #f3f4f6;
}
</style>
@endsection
