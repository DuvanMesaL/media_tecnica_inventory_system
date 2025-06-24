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
                <h1>Crear Programa Técnico</h1>
                <p class="page-subtitle">Agrega un nuevo programa técnico al sistema</p>
            </div>
        </div>
    </div>

    <div class="form-container">
        <div class="form-card">
            <div class="form-header">
                <div class="form-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h2>Información del Programa</h2>
            </div>

            <form action="{{ route('programs.store') }}" method="POST" class="form-content">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label required">Nombre del Programa</label>
                        <input type="text"
                               id="name"
                               name="name"
                               class="form-input @error('name') error @enderror"
                               value="{{ old('name') }}"
                               placeholder="Ej: Técnico en Sistemas Computacionales"
                               required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="code" class="form-label required">Código del Programa</label>
                        <input type="text"
                               id="code"
                               name="code"
                               class="form-input @error('code') error @enderror"
                               value="{{ old('code') }}"
                               placeholder="Ej: TSC-001"
                               required>
                        <div class="form-help">Código único para identificar el programa técnico</div>
                        @error('code')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea id="description"
                              name="description"
                              class="form-textarea @error('description') error @enderror"
                              rows="4"
                              placeholder="Descripción detallada del programa técnico...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-switch">
                        <input type="checkbox"
                               id="is_active"
                               name="is_active"
                               value="1"
                               class="switch-input"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="switch-label">
                            <span class="switch-slider"></span>
                            <span class="switch-text">Programa Activo</span>
                        </label>
                        <div class="form-help">Los programas inactivos no aparecerán en las opciones de selección</div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('programs.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Crear Programa
                    </button>
                </div>
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

.form-container {
    max-width: 800px;
    margin: 0 auto;
}

.form-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.form-header {
    padding: 32px 32px 24px 32px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 16px;
}

.form-icon {
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

.form-header h2 {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.form-content {
    padding: 32px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 24px;
}

.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 8px;
}

.form-label.required::after {
    content: " *";
    color: #dc2626;
}

.form-input, .form-textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    color: #1a1a1a;
    background: white;
    transition: all 0.2s;
}

.form-input:focus, .form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-input.error, .form-textarea.error {
    border-color: #dc2626;
}

.form-textarea {
    resize: vertical;
    min-height: 100px;
}

.form-help {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}

.form-error {
    font-size: 12px;
    color: #dc2626;
    margin-top: 4px;
}

.form-switch {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.switch-input {
    display: none;
}

.switch-label {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
}

.switch-slider {
    width: 44px;
    height: 24px;
    background: #d1d5db;
    border-radius: 12px;
    position: relative;
    transition: all 0.2s;
}

.switch-slider::before {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    top: 2px;
    left: 2px;
    transition: all 0.2s;
}

.switch-input:checked + .switch-label .switch-slider {
    background: #3b82f6;
}

.switch-input:checked + .switch-label .switch-slider::before {
    transform: translateX(20px);
}

.switch-text {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 16px;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid #f3f4f6;
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

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-secondary {
    background: white;
    color: #6b7280;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background: #f9fafb;
    color: #374151;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-content {
        padding: 24px;
    }

    .form-header {
        padding: 24px;
    }

    .form-actions {
        flex-direction: column-reverse;
    }
}
</style>
@endsection
