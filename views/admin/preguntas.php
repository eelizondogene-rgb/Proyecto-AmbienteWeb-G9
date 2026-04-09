<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="page-title">Preguntas: <?php echo htmlspecialchars($examen['nombre']); ?></h2>
        <p class="page-subtitle">Gestiona las preguntas de este examen</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="index.php?action=admin_examenes" class="btn btn-secondary-custom me-2">← Volver</a>
        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalPregunta" onclick="resetForm()">
            + Nueva Pregunta
        </button>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="panel-card">
    <div class="panel-body">
        <div class="mb-3">
            <strong>Total de preguntas:</strong> <?php echo $totalPreguntas; ?>
        </div>
        
        <?php foreach ($preguntas as $index => $pregunta): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h6>Pregunta <?php echo $index + 1; ?>:</h6>
                <p><?php echo nl2br(htmlspecialchars($pregunta['texto'])); ?></p>
                <div class="row">
                    <div class="col-md-6">
                        <small><strong>A)</strong> <?php echo htmlspecialchars($pregunta['opcion_a']); ?></small><br>
                        <small><strong>B)</strong> <?php echo htmlspecialchars($pregunta['opcion_b']); ?></small><br>
                        <small><strong>C)</strong> <?php echo htmlspecialchars($pregunta['opcion_c']); ?></small><br>
                        <small><strong>D)</strong> <?php echo htmlspecialchars($pregunta['opcion_d']); ?></small>
                    </div>
                    <div class="col-md-6">
                        <span class="badge badge-success">Correcta: <?php echo strtoupper($pregunta['respuesta_correcta']); ?></span>
                        <span class="badge badge-info">Puntos: <?php echo $pregunta['puntos']; ?></span>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-xs btn-accion" onclick="editarPregunta(<?php echo htmlspecialchars(json_encode($pregunta)); ?>)">Editar</button>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta pregunta?')">
                        <input type="hidden" name="action" value="eliminar">
                        <input type="hidden" name="id_pregunta" value="<?php echo $pregunta['id_pregunta']; ?>">
                        <input type="hidden" name="id_examen" value="<?php echo $examen['id_examen']; ?>">
                        <button type="submit" class="btn btn-xs btn-eliminar">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if (empty($preguntas)): ?>
            <div class="alert alert-info">No hay preguntas registradas. ¡Crea la primera!</div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para crear/editar pregunta -->
<div class="modal fade" id="modalPregunta" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="modalTitle">Nueva Pregunta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" id="formAction" value="crear">
                <input type="hidden" name="id_pregunta" id="idPregunta" value="0">
                <input type="hidden" name="id_examen" value="<?php echo $examen['id_examen']; ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label label-custom">Texto de la pregunta</label>
                        <textarea class="form-control input-custom" name="texto" id="textoPregunta" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label label-custom">Opción A</label>
                            <input type="text" class="form-control input-custom" name="opcion_a" id="opcionA" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label label-custom">Opción B</label>
                            <input type="text" class="form-control input-custom" name="opcion_b" id="opcionB" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="form-label label-custom">Opción C</label>
                            <input type="text" class="form-control input-custom" name="opcion_c" id="opcionC" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label label-custom">Opción D</label>
                            <input type="text" class="form-control input-custom" name="opcion_d" id="opcionD" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="form-label label-custom">Respuesta correcta</label>
                            <select class="form-select input-custom" name="respuesta_correcta" id="respuestaCorrecta" required>
                                <option value="">Seleccione...</option>
                                <option value="a">A</option>
                                <option value="b">B</option>
                                <option value="c">C</option>
                                <option value="d">D</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label label-custom">Puntos</label>
                            <input type="number" class="form-control input-custom" name="puntos" id="puntosPregunta" value="1" required>
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
    document.getElementById('idPregunta').value = '0';
    document.getElementById('modalTitle').innerText = 'Nueva Pregunta';
    document.getElementById('formPregunta')?.reset();
}

function editarPregunta(pregunta) {
    document.getElementById('formAction').value = 'editar';
    document.getElementById('idPregunta').value = pregunta.id_pregunta;
    document.getElementById('modalTitle').innerText = 'Editar Pregunta';
    document.getElementById('textoPregunta').value = pregunta.texto;
    document.getElementById('opcionA').value = pregunta.opcion_a;
    document.getElementById('opcionB').value = pregunta.opcion_b;
    document.getElementById('opcionC').value = pregunta.opcion_c;
    document.getElementById('opcionD').value = pregunta.opcion_d;
    document.getElementById('respuestaCorrecta').value = pregunta.respuesta_correcta;
    document.getElementById('puntosPregunta').value = pregunta.puntos;
    
    var modal = new bootstrap.Modal(document.getElementById('modalPregunta'));
    modal.show();
}
</script>