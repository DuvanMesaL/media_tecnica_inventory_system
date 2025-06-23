¡Bienvenido {{ $user->first_name }}!
Tu cuenta ha sido creada exitosamente

Hola {{ $user->full_name }},

¡Felicidades! Tu cuenta en el {{ config('app.name') }} ha sido creada exitosamente y ya puedes comenzar a usar todas las funcionalidades del sistema.

Accede al sistema: {{ $dashboardUrl }}

Primeros pasos recomendados:
1. Explora el dashboard - Familiarízate con la interfaz
@if($user->hasRole('teacher'))
2. Revisa el inventario - Ve qué herramientas están disponibles
3. Solicita tu primer préstamo - Prueba el proceso completo
@elseif($user->hasRole('logistics'))
2. Revisa los préstamos pendientes - Ve si hay solicitudes por aprobar
3. Explora los reportes - Conoce las estadísticas del sistema
@else
2. Revisa la configuración - Asegúrate de que todo esté configurado correctamente
3. Explora los reportes - Conoce el estado general del sistema
@endif
4. Actualiza tu perfil - Agrega información adicional si es necesario

Funcionalidades principales:
@if($user->hasRole('teacher'))
- Solicitar préstamos de herramientas
- Ver el estado de tus préstamos
- Consultar inventario disponible
- Ver historial de préstamos
@elseif($user->hasRole('logistics'))
- Gestionar inventario de herramientas
- Aprobar y procesar préstamos
- Generar reportes de uso
- Procesar devoluciones
@else
- Administrar usuarios y permisos
- Gestión completa del inventario
- Acceso a todos los reportes
- Configuración del sistema
@endif

¿Necesitas ayuda?
Si tienes alguna pregunta o necesitas asistencia, no dudes en contactar al administrador del sistema.

¡Esperamos que tengas una excelente experiencia usando nuestro sistema!

Saludos cordiales,
El equipo de {{ config('app.name') }}

---
Este correo fue enviado desde {{ config('app.name') }}
Fecha de registro: {{ $user->created_at->format('d/m/Y H:i') }}
