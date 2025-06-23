<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Registro - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-blue-100">
                <i class="fas fa-user-plus text-2xl text-blue-600"></i>
            </div>
            <h2 class="mt-6 text-3xl font-bold text-gray-900">
                ¡Bienvenido!
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Completa tu registro para acceder al sistema
            </p>
        </div>

        <!-- Invitation Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-envelope text-blue-600 mr-3"></i>
                <div>
                    <p class="text-sm font-medium text-blue-900">{{ $invitation->email }}</p>
                    <p class="text-xs text-blue-700">
                        Rol: {{ ucfirst($invitation->role) }}
                        @if($invitation->technicalProgram)
                            - {{ $invitation->technicalProgram->name }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <form method="POST" action="{{ route('invitation.complete', $invitation->token) }}" class="space-y-6">
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

            <!-- Personal Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Información Personal</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700">Segundo Nombre</label>
                        <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Apellido Paterno *</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="second_last_name" class="block text-sm font-medium text-gray-700">Apellido Materno</label>
                        <input type="text" name="second_last_name" id="second_last_name" value="{{ old('second_last_name') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label for="identification_number" class="block text-sm font-medium text-gray-700">Número de Identificación *</label>
                    <input type="text" name="identification_number" id="identification_number" value="{{ old('identification_number') }}" required
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Cédula, RFC, etc.">
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="+52 555 123 4567">
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Contraseña</h3>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Nueva Contraseña *</label>
                    <input type="password" name="password" id="password" required
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Mínimo 8 caracteres</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Terms -->
            <div class="flex items-center">
                <input type="checkbox" name="terms" id="terms" required
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="terms" class="ml-2 block text-sm text-gray-900">
                    Acepto los <a href="#" class="text-blue-600 hover:text-blue-500">términos y condiciones</a>
                </label>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-check text-blue-500 group-hover:text-blue-400"></i>
                    </span>
                    Completar Registro
                </button>
            </div>

            <!-- Expiration Warning -->
            <div class="text-center">
                <p class="text-xs text-gray-500">
                    <i class="fas fa-clock mr-1"></i>
                    Esta invitación expira el {{ $invitation->expires_at->format('d/m/Y \a \l\a\s H:i') }}
                </p>
            </div>
        </form>
    </div>
</body>
</html>
