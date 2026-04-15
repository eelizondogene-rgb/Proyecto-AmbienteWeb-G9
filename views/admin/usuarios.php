<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="page-title">Gestión de Usuarios</h2>
        <p class="page-subtitle">Administra los estudiantes del sistema</p>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalUsuario" onclick="resetForm()">
            + Nuevo Estudiante
        </button>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="panel-card">
    <div class="panel-body">
        <table class="table table-custom">
            <thead>
                <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Registro</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes as $est): ?>
                <tr>
                    <td><?php echo $est['id_usuario']; ?></td>
                    <td><?php echo htmlspecialchars($est['nombre'] . ' ' . ($est['apellidos'] ?? '')); ?></td>
                    <td><?php echo htmlspecialchars($est['email']); ?></td>
                    <td><?php echo $est['telefono'] ?? 'N/A'; ?></td>
                    <td><?php echo $est['fecha_registro']; ?></td>
                    <td>
                        <button class="btn btn-xs btn-accion" onclick="editarUsuario(<?php echo htmlspecialchars(json_encode($est)); ?>)">Editar</button>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Desactivar este estudiante?')">
                            <input type="hidden" name="action" value="eliminar">
                            <input type="hidden" name="id_usuario" value="<?php echo $est['id_usuario']; ?>">
                            <button type="submit" class="btn btn-xs btn-eliminar">Desactivar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para crear/editar usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="modalTitle">Nuevo Estudiante</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" id="formAction" value="crear">
                <input type="hidden" name="id_usuario" id="idUsuario" value="0">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label label-custom">Nombre completo</label>
                        <input type="text" class="form-control input-custom" name="nombre" id="nombreUsuario" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label label-custom">Apellidos</label>
                        <input type="text" class="form-control input-custom" name="apellidos" id="apellidosUsuario">
                    </div>
                    <div class="mb-3">
                        <label class="form-label label-custom">Email</label>
                        <input type="email" class="form-control input-custom" name="email" id="emailUsuario" required>
                    </div>
                    <div class="mb-3" id="passwordField">
                        <label class="form-label label-custom">Contraseña</label>
                        <input type="password" class="form-control input-custom" name="password" id="passwordUsuario">
                        <small class="text-muted">Mínimo 4 caracteres</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label label-custom">Teléfono</label>
                        <input type="text" class="form-control input-custom" name="telefono" id="telefonoUsuario">
                    </div>
                    <div class="mb-3">
                        <label class="form-label label-custom">Fecha de nacimiento</label>
                        <input type="date" class="form-control input-custom" name="fecha_nacimiento" id="fechaNacimientoUsuario">
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
    document.getElementById('idUsuario').value = '0';
    document.getElementById('modalTitle').innerText = 'Nuevo Estudiante';
    document.getElementById('passwordField').style.display = 'block';
    document.getElementById('passwordUsuario').required = true;
    document.getElementById('formUsuario')?.reset();
}

function editarUsuario(usuario) {
    document.getElementById('formAction').value = 'editar';
    document.getElementById('idUsuario').value = usuario.id_usuario;
    document.getElementById('modalTitle').innerText = 'Editar Estudiante';
    document.getElementById('nombreUsuario').value = usuario.nombre || '';
    document.getElementById('apellidosUsuario').value = usuario.apellidos || '';
    document.getElementById('emailUsuario').value = usuario.email;
    document.getElementById('telefonoUsuario').value = usuario.telefono || '';
    document.getElementById('fechaNacimientoUsuario').value = usuario.fecha_nacimiento || '';
    document.getElementById('passwordField').style.display = 'none';
    document.getElementById('passwordUsuario').required = false;
    
    var modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
    modal.show();
}
</script>