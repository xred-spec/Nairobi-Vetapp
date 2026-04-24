<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte General Veterinaria</title>
    <style>
        /* ===================== GLOBAL STYLES ===================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            font-size: 11px;
            background: #f5f7fb;
            padding: 30px 20px;
            color: #1e293b;
        }

        /* main container */
        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            padding: 30px 28px;
        }

        h1 {
            font-size: 26px;
            font-weight: 700;
            text-align: center;
            color: #0f3b2c;
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }

        .subhead {
            text-align: center;
            font-size: 13px;
            color: #5b6e8c;
            border-bottom: 2px solid #e6edf4;
            padding-bottom: 20px;
            margin-bottom: 28px;
        }

        h2 {
            font-size: 18px;
            font-weight: 600;
            color: #1e4620;
            margin: 0 0 16px 0;
            padding-left: 10px;
            border-left: 5px solid #2c7a4d;
        }

        h3 {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 12px 0;
            color: #2c3e66;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        h4 {
            font-size: 14px;
            font-weight: 500;
            margin: 20px 0 12px 0;
            color: #2d4a6e;
            background: #f1f5f9;
            padding: 6px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        .section {
            margin-bottom: 42px;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        /* ========== CARDS & METRICS ========== */
        .metrics-grid {
            background: #fefdf8;
            border-radius: 28px;
            padding: 10px 0 20px 0;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .circle-table {
            width: 100%;
            text-align: center;
            border-collapse: separate;
            border-spacing: 15px;
        }

        .circle-box {
            padding: 8px;
            vertical-align: top;
        }

        .circle {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            margin: 0 auto;
            position: relative;
            background: #f8fafc;
            transition: all 0.1s ease;
        }

        /* colored rings */
        .circle.green {
            border: 9px solid #2b9348;
            box-shadow: 0 4px 12px rgba(43, 147, 72, 0.2);
        }
        .circle.red {
            border: 9px solid #e63946;
            box-shadow: 0 4px 12px rgba(230, 57, 70, 0.2);
        }
        .circle.orange {
            border: 9px solid #f4a261;
            box-shadow: 0 4px 12px rgba(244, 162, 97, 0.2);
        }

        .inner {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: white;
            position: absolute;
            top: 10px;
            left: 10px;
            text-align: center;
            line-height: 72px;
            font-weight: 800;
            font-size: 18px;
            color: #1e293b;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
        }

        .circle-label {
            margin-top: 14px;
            font-size: 12px;
            font-weight: 500;
            color: #2c3e50;
        }

        .circle-label strong {
            font-size: 16px;
            color: #0f172a;
            display: inline-block;
            margin-top: 4px;
        }

        /* mini stats table */
        .mini-stats {
            width: 100%;
            margin-top: 20px;
            border-collapse: separate;
            border-spacing: 0 6px;
            background: #ffffff;
            border-radius: 20px;
        }

        .mini-stats td {
            padding: 12px 10px;
            background: #f8fafc;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .mini-stats td strong {
            font-size: 15px;
            color: #0f3b2c;
            margin-left: 6px;
        }

        /* ========== TABLES ========== */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            margin-top: 8px;
        }

        .data-table th {
            background: #1e3a2f;
            color: white;
            padding: 12px 8px;
            font-weight: 600;
            font-size: 11px;
            text-align: left;
        }

        .data-table td {
            padding: 9px 8px;
            border-bottom: 1px solid #e9edf2;
            background-color: #ffffff;
        }

        .data-table tr:hover td {
            background-color: #fef9e6;
        }

        .finance-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
            font-size: 11px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .finance-table th {
            text-align: left;
            padding: 10px 12px;
            background: #eef2f5;
            color: #1e3a5f;
            font-weight: 600;
            border-bottom: 1px solid #dce3ec;
        }

        .finance-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #f0f2f5;
            background: white;
        }

        .finance-subtitle {
            font-size: 13px;
            font-weight: 600;
            margin: 28px 0 12px 0;
            color: #2c5f2d;
            background: none;
            padding: 0;
            border-left: 4px solid #2c7a4d;
            padding-left: 10px;
        }

        /* empty state message */
        .empty-message {
            background: #fef2e8;
            border-left: 4px solid #e67e22;
            padding: 16px 18px;
            border-radius: 14px;
            color: #a45313;
            font-weight: 500;
            font-size: 12px;
            margin: 12px 0;
            text-align: center;
        }

        .badge {
            background: #eef2ff;
            color: #1e3a8a;
            border-radius: 30px;
            padding: 2px 12px;
            font-size: 10px;
            font-weight: 500;
        }

        footer {
            text-align: center;
            font-size: 9px;
            color: #8b9eb0;
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            .report-container {
                box-shadow: none;
                padding: 15px;
            }
            .data-table th {
                background: #1e3a2f !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
<div class="report-container">
    <h1> Reporte General Veterinaria</h1>
    <div class="subhead">Resumen ejecutivo · Indicadores clave · Desempeño financiero</div>

    <div class="section">
        <h2> Resumen General de Citas</h2>
        @php
            $total = max(1, $data1['general']['total_citas'] ?? 0);
            $realizadas = $data1['general']['citas_realizadas'] ?? 0;
            $canceladas = $data1['general']['citas_canceladas'] ?? 0;
            $proceso = $data1['general']['citas_proceso'] ?? 0;
        @endphp

        <div class="metrics-grid">
            <table class="circle-table">
                <tr>
                    <td class="circle-box">
                        <div class="circle green">
                            <div class="inner">{{ round(($realizadas / $total) * 100) }}%</div>
                        </div>
                        <div class="circle-label">
                             Realizadas <br>
                            <strong>{{ number_format($realizadas) }}</strong>
                        </div>
                    </td>
                    <td class="circle-box">
                        <div class="circle red">
                            <div class="inner">{{ round(($canceladas / $total) * 100) }}%</div>
                        </div>
                        <div class="circle-label">
                             Canceladas <br>
                            <strong>{{ number_format($canceladas) }}</strong>
                        </div>
                    </td>
                    <td class="circle-box">
                        <div class="circle orange">
                            <div class="inner">{{ round(($proceso / $total) * 100) }}%</div>
                        </div>
                        <div class="circle-label">
                             En Proceso <br>
                            <strong>{{ number_format($proceso) }}</strong>
                        </div>
                    </td>
                </tr>
            </table>


            <table class="mini-stats">
                <tr>
                    <td> Total Citas: <strong>{{ $data1['general']['total_citas'] ?? 0 }}</strong></td>
                    <td> Mascotas Atendidas: <strong>{{ $data1['general']['mascotas_atendidas'] ?? 0 }}</strong></td>
                    <td> Trabajadores Activos: <strong>{{ $data1['general']['trabajadores_activos'] ?? 0 }}</strong></td>
                </tr>
                <tr>
                    <td> Agendadas: <strong>{{ $data1['general']['citas_agendadas'] ?? 0 }}</strong></td>
                    <td> Nuevas Mascotas: <strong>{{ $data1['general']['nuevas_mascotas'] ?? 0 }}</strong></td>
                    <td> Clientes Nuevos: <strong>{{ $data1['general']['nuevos_clientes'] ?? 0 }}</strong></td>
                </tr>
            </table>
        </div>
    </div>


    <div class="section">
        <h2> Mascotas Registradas</h2>
        @if(isset($mascotas) && count($mascotas) > 0)
            <table class="finance-table">
                <thead>
                <tr>
                    <th> Mascota</th>
                    <th> Dueño</th>
                    <th> Raza</th>
                    <th> Animal</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($mascotas as $mascota)
                    <tr>
                        <td>{{ $mascota->Mascota ?? $mascota->nombre ?? '—' }}</td>
                        <td>{{ $mascota->Dueño ?? $mascota->propietario ?? '—' }}</td>
                        <td>{{ $mascota->Raza ?? $mascota->raza ?? '—' }}</td>
                        <td>{{ $mascota->Animal ?? $mascota->tipo ?? '—' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-message">
                No hay mascotas registradas.
            </div>
        @endif
    </div>


    <div class="section">
        <h2> Rendimiento por Trabajador</h2>
        @if(isset($data1['trabajadores']) && count($data1['trabajadores']) > 0)
            <table class="data-table">
                <thead>
                <tr>
                    <th> Nombre</th>
                    <th> Total Citas</th>
                    <th> Completadas</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($data1['trabajadores'] as $trabajador)
                    <tr>
                        <td>{{ $trabajador['nombre_trabajador'] ?? '—' }}</td>
                        <td>{{ $trabajador['cantidad_citas'] ?? 0 }}</td>
                        <td>{{ $trabajador['completadas'] ?? 0 }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-message">
                 No hay información de trabajadores disponible en el período seleccionado.
            </div>
        @endif
    </div>


    <div class="section">
        <h2> Análisis Financiero</h2>


        <h4 class="finance-subtitle"> Ingresos Promedio por tipo</h4>
        @if(isset($data2['ingresos_promedios']) && count($data2['ingresos_promedios']) > 0)
            <table class="finance-table">
                <thead>
                <tr>
                    <th>Tipo de cita</th>
                    <th>Total citas</th>
                    <th>Promedio producto (USD)</th>
                    <th>Promedio servicios (USD)</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($data2['ingresos_promedios'] as $avg)
                    <tr>
                        <td>{{ $avg->tipo ?? '—' }}</td>
                        <td>{{ $avg->total_citas ?? 0 }}</td>
                        <td>${{ number_format($avg->promedio_producto ?? 0, 2) }}</td>
                        <td>${{ number_format($avg->promedio_servicios ?? 0, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-message"> No hay datos de ingresos promedio para mostrar.</div>
        @endif

        <!-- Finanzas por tipo de cita -->
        <h4 class="finance-subtitle">Finanzas por tipo de cita</h4>
        @if(isset($data2['finanzas_tipo_cita']) && count($data2['finanzas_tipo_cita']) > 0)
            <table class="finance-table">
                <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Total citas</th>
                    <th>Ganancia productos (USD)</th>
                    <th>Subtotal servicios (USD)</th>
                    <th>Total (USD)</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($data2['finanzas_tipo_cita'] as $avg)
                    <tr>
                        <td>{{ $avg->tipo ?? '—' }}</td>
                        <td>{{ $avg->total_citas ?? 0 }}</td>
                        <td>${{ number_format($avg->diferencia ?? 0, 2) }}</td>
                        <td>${{ number_format($avg->sutotal ?? 0, 2) }}</td>
                        <td><strong>${{ number_format($avg->total ?? 0, 2) }}</strong></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-message"> No hay registros financieros por tipo de cita.</div>
        @endif

        <h4 class="finance-subtitle"> Rentabilidad por Veterinario</h4>
        @if(isset($data2['finanzas_por_veterinario']) && count($data2['finanzas_por_veterinario']) > 0)
            <table class="finance-table">
                <thead>
                <tr>
                    <th>Nombre veterinario</th>
                    <th>Ganancia productos (USD)</th>
                    <th>Ganancia servicios (USD)</th>
                    <th>Total citas</th>
                    <th>Total facturado (USD)</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($data2['finanzas_por_veterinario'] as $avg)
                    <tr>
                        <td>{{ $avg->nombre ?? '—' }}</td>
                        <td>${{ number_format($avg->ganancia_productos ?? 0, 2) }}</td>
                        <td>${{ number_format($avg->ganancia_servicios ?? 0, 2) }}</td>
                        <td>{{ $avg->total_citas ?? 0 }}</td>
                        <td><strong>${{ number_format($avg->total ?? 0, 2) }}</strong></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-message">No hay datos financieros por veterinario disponibles.</div>
        @endif

  
        <h4 class="finance-subtitle"> Productos más utilizados</h4>
        @if(isset($productos) && count($productos) > 0)
            <table class="finance-table">
                <thead>
                <tr>
                    <th>Nombre producto</th>
                    <th>Cantidad usada</th>
                    <th>Ganancia generada (USD)</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td>{{ $producto->nombre ?? '—' }}</td>
                        <td>{{ $producto->counted ?? 0 }}</td>
                        <td>${{ number_format($producto->diferencia ?? 0, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-message"> No se registró uso de productos.</div>
        @endif


        <h4 class="finance-subtitle"> Servicios más demandados</h4>
        @if(isset($servicios) && count($servicios) > 0)
            <table class="finance-table">
                <thead>
                <tr>
                    <th>Nombre servicio</th>
                    <th>Cantidad solicitada</th>
                    <th>Ingresos (USD)</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($servicios as $servicio)
                    <tr>
                        <td>{{ $servicio->nombre ?? '—' }}</td>
                        <td>{{ $servicio->cantidad ?? 0 }}</td>
                        <td>${{ number_format($servicio->sutotal ?? 0, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-message"> No hay servicios </div>
        @endif

        <h4 class="finance-subtitle"> Alertas de inventario · Stock bajo</h4>
        @if(isset($productostock) && count($productostock) > 0)
            <table class="finance-table">
                <thead>
                <tr>
                    <th>Producto</th>
                    <th>Marca</th>
                    <th>Stock actual</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($productostock as $producto)
                    <tr>
                        <td>{{ $producto->nombre ?? '—' }}</td>
                        <td>{{ $producto->marca ?? '—' }}</td>
                        <td class="badge" style="background:#fee2e2; color:#b91c1c; font-weight:bold;">{{ $producto->stock ?? 0 }} unidades</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-message">
                 No se encontraron productos con stock crítico.
            </div>
        @endif
    </div>

    <footer>
        Reporte generado automáticamente · Sistema de gestión veterinaria · Datos actualizados al instante
    </footer>
</div>
</body>
</html>