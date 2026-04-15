<div class="row mb-4">
    <div class="col-12">
        <h2 class="page-title">Mis Resultados</h2>
        <p class="page-subtitle">Historial de tus exámenes completados</p>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="panel-card">
    <div class="panel-body">
        <?php if (empty($resultados)): ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> No has completado ningún examen aún.
                <br>Usa un código de acceso para comenzar tu primer examen.
            </div>
        <?php else: ?>
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>Examen</th>
                        <th>Fecha</th>
                        <th>Puntaje</th>
                        <th>Porcentaje</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $resultado): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($resultado['examen_nombre'] ?? 'N/A'); ?></td>
                        <td><?php echo $resultado['fecha_calificacion'] ?? $resultado['fecha_presentacion'] ?? 'N/A'; ?></td>
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
                        <td>
                            <button class="btn btn-xs btn-accion" onclick="verDetalle(<?php echo $resultado['id_resultado']; ?>)">Ver detalles</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<script>
function verDetalle(id) {
    alert("Detalles del resultado " + id + " - Próximamente");
}
</script>