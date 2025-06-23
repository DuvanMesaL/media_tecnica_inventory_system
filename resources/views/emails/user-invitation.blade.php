<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitación al Sistema de Inventario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 ¡Has sido invitado!</h1>
        <p>{{ config('app.name') }}</p>
    </div>

    <div class="content">
        <h2>Hola,</h2>

        <p>{{ $invitedBy->name }} te ha invitado a unirte al <strong>{{ config('app.name') }}</strong>.</p>

        <div class="info-box">
            <h3>📋 Detalles de tu invitación:</h3>
            <ul>
                <li><strong>Correo:</strong> {{ $invitation->email }}</li>
                <li><strong>Rol:</strong> {{ $roleName }}</li>
                @if($invitation->technicalProgram)
                <li><strong>Programa:</strong> {{ $invitation->technicalProgram->name }}</li>
                @endif
                <li><strong>Invitado por:</strong> {{ $invitedBy->name }}</li>
            </ul>
        </div>

        <p>Para completar tu registro y acceder al sistema, haz clic en el siguiente botón:</p>

        <div style="text-align: center;">
            <a href="{{ $acceptUrl }}" class="button">
                ✅ Completar Registro
            </a>
        </div>

        <p><strong>⏰ Importante:</strong> Esta invitación expira el <strong>{{ $expiresAt->format('d/m/Y \a \l\a\s H:i') }}</strong>.</p>

        <h3>🚀 ¿Qué puedes hacer en el sistema?</h3>
        <ul>
            @if($invitation->role === 'teacher')
            <li>Solicitar préstamos de herramientas para tus clases</li>
            <li>Ver el estado de tus préstamos</li>
            <li>Consultar el inventario disponible</li>
            @elseif($invitation->role === 'logistics')
            <li>Gestionar el inventario de herramientas</li>
            <li>Aprobar y procesar préstamos</li>
            <li>Generar reportes de uso</li>
            @else
            <li>Administrar usuarios y permisos</li>
            <li>Gestionar inventario completo</li>
            <li>Acceder a todos los reportes</li>
            @endif
        </ul>

        <p>Si tienes alguna pregunta, no dudes en contactar a {{ $invitedBy->name }} o al administrador del sistema.</p>

        <p>¡Esperamos verte pronto en el sistema!</p>
    </div>

    <div class="footer">
        <p>Este correo fue enviado desde {{ config('app.name') }}</p>
        <p>Si no esperabas esta invitación, puedes ignorar este correo.</p>
    </div>
</body>
</html>
