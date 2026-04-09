<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="page-title">Códigos de Acceso</h2>
        <p class="page-subtitle">Genera y administra códigos para los estudiantes</p>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalGenerar">
            + Generar Códigos
        </button>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['codigos_generados'])): ?>
    <div class="alert alert-info">
        <strong>Códigos generados:</strong><br>
        <?php foreach ($_SESSION['codigos_generados'] as $cod): ?>
            <code><?php echo $cod; ?></code><br>
        <?php endforeach; ?>
        <?php unset($_SESSION['codigos_generados']); ?>
    </div>
<?php endif; ?>

<div class="panel-card">
    <div class="panel-body">
        <table class="table table-custom">
            <thead>
                <tr><th>Código</th><th>Examen</th><th>Usos</th><th>Vencimiento</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($codigos as $codigo): ?>
                <tr>
                    <td><code><?php echo $codigo['codigo']; ?></code></td>
                    <td><?php echo htmlspecialchars($codigo['examen_nombre'] ?? 'N/A'); ?></td>
                    <td><?php echo $codigo['usos_actuales']; ?>/<?php echo $codigo['usos_max']; ?></td>
                    <td><?php echo $codigo['fecha_vencimiento'] ?? 'Sin vencimiento'; ?></td>
                    <td>
                        <span class="badge badge-<?php echo $codigo['estado']; ?>">
                            <?php echo ucfirst($codigo['estado']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($codigo['estado'] == 'disponible'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="revocar">
                            <input type="hidden" name="id_codigo" value="<?php echo $codigo['id_codigo']; ?>">
                            <button type="submit" class="btn btn-xs btn-warning" onclick="return confirm('¿Revocar este código?')">Revocar</button>
                        </form>
                        <?php endif; ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="eliminar">
                            <input type="hidden" name="id_codigo" value="<?php echo $codigo['id_codigo']; ?>">
                            <button type="submit" class="btn btn-xs btn-eliminar" onclick="return confirm('¿Eliminar este código?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para generar códigos -->
<div class="modal fade" id="modalGenerar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title">Generar Códigos de Acceso</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="generar">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label label-custom">Examen</label>
                        <select class="form-select input-custom" name="id_examen" required>
                            <option value="">Seleccione un examen...</option>
                            <?php foreach ($examenes as $examen): ?>
                                <option value="<?php echo $examen['id_examen']; ?>"><?php echo htmlspecialchars($examen['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label label-custom">Cantidad de códigos</label>
                        <input type="number" class="form-control input-custom" name="cantidad" value="1" min="1" max="100" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label label-custom">Usos por código</label>
                        <input type="number" class="form-control input-custom" name="usos_max" value="1" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label label-custom">Fecha de vencimiento (opcional)</label>
                        <input type="date" class="form-control input-custom" name="fecha_vencimiento">
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary-custom">Generar</button>
                </div>
            </form>
        </div>
    </div>
</div>