¡Has sido invitado!
{{ config('app.name') }}

Hola,

{{ $invitedBy->name }} te ha invitado a unirte al {{ config('app.name') }}.

Detalles de tu invitación:
- Correo: {{ $invitation->email }}
- Rol: {{ $roleName }}
@if($invitation->technicalProgram)
- Programa: {{ $invitation->technicalProgram->name }}
@endif
- Invitado por: {{ $invitedBy->name }}

Para completar tu registro y acceder al sistema, visita el siguiente enlace:
{{ $acceptUrl }}

IMPORTANTE: Esta invitación expira el {{ $expiresAt->format('d/m/Y \a \l\a\s H:i') }}.

Si tienes alguna pregunta, no dudes en contactar a {{ $invitedBy->name }} o al administrador del sistema.

¡Esperamos verte pronto en el sistema!

---
Este correo fue enviado desde {{ config('app.name') }}
Si no esperabas esta invitación, puedes ignorar este correo.
