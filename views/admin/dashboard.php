<div class="row mb-4">
    <div class="col-12">
        <h2 class="page-title">Panel de Administrador</h2>
        <p class="page-subtitle">Resumen general del sistema</p>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-blue">
            <div class="stat-number"><?php echo $totalExamenes ?? 0; ?></div>
            <div class="stat-label">Exámenes</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-green">
            <div class="stat-number"><?php echo $totalEstudiantes ?? 0; ?></div>
            <div class="stat-label">Estudiantes</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-orange">
            <div class="stat-number"><?php echo $totalCodigos ?? 0; ?></div>
            <div class="stat-label">Códigos</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-purple" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
            <div class="stat-number"><?php echo $totalResultados ?? 0; ?></div>
            <div class="stat-label">Exámenes Completados</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="panel-card">
            <div class="panel-header">
                <h5 class="panel-title">Exámenes Recientes</h5>
                <a href="index.php?action=admin_examenes" class="btn btn-sm btn-outline-custom">Ver todos</a>
            </div>
            <div class="panel-body">
                <table class="table table-custom">
                    <thead>
                        <tr><th>Nombre</th><th>Duración</th><th>Estado</th><th>Acciones</th></thead>
                    <tbody>
                        <?php if (!empty($examenesRecientes)): ?>
                            <?php foreach ($examenesRecientes as $examen): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($examen['nombre']); ?></td>
                                    <td><?php echo $examen['duracion_minutos']; ?> min</td>
                                    <td><span class="badge badge-<?php echo $examen['estado']; ?>"><?php echo ucfirst($examen['estado']); ?></span></td>
                                    <td>
                                        <a href="index.php?action=admin_preguntas&id_examen=<?php echo $examen['id_examen']; ?>" class="btn btn-xs btn-accion">Preguntas</a>
                                        <a href="index.php?action=admin_codigos&id_examen=<?php echo $examen['id_examen']; ?>" class="btn btn-xs btn-accion">Códigos</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">No hay exámenes registrados</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel-card">
            <div class="panel-header">
                <h5 class="panel-title">Estadísticas</h5>
            </div>
            <div class="panel-body">
                <div class="mb-3">
                    <small>Promedio general</small>
                    <h3><?php echo $promedioGeneral ?? 0; ?>%</h3>
                    <div class="progress mt-1" style="height:8px;">
                        <div class="progress-bar progress-bar-custom" style="width: <?php echo $promedioGeneral ?? 0; ?>%"></div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Exámenes activos:</span>
                    <strong><?php echo $totalExamenes ?? 0; ?></strong>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <span>Estudiantes registrados:</span>
                    <strong><?php echo $totalEstudiantes ?? 0; ?></strong>
                </div>
            </div>
        </div>

        <div class="panel-card mt-4">
            <div class="panel-header">
                <h5 class="panel-title">Acciones Rápidas</h5>
            </div>
            <div class="panel-body d-flex flex-column gap-2">
                <a href="index.php?action=admin_examenes" class="btn btn-accion-rapida">+ Nuevo Examen</a>
                <a href="index.php?action=admin_codigos" class="btn btn-accion-rapida">+ Generar Códigos</a>
                <a href="index.php?action=admin_usuarios" class="btn btn-accion-rapida">+ Nuevo Estudiante</a>
                <a href="index.php?action=admin_reportes" class="btn btn-accion-rapida">📊 Ver Reportes</a>
            </div>
        </div>
    </div>
</div>