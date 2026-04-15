<div class="row mb-4">
    <div class="col-12">
        <h2 class="page-title">Reportes y Estadísticas</h2>
        <p class="page-subtitle">Análisis general del sistema de exámenes</p>
    </div>
</div>
<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-blue">
            <div class="stat-number"><?php echo $estadisticas['total_examenes'] ?? 0; ?></div>
            <div class="stat-label">Total Exámenes</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-green">
            <div class="stat-number"><?php echo $estadisticas['total_estudiantes'] ?? 0; ?></div>
            <div class="stat-label">Estudiantes Registrados</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-orange">
            <div class="stat-number"><?php echo $estadisticas['total_completados'] ?? 0; ?></div>
            <div class="stat-label">Exámenes Completados</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-purple" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
            <div class="stat-number"><?php echo $estadisticas['tasa_aprobacion'] ?? 0; ?>%</div>
            <div class="stat-label">Tasa de Aprobación</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-6">
        <div class="panel-card">
            <div class="panel-header">
                <h5 class="panel-title">Exámenes por Mes</h5>
            </div>
            <div class="panel-body">
                <canvas id="graficaExamenesMes" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel-card">
            <div class="panel-header">
                <h5 class="panel-title">Promedio General</h5>
            </div>
            <div class="panel-body text-center">
                <div class="display-1 fw-bold text-primary"><?php echo $estadisticas['promedio_general'] ?? 0; ?>%</div>
                <p class="text-muted">Promedio de calificaciones en todos los exámenes</p>
                <div class="progress mt-3" style="height: 20px;">
                    <div class="progress-bar progress-bar-custom" style="width: <?php echo $estadisticas['promedio_general'] ?? 0; ?>%;">
                        <?php echo $estadisticas['promedio_general'] ?? 0; ?>%
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel-card mb-5">
    <div class="panel-header">
        <h5 class="panel-title">Resultados por Examen</h5>
    </div>
    <div class="panel-body">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Examen</th>
                    <th>Presentados</th>
                    <th>Aprobados</th>
                    <th>Reprobados</th>
                    <th>Promedio</th>
                    <th>Nota Máx</th>
                    <th>Nota Mín</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultadosExamenes as $examen): ?>
                <tr>
                    <td><?php echo htmlspecialchars($examen['examen_nombre']); ?></td>
                    <td><?php echo $examen['total_presentados'] ?? 0; ?></td>
                    <td><?php echo $examen['aprobados'] ?? 0; ?> (<span class="text-success"><?php echo $examen['total_presentados'] > 0 ? round(($examen['aprobados'] / $examen['total_presentados']) * 100, 1) : 0; ?>%</span>)</td>
                    <td><?php echo $examen['reprobados'] ?? 0; ?> (<span class="text-danger"><?php echo $examen['total_presentados'] > 0 ? round(($examen['reprobados'] / $examen['total_presentados']) * 100, 1) : 0; ?>%</span>)</td>
                    <td><?php echo round($examen['promedio'] ?? 0, 1); ?>%</td>
                    <td><?php echo round($examen['nota_maxima'] ?? 0, 1); ?>%</td>
                    <td><?php echo round($examen['nota_minima'] ?? 0, 1); ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Top Estudiantes -->
<div class="row g-4">
    <div class="col-lg-6">
        <div class="panel-card">
            <div class="panel-header">
                <h5 class="panel-title">Top 10 Estudiantes</h5>
            </div>
            <div class="panel-body">
                <table class="table table-custom">
                    <thead>
                        <tr><th>Estudiante</th><th>Exámenes</th><th>Promedio</th><th>Aprobados</th><th>Reprobados</th></thead>
                    <tbody>
                        <?php foreach ($topEstudiantes as $est): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($est['nombre'] . ' ' . ($est['apellidos'] ?? '')); ?></td>
                            <td><?php echo $est['examenes_presentados'] ?? 0; ?></td>
                            <td><strong><?php echo round($est['promedio_general'] ?? 0, 1); ?>%</strong></td>
                            <td><?php echo $est['aprobados'] ?? 0; ?></td>
                            <td><?php echo $est['reprobados'] ?? 0; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="panel-card">
            <div class="panel-header">
                <h5 class="panel-title">Uso de Códigos de Acceso</h5>
            </div>
            <div class="panel-body">
                <table class="table table-custom">
                    <thead>
                        <tr><th>Código</th><th>Examen</th><th>Usos</th><th>Estado</th><th>Asignado</th></thead>
                    <tbody>
                        <?php foreach ($usoCodigos as $codigo): ?>
                        <tr>
                            <td><code><?php echo $codigo['codigo']; ?></code></td>
                            <td><?php echo htmlspecialchars($codigo['examen_nombre']); ?></td>
                            <td><?php echo $codigo['usos_actuales']; ?>/<?php echo $codigo['usos_max']; ?></td>
                            <td><span class="badge badge-<?php echo $codigo['estado']; ?>"><?php echo ucfirst($codigo['estado']); ?></span></td>
                            <td><?php echo $codigo['asignado']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

    <?php
    $meses = [1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun', 
              7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'];
    $datosMeses = array_fill(1, 12, 0);
    foreach ($examenesPorMes as $item) {
        $datosMeses[$item['mes']] = $item['cantidad'];
    }
    ?>
    
    var ctx = document.getElementById('graficaExamenesMes').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Exámenes creados',
                data: [<?php echo implode(',', array_values($datosMeses)); ?>],
                backgroundColor: 'rgba(26, 43, 94, 0.7)',
                borderColor: 'rgba(26, 43, 94, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });
</script>