<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="panel-card text-center">
            <div class="panel-header"><h5 class="panel-title mb-0">Bienvenido a ExamWeb</h5></div>
            <div class="panel-body py-5">
                <div class="logo-icon mx-auto mb-4" style="width: 100px; height: 100px; font-size: 40px; background: var(--primario);">EW</div>
                <h2 class="mb-3">¡Hola, <?php echo htmlspecialchars($usuario['nombre'] ?? 'Estudiante'); ?>!</h2>
                <p class="lead mb-4">Has iniciado sesión correctamente en el sistema de exámenes.</p>
                <div class="row g-4 mt-4">
                    <div class="col-md-6"><div class="stat-card stat-blue p-4"><div class="stat-number h1 mb-2"><?php echo $examenPendiente ? '1' : '0'; ?></div><div class="stat-label">Examen<?php echo $examenPendiente ? ' pendiente' : 'es completados'; ?></div><?php if ($examenPendiente): ?><p class="mt-3 small"><?php echo htmlspecialchars($examenPendiente['nombre']); ?></p><?php endif; ?></div></div>
                    <div class="col-md-6"><div class="stat-card stat-green p-4"><div class="stat-number h1 mb-2"><?php echo $examenPendiente['duracion_minutos'] ?? '90'; ?></div><div class="stat-label">Minutos disponibles</div><p class="mt-3 small">Tiempo estimado del examen</p></div></div>
                </div>
                <div class="alert alert-info mt-4 text-start"><strong>📝 Instrucciones importantes:</strong><ul class="mb-0 mt-2"><li>El examen tiene una duración definida</li><li>Una vez iniciado, no puedes pausar el tiempo</li><li>Puedes navegar entre preguntas usando los botones</li><li>Las preguntas respondidas se marcan en verde</li><li>Al finalizar, haz clic en "Finalizar Examen"</li></ul></div>
                <div class="d-grid gap-3 mt-4">
                    <?php if ($examenPendiente): ?>
                        <a href="index.php?action=examen_realizar&id=<?php echo $examenPendiente['id_examen']; ?>" class="btn btn-primary-custom btn-lg">Comenzar Examen</a>
                    <?php else: ?>
                        <button class="btn btn-secondary-custom btn-lg" disabled>No hay exámenes pendientes</button>
                    <?php endif; ?>
                    <a href="index.php?action=estudiante_perfil" class="btn btn-secondary-custom">Ver mi perfil</a>
                </div>
            </div>
        </div>
    </div>
</div>