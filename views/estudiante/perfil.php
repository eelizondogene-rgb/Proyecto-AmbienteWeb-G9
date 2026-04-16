<div class="row mb-4">
    <div class="col-12">
        <h2 class="page-title">Mi Perfil</h2>
        <p class="page-subtitle">Información personal del estudiante</p>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="panel-card">
    <div class="panel-header">
        <h5 class="panel-title">Editar Perfil</h5>
    </div>
    <div class="panel-body">
        <form method="POST" action="index.php?action=estudiante_actualizar_perfil">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label label-custom">Nombre</label>
                    <input type="text" class="form-control input-custom" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label label-custom">Apellidos</label>
                    <input type="text" class="form-control input-custom" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label label-custom">Email</label>
                    <input type="email" class="form-control input-custom" value="<?php echo htmlspecialchars($_SESSION['usuario']['email']); ?>" disabled>
                    <small class="text-muted">El email no se puede modificar</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label label-custom">Teléfono</label>
                    <input type="text" class="form-control input-custom" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label label-custom">Fecha de Nacimiento</label>
                    <input type="date" class="form-control input-custom" name="fecha_nacimiento" value="<?php echo htmlspecialchars($usuario['fecha_nacimiento'] ?? ''); ?>">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary-custom">Guardar Cambios</button>
                <a href="index.php?action=estudiante_bienvenida" class="btn btn-secondary-custom">Cancelar</a>
            </div>
        </form>
    </div>
</div>