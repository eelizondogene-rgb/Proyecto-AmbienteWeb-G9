<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExamWeb - Examen en Curso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body class="examen-page">

<nav class="navbar navbar-exam">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <span class="brand-nav"><span class="logo-nav">EW</span> ExamWeb</span>
        <span class="exam-title-nav">Admisión Ingeniería 2025</span>
        <div class="d-flex align-items-center gap-3">
            <div class="timer-box" id="temporizador">
                <span id="tiempoRestante">90:00</span>
            </div>
            <?php if (!empty($_SESSION["nombre"])): ?>
                <span class="usuario-nav"><b><?php echo $_SESSION["nombre"]; ?></b></span>
            <?php endif; ?>
            <a href="./auth/logout.php" class="btn btn-logout">Salir</a>
        </div>
    </div>
</nav>

    <main class="container py-4">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-12 mb-4">
                <div class="panel-card">
                    <div class="panel-header">
                        <h6 class="panel-title mb-0">Navegación</h6>
                    </div>
                    <div class="panel-body">
                        <div class="d-flex flex-wrap gap-2" id="navegacionPreguntas">
                            <button class="btn btn-nav-pregunta activa" onclick="irPregunta(1)">1</button>
                            <button class="btn btn-nav-pregunta" onclick="irPregunta(2)">2</button>
                            <button class="btn btn-nav-pregunta respondida" onclick="irPregunta(3)">3</button>
                            <button class="btn btn-nav-pregunta respondida" onclick="irPregunta(4)">4</button>
                            <button class="btn btn-nav-pregunta" onclick="irPregunta(5)">5</button>
                        </div>
                        <div class="mt-3">
                            <div class="leyenda-item"><span class="dot dot-activa"></span> Actual</div>
                            <div class="leyenda-item"><span class="dot dot-respondida"></span> Respondida</div>
                            <div class="leyenda-item"><span class="dot dot-pendiente"></span> Pendiente</div>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">Progreso: <b>2/5</b></small>
                            <div class="progress mt-1" style="height:8px;">
                                <div class="progress-bar progress-bar-custom" style="width: 40%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9 col-md-8 col-12">
                <div class="panel-card" id="contenedorPregunta">
                    <div class="panel-header d-flex justify-content-between align-items-center">
                        <h5 class="panel-title mb-0">Pregunta <span id="numPreguntaActual">1</span> de 5</h5>
                        <span class="badge badge-categoria">Matemáticas</span>
                    </div>
                    <div class="panel-body">
                        <p class="pregunta-texto" id="textoPregunta">
                            ¿Cuál es el resultado de resolver la ecuación cuadrática x² - 5x + 6 = 0?
                        </p>

                        <form id="formRespuesta">
                            <div class="opciones-container" id="opcionesContainer">
                                <label class="opcion-label">
                                    <input type="radio" name="respuesta" value="a"> x = 2 y x = 3
                                </label>
                                <label class="opcion-label">
                                    <input type="radio" name="respuesta" value="b"> x = 1 y x = 6
                                </label>
                                <label class="opcion-label">
                                    <input type="radio" name="respuesta" value="c"> x = -2 y x = -3
                                </label>
                                <label class="opcion-label">
                                    <input type="radio" name="respuesta" value="d"> x = 5 y x = -1
                                </label>
                            </div>
                        </form>

                        <div class="d-flex justify-content-between mt-4">
                            <button class="btn btn-secondary" id="btnAnterior" onclick="preguntaAnterior()" disabled>
                                Anterior
                            </button>
                            <button class="btn btn-primary-custom" id="btnSiguiente" onclick="preguntaSiguiente()">
                                Siguiente
                            </button>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-finalizar" data-bs-toggle="modal" data-bs-target="#modalFinalizar">
                        Finalizar Examen
                    </button>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalFinalizar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content modal-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title">Confirmar Entrega</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="confirm-icon mb-3">!</div>
                    <p class="fs-5">¿Está seguro que desea finalizar el examen?</p>
                    <p class="text-muted">Ha respondido <b>2 de 5</b> preguntas. Las preguntas sin responder quedarán en blanco.</p>
                </div>
                <div class="modal-footer modal-footer-custom justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continuar examen</button>
                    <form action="./index.php" method="post">
                        <button type="submit" class="btn btn-finalizar">Sí, entregar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/examen.js"></script>
</body>
</html>
