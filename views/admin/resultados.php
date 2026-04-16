<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="page-title">Resultados</h2>
        <p class="page-subtitle">Desempeño de los estudiantes</p>
    </div>
    <div class="col-md-6">
        <form method="GET" class="d-flex gap-2">
            <input type="hidden" name="action" value="admin_resultados">
            <select name="id_examen" class="form-select input-custom" onchange="this.form.submit()">
                <option value="0">Todos los exámenes</option>
                <?php foreach ($examenes as $ex): ?>
                    <option value="<?php echo $ex['id_examen']; ?>" <?php echo ($id_examen == $ex['id_examen']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($ex['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</div>

<?php if ($estadisticas && $id_examen > 0): ?>
<div class="row g-4 mb-4">
    <div class="col-lg-2 col-md-4">
        <div class="stat-card stat-blue p-3 text-center">
            <div class="stat-number h3"><?php echo $estadisticas['total_presentados'] ?? 0; ?></div>
            <div class="stat-label">Presentados</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4">
        <div class="stat-card stat-green p-3 text-center">
            <div class="stat-number h3"><?php echo $estadisticas['aprobados'] ?? 0; ?></div>
            <div class="stat-label">Aprobados</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4">
        <div class="stat-card stat-red p-3 text-center">
            <div class="stat-number h3"><?php echo $estadisticas['reprobados'] ?? 0; ?></div>
            <div class="stat-label">Reprobados</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-orange p-3 text-center">
            <div class="stat-number h3"><?php echo $estadisticas['promedio'] ?? 0; ?>%</div>
            <div class="stat-label">Promedio</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card p-3 text-center" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
            <div class="stat-number h3"><?php echo $estadisticas['maximo'] ?? 0; ?>%</div>
            <div class="stat-label">Máximo</div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="panel-card">
    <div class="panel-body">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Estudiante</th>
                    <th>Examen</th>
                    <th>Puntaje</th>
                    <th>Porcentaje</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultados as $resultado): ?>
                <tr>
                    <td><?php echo htmlspecialchars($resultado['estudiante_nombre'] ?? $resultado['email'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($resultado['examen_nombre'] ?? 'N/A'); ?></td>
                    <td><?php echo $resultado['puntaje_obtenido']; ?>/<?php echo $resultado['puntaje_total']; ?></td>
                    <td><?php echo $resultado['porcentaje']; ?>%</td>
                    <td>
                        <span class="badge badge-<?php echo $resultado['estado'] == 'aprobado' ? 'activo' : 'finalizado'; ?>">
                            <?php echo ucfirst($resultado['estado']); ?>
                        </span>
                    </span>
                    <td><?php echo $resultado['fecha_calificacion'] ?? $resultado['fecha_presentacion'] ?? 'N/A'; ?></td>
                    <td>
                        <a href="index.php?action=admin_detalle_resultado&id=<?php echo $resultado['id_resultado']; ?>" class="btn btn-xs btn-accion">
                            Ver detalle
                        </a>
                    </span>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>