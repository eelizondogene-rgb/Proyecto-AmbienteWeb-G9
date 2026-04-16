<div class="row mb-4">
    <div class="col-12">
        <h2 class="page-title">Mi Historial</h2>
        <p class="page-subtitle">Rendimiento en todos los exámenes</p>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="stat-card stat-blue p-3 text-center">
            <div class="stat-number"><?php echo $totalExamenes ?? 0; ?></div>
            <div class="stat-label">Exámenes Realizados</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-green p-3 text-center">
            <div class="stat-number"><?php echo $totalAprobados ?? 0; ?></div>
            <div class="stat-label">Aprobados</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-red p-3 text-center">
            <div class="stat-number"><?php echo $totalReprobados ?? 0; ?></div>
            <div class="stat-label">Reprobados</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-orange p-3 text-center">
            <div class="stat-number"><?php echo $promedioGeneral ?? 0; ?>%</div>
            <div class="stat-label">Promedio General</div>
        </div>
    </div>
</div>

<div class="panel-card">
    <div class="panel-header">
        <h5 class="panel-title">Detalle de Exámenes</h5>
    </div>
    <div class="panel-body">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Examen</th>
                    <th>Fecha</th>
                    <th>Puntaje</th>
                    <th>Porcentaje</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultados as $resultado): ?>
                <tr>
                    <td><?php echo htmlspecialchars($resultado['examen_nombre'] ?? 'N/A'); ?></td>
                    <td><?php echo $resultado['fecha_calificacion'] ?? 'N/A'; ?></td>
                    <td><?php echo ($resultado['puntaje_obtenido'] ?? 0) . '/' . ($resultado['puntaje_total'] ?? 0); ?></td>
                    <td>
                        <strong><?php echo round($resultado['porcentaje'] ?? 0, 1); ?>%</strong>
                        <div class="progress mt-1" style="height: 5px;">
                            <div class="progress-bar <?php echo (($resultado['porcentaje'] ?? 0) >= 70) ? 'bg-success' : 'bg-danger'; ?>" 
                                 style="width: <?php echo round($resultado['porcentaje'] ?? 0, 1); ?>%"></div>
                        </div>
                    </td>
                    <td>
                        <?php if (($resultado['porcentaje'] ?? 0) >= 70): ?>
                            <span class="badge badge-activo">Aprobado</span>
                        <?php else: ?>
                            <span class="badge badge-finalizado">Reprobado</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>