<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de Préstamo</title>
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
            @if($type === 'approved')
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            @elseif($type === 'delivered')
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
            @elseif($type === 'overdue')
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            @else
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            @endif
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
        .tools-list {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
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
        <h1>
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
        </h1>
        <p>{{ $loan->loan_number }}</p>
    </div>

    <div class="content">
        <h2>Hola {{ $loan->user->full_name }},</h2>

        @if($type === 'approved')
            <p>¡Excelente noticia! Tu solicitud de préstamo ha sido <strong>aprobada</strong> y las herramientas han sido reservadas para ti.</p>
        @elseif($type === 'delivered')
            <p>Tu préstamo ha sido <strong>entregado</strong> exitosamente. Ya puedes usar las herramientas solicitadas.</p>
        @elseif($type === 'returned')
            <p>Tu préstamo ha sido <strong>devuelto</strong> y procesado correctamente. Gracias por usar nuestro sistema.</p>
        @elseif($type === 'overdue')
            <p><strong>Atención:</strong> Tu préstamo está <strong>vencido</strong>. Por favor, devuelve las herramientas lo antes posible.</p>
        @endif

        <div class="info-box">
            <h3>📋 Detalles del préstamo:</h3>
            <ul>
                <li><strong>Número:</strong> {{ $loan->loan_number }}</li>
                <li><strong>Fecha de préstamo:</strong> {{ $loan->loan_date->format('d/m/Y') }}</li>
                <li><strong>Fecha de devolución esperada:</strong> {{ $loan->expected_return_date->format('d/m/Y') }}</li>
                <li><strong>Programa:</strong> {{ $loan->technicalProgram->name }}</li>
                <li><strong>Aula:</strong> {{ $loan->classroom->name }}</li>
                <li><strong>Estado:</strong>
                    @if($loan->status === 'approved')
                        <span style="color: #28a745;">Aprobado</span>
                    @elseif($loan->status === 'delivered')
                        <span style="color: #007bff;">Entregado</span>
                    @elseif($loan->status === 'returned')
                        <span style="color: #6c757d;">Devuelto</span>
                    @else
                        {{ ucfirst($loan->status) }}
                    @endif
                </li>
            </ul>
        </div>

        <div class="tools-list">
            <h3>🔧 Herramientas:</h3>
            <ul>
                @foreach($loan->toolLoanItems as $item)
                    <li>
                        <strong>{{ $item->tool->name }}</strong>
                        ({{ $item->quantity_requested }} {{ $item->quantity_requested > 1 ? 'unidades' : 'unidad' }})
                        @if($item->tool->code)
                            - Código: {{ $item->tool->code }}
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        @if($type === 'approved')
            <p><strong>Próximos pasos:</strong></p>
            <ul>
                <li>Dirígete al almacén para recoger las herramientas</li>
                <li>Presenta tu identificación y menciona el número de préstamo</li>
                <li>Verifica que todas las herramientas estén en buen estado</li>
            </ul>
        @elseif($type === 'delivered')
            <p><strong>Recordatorios importantes:</strong></p>
            <ul>
                <li>Cuida las herramientas durante su uso</li>
                <li>Devuélvelas en la fecha acordada</li>
                <li>Reporta cualquier daño inmediatamente</li>
            </ul>
        @elseif($type === 'overdue')
            <p><strong>Acción requerida:</strong></p>
            <ul>
                <li>Devuelve las herramientas lo antes posible</li>
                <li>Contacta al personal de logística si hay algún problema</li>
                <li>Los préstamos vencidos pueden afectar futuras solicitudes</li>
            </ul>
        @endif

        <div style="text-align: center;">
            <a href="{{ $loanUrl }}" class="button">
                Ver Detalles del Préstamo
            </a>
        </div>

        <p>Si tienes alguna pregunta o necesitas asistencia, no dudes en contactar al personal de logística.</p>
    </div>

    <div class="footer">
        <p>Este correo fue enviado desde {{ config('app.name') }}</p>
        <p>Fecha: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
