<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Bienvenido al Sistema!</title>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        .tips-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
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
        <h1>🎉 ¡Bienvenido {{ $user->first_name }}!</h1>
        <p>Tu cuenta ha sido creada exitosamente</p>
    </div>

    <div class="content">
        <h2>¡Hola {{ $user->full_name }}!</h2>

        <p>¡Felicidades! Tu cuenta en el <strong>{{ config('app.name') }}</strong> ha sido creada exitosamente y ya puedes comenzar a usar todas las funcionalidades del sistema.</p>

        <div style="text-align: center;">
            <a href="{{ $dashboardUrl }}" class="button">
                🚀 Ir al Dashboard
            </a>
        </div>

        <div class="tips-box">
            <h3>💡 Primeros pasos recomendados:</h3>
            <ol>
                <li><strong>Explora el dashboard</strong> - Familiarízate con la interfaz</li>
                @if($user->hasRole('teacher'))
                <li><strong>Revisa el inventario</strong> - Ve qué herramientas están disponibles</li>
                <li><strong>Solicita tu primer préstamo</strong> - Prueba el proceso completo</li>
                @elseif($user->hasRole('logistics'))
                <li><strong>Revisa los préstamos pendientes</strong> - Ve si hay solicitudes por aprobar</li>
                <li><strong>Explora los reportes</strong> - Conoce las estadísticas del sistema</li>
                @else
                <li><strong>Revisa la configuración</strong> - Asegúrate de que todo esté configurado correctamente</li>
                <li><strong>Explora los reportes</strong> - Conoce el estado general del sistema</li>
                @endif
                <li><strong>Actualiza tu perfil</strong> - Agrega información adicional si es necesario</li>
            </ol>
        </div>

        <h3>🔧 Funcionalidades principales:</h3>
        <ul>
            @if($user->hasRole('teacher'))
            <li>📝 Solicitar préstamos de herramientas</li>
            <li>📊 Ver el estado de tus préstamos</li>
            <li>🔍 Consultar inventario disponible</li>
            <li>📈 Ver historial de préstamos</li>
            @elseif($user->hasRole('logistics'))
            <li>📦 Gestionar inventario de herramientas</li>
            <li>✅ Aprobar y procesar préstamos</li>
            <li>📊 Generar reportes de uso</li>
            <li>🔄 Procesar devoluciones</li>
            @else
            <li>👥 Administrar usuarios y permisos</li>
            <li>📦 Gestión completa del inventario</li>
            <li>📊 Acceso a todos los reportes</li>
            <li>⚙️ Configuración del sistema</li>
            @endif
        </ul>

        <p><strong>🆘 ¿Necesitas ayuda?</strong></p>
        <p>Si tienes alguna pregunta o necesitas asistencia, no dudes en contactar al administrador del sistema o consultar la documentación disponible en el sistema.</p>

        <p>¡Esperamos que tengas una excelente experiencia usando nuestro sistema!</p>

        <p>Saludos cordiales,<br>
        <strong>El equipo de {{ config('app.name') }}</strong></p>
    </div>

    <div class="footer">
        <p>Este correo fue enviado desde {{ config('app.name') }}</p>
        <p>Fecha de registro: {{ $user->created_at->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
