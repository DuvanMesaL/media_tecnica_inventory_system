@if($type === 'approved')
✅ Préstamo Aprobado
@elseif($type === 'delivered')
📦 Préstamo Entregado
@elseif($type === 'returned')
🔄 Préstamo Devuelto
@elseif($type === 'overdue')
⚠️ Préstamo Vencido
@else
📋 Notificación de Préstamo
@endif

{{ $loan->loan_number }}

Hola {{ $loan->user->full_name }},

@if($type === 'approved')
¡Excelente noticia! Tu solicitud de préstamo ha sido APROBADA y las herramientas han sido reservadas para ti.
@elseif($type === 'delivered')
Tu préstamo ha sido ENTREGADO exitosamente. Ya puedes usar las herramientas solicitadas.
@elseif($type === 'returned')
Tu préstamo ha sido DEVUELTO y procesado correctamente. Gracias por usar nuestro sistema.
@elseif($type === 'overdue')
ATENCIÓN: Tu préstamo está VENCIDO. Por favor, devuelve las herramientas lo antes posible.
@endif

Detalles del préstamo:
- Número: {{ $loan->loan_number }}
- Fecha de préstamo: {{ $loan->loan_date->format('d/m/Y') }}
- Fecha de devolución esperada: {{ $loan->expected_return_date->format('d/m/Y') }}
- Programa: {{ $loan->technicalProgram->name }}
- Aula: {{ $loan->classroom->name }}
- Estado: {{ ucfirst($loan->status) }}

Herramientas:
@foreach($loan->toolLoanItems as $item)
- {{ $item->tool->name }} ({{ $item->quantity_requested }} {{ $item->quantity_requested > 1 ? 'unidades' : 'unidad' }})@if($item->tool->code) - Código: {{ $item->tool->code }}@endif
@endforeach

@if($type === 'approved')
Próximos pasos:
- Dirígete al almacén para recoger las herramientas
- Presenta tu identificación y menciona el número de préstamo
- Verifica que todas las herramientas estén en buen estado
@elseif($type === 'delivered')
Recordatorios importantes:
- Cuida las herramientas durante su uso
- Devuélvelas en la fecha acordada
- Reporta cualquier daño inmediatamente
@elseif($type === 'overdue')
Acción requerida:
- Devuelve las herramientas lo antes posible
- Contacta al personal de logística si hay algún problema
- Los préstamos vencidos pueden afectar futuras solicitudes
@endif

Ver detalles completos: {{ $loanUrl }}

Si tienes alguna pregunta o necesitas asistencia, no dudes en contactar al personal de logística.

---
Este correo fue enviado desde {{ config('app.name') }}
Fecha: {{ now()->format('d/m/Y H:i') }}
