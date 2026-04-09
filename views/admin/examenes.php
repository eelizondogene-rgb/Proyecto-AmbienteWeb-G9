<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="page-title">Gestión de Exámenes</h2>
        <p class="page-subtitle">Crea, edita y administra los exámenes</p>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalExamen" onclick="resetForm()">
            + Nuevo Examen
        </button>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="panel-card">
    <div class="panel-body">
        <table class="table table-custom">
            <thead>
                <tr><th>ID</th><th>Nombre</th><th>Duración</th><th>Preguntas</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($examenes as $examen): ?>
                <tr>
                    <td><?php echo $examen['id_examen']; ?></td>
                    <td><?php echo htmlspecialchars($examen['nombre']); ?></td>
                    <td><?php echo $examen['duracion_minutos']; ?> min</td>
                    <td>
                        <a href="index.php?action=admin_preguntas&id_examen=<?php echo $examen['id_examen']; ?>" class="btn btn-xs btn-accion">
                            Ver preguntas
                        </a>
                    </td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="cambiar_estado">
                            <input type="hidden" name="id_examen" value="<?php echo $examen['id_examen']; ?>">
                            <select name="estado" onchange="this.form.submit()" class="form-select form-select-sm" style="width:auto;display:inline-block;">
                                <option value="borrador" <?php echo $examen['estado'] == 'borrador' ? 'selected' : ''; ?>>Borrador</option>
                                <option value="activo" <?php echo $examen['estado'] == 'activo' ? 'selected' : ''; ?>>Activo</option>
                                <option value="finalizado" <?php echo $examen['estado'] == 'finalizado' ? 'selected' : ''; ?>>Finalizado</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-xs btn-accion" onclick="editarExamen(<?php echo htmlspecialchars(json_encode($examen)); ?>)">Editar</button>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este examen? Se eliminarán todas sus preguntas')">
                            <input type="hidden" name="action" value="eliminar">
                            <input type="hidden" name="id_examen" value="<?php echo $examen['id_examen']; ?>">
                            <button type="submit" class="btn btn-xs btn-eliminar">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para crear/editar examen -->
<div class="modal fade" id="modalExamen" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="modalTitle">Crear Nuevo Examen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formExamen">
                <input type="hidden" name="action" id="formAction" value="crear">
                <input type="hidden" name="id_examen" id="idExamen" value="0">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label label-custom">Nombre del Examen</label>
                        <input type="text" class="form-control input-custom" name="nombre" id="nombreExamen" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label label-custom">Descripción</label>
                        <textarea class="form-control input-custom" name="descripcion" id="descripcionExamen" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label label-custom">Duración (minutos)</label>
                            <input type="number" class="form-control input-custom" name="duracion_minutos" id="duracionExamen" value="60" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label label-custom">Estado</label>
                            <select class="form-select input-custom" name="estado" id="estadoExamen">
                                <option value="borrador">Borrador</option>
                                <option value="activo">Activo</option>
                                <option value="finalizado">Finalizado</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label label-custom">Fecha Inicio</label>
                            <input type="date" class="form-control input-custom" name="fecha_inicio" id="fechaInicioExamen">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label label-custom">Fecha Cierre</label>
                            <input type="date" class="form-control input-custom" name="fecha_cierre" id="fechaCierreExamen">
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary-custom">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('formAction').value = 'crear';
    document.getElementById('idExamen').value = '0';
    document.getElementById('modalTitle').innerText = 'Crear Nuevo Examen';
    document.getElementById('formExamen').reset();
}

function editarExamen(examen) {
    document.getElementById('formAction').value = 'editar';
    document.getElementById('idExamen').value = examen.id_examen;
    document.getElementById('modalTitle').innerText = 'Editar Examen';
    document.getElementById('nombreExamen').value = examen.nombre;
    document.getElementById('descripcionExamen').value = examen.descripcion || '';
    document.getElementById('duracionExamen').value = examen.duracion_minutos;
    document.getElementById('estadoExamen').value = examen.estado;
    document.getElementById('fechaInicioExamen').value = examen.fecha_inicio || '';
    document.getElementById('fechaCierreExamen').value = examen.fecha_cierre || '';
    
    var modal = new bootstrap.Modal(document.getElementById('modalExamen'));
    modal.show();
}
</script>