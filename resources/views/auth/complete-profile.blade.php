@extends('layouts.app')

@section('title', 'Completar Perfil')
@section('header', 'Completar Perfil')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900">Completa tu perfil</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Para continuar usando el sistema, necesitas completar tu información personal.
                </p>
            </div>

            <form method="POST" action="{{ route('profile.complete') }}" class="space-y-6">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Personal Information -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-900">Información Personal</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">Nombre *</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700">Segundo Nombre</label>
                            <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $user->middle_name) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Apellido Paterno *</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="second_last_name" class="block text-sm font-medium text-gray-700">Apellido Materno</label>
                            <input type="text" name="second_last_name" id="second_last_name" value="{{ old('second_last_name', $user->second_last_name) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="identification_number" class="block text-sm font-medium text-gray-700">Número de Identificación *</label>
                            <input type="text" name="identification_number" id="identification_number" value="{{ old('identification_number', $user->identification_number) }}" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    @if($user->hasRole('teacher'))
                    <div>
                        <label for="technical_program_id" class="block text-sm font-medium text-gray-700">Programa Técnico *</label>
                        <select name="technical_program_id" id="technical_program_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccionar Programa</option>
                            @foreach($technicalPrograms as $program)
                                <option value="{{ $program->id }}" {{ old('technical_program_id', $user->technical_program_id) == $program->id ? 'selected' : '' }}>
                                    {{ $program->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>

                <!-- Password Change (if needed) -->
                @if(!$user->password_changed)
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-900">Cambiar Contraseña</h4>
                    <p class="text-sm text-gray-600">Debes establecer una nueva contraseña para tu cuenta.</p>

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Contraseña Actual *</label>
                        <input type="password" name="current_password" id="current_password" required
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Nueva Contraseña *</label>
                            <input type="password" name="password" id="password" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex justify-end space-x-3">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-check mr-2"></i>Completar Perfil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
