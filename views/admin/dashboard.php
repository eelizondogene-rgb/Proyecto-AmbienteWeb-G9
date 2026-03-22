<div class="row mb-4">
    <div class="col-12">
        <h2 class="page-title">Panel de Administrador</h2>
        <p class="page-subtitle">Resumen general del sistema</p>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="stat-card stat-blue">
            <div class="stat-number"><?php echo $totalExamenes ?? 0; ?></div>
            <div class="stat-label">Exámenes Activos</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="stat-card stat-green">
            <div class="stat-number"><?php echo $totalCodigos ?? 0; ?></div>
            <div class="stat-label">Códigos Generados</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="stat-card stat-orange">
            <div class="stat-number"><?php echo $totalResultados ?? 0; ?></div>
            <div class="stat-label">Exámenes Completados</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="stat-card stat-red">
            <div class="stat-number">0</div>
            <div class="stat-label">Sesiones en Curso</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8 col-md-12">
        <div class="panel-card">
            <div class="panel-header">
                <h5 class="panel-title">Exámenes Recientes</h5>
                <a href="index.php?action=admin_examenes" class="btn btn-sm btn-outline-custom">Ver todos</a>
            </div>
            <div class="panel-body">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Fecha Inicio</th>
                            <th>Duración</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </thead>
                    <tbody>
                        <?php if (!empty($examenesRecientes)): ?>
                            <?php foreach ($examenesRecientes as $examen): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($examen['nombre']); ?></td>
                                    <td><?php echo $examen['fecha_inicio'] ?? 'N/A'; ?></td>
                                    <td><?php echo $examen['duracion_minutos']; ?> min</td>
                                    <td>
                                        <span class="badge badge-<?php echo $examen['estado']; ?>">
                                            <?php echo ucfirst($examen['estado']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="index.php?action=admin_examenes&id=<?php echo $examen['id_examen']; ?>" class="btn btn-xs btn-accion">Ver</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay exámenes registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-12">
        <div class="panel-card">
            <div class="panel-header">
                <h5 class="panel-title">Acciones Rápidas</h5>
            </div>
            <div class="panel-body d-flex flex-column gap-3">
                <a href="index.php?action=admin_examenes" class="btn btn-accion-rapida">Crear Nuevo Examen</a>
                <a href="index.php?action=admin_examenes" class="btn btn-accion-rapida">Ver Exámenes</a>
                <a href="index.php?action=admin_codigos" class="btn btn-accion-rapida">Generar Códigos de Acceso</a>
                <a href="index.php?action=admin_resultados" class="btn btn-accion-rapida">Ver Resultados</a>
                <a href="index.php?action=admin_reportes" class="btn btn-accion-rapida">Generar Reportes</a>
            </div>
        </div>
    </div>
</div>