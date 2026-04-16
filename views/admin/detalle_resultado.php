<div class="row mb-4">
    <div class="col-12">
        <h2 class="page-title">Detalle del Resultado</h2>
        <p class="page-subtitle">Respuestas del estudiante</p>
    </div>
</div>

<div class="panel-card mb-4">
    <div class="panel-header">
        <h5 class="panel-title">Información del Examen</h5>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <strong>Estudiante:</strong> <?php echo htmlspecialchars($resultado['estudiante_nombre'] ?? 'N/A'); ?>
            </div>
            <div class="col-md-4">
                <strong>Examen:</strong> <?php echo htmlspecialchars($resultado['examen_nombre'] ?? 'N/A'); ?>
            </div>
            <div class="col-md-4">
                <strong>Fecha:</strong> <?php echo $resultado['fecha_calificacion'] ?? 'N/A'; ?>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-4">
                <strong>Puntaje:</strong> <?php echo $resultado['puntaje_obtenido']; ?>/<?php echo $resultado['puntaje_total']; ?>
            </div>
            <div class="col-md-4">
                <strong>Porcentaje:</strong> <?php echo $resultado['porcentaje']; ?>%
            </div>
            <div class="col-md-4">
                <strong>Estado:</strong> 
                <span class="badge badge-<?php echo $resultado['estado'] == 'aprobado' ? 'activo' : 'finalizado'; ?>">
                    <?php echo ucfirst($resultado['estado']); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="panel-card">
    <div class="panel-header">
        <h5 class="panel-title">Respuestas del Estudiante</h5>
    </div>
    <div class="panel-body">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pregunta</th>
                    <th>Respuesta del Estudiante</th>
                    <th>Correcta</th>
                    <th>Puntaje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($respuestas as $index => $resp): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($resp['texto']); ?></td>
                    <td>
                        <?php if ($resp['respuesta_seleccionada']): ?>
                            <?php echo strtoupper($resp['respuesta_seleccionada']); ?>
                        <?php else: ?>
                            <span class="text-muted">No respondida</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($resp['es_correcta']): ?>
                            <span class="text-success">✓ Correcta</span>
                        <?php else: ?>
                            <span class="text-danger">✗ Incorrecta</span>
                            <br><small class="text-muted">Correcta: <?php echo strtoupper($resp['respuesta_correcta']); ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $resp['puntaje_obtenido']; ?>/<?php echo $resp['puntos']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-active">
                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                    <td><strong><?php echo $resultado['puntaje_obtenido']; ?>/<?php echo $resultado['puntaje_total']; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="mt-3">
    <a href="index.php?action=admin_resultados" class="btn btn-secondary-custom">← Volver a Resultados</a>
</div>