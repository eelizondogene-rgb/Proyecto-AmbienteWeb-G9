<?php
$baseUrl = '/Proyecto-AmbienteWeb-G9/public/';
$examen = $examen ?? null;
$preguntas = $preguntas ?? [];
$totalPreguntas = count($preguntas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExamWeb - <?php echo htmlspecialchars($examen['nombre'] ?? 'Examen'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>css/main.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>css/examen.css">
</head>
<body class="examen-page">

<nav class="navbar navbar-exam">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <span class="brand-nav"><span class="logo-nav">EW</span> ExamWeb</span>
        <span class="exam-title-nav"><?php echo htmlspecialchars($examen['nombre'] ?? 'Examen'); ?></span>
        <div class="d-flex align-items-center gap-3">
            <div class="timer-box" id="temporizador">
                <span id="tiempoRestante"><?php echo $examen['duracion_minutos'] ?? 90; ?>:00</span>
            </div>
            <?php if (!empty($_SESSION["usuario"])): ?>
                <span class="usuario-nav"><b><?php echo htmlspecialchars($_SESSION["usuario"]['email']); ?></b></span>
            <?php endif; ?>
            <a href="index.php?action=logout" class="btn btn-logout">Salir</a>
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
                        <?php for ($i = 1; $i <= $totalPreguntas; $i++): ?>
                            <button class="btn btn-nav-pregunta <?php echo $i == 1 ? 'activa' : ''; ?>" data-pregunta="<?php echo $i; ?>"><?php echo $i; ?></button>
                        <?php endfor; ?>
                    </div>
                    <div class="mt-3">
                        <div class="leyenda-item"><span class="dot dot-activa"></span> Actual</div>
                        <div class="leyenda-item"><span class="dot dot-respondida"></span> Respondida</div>
                        <div class="leyenda-item"><span class="dot dot-pendiente"></span> Pendiente</div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">Progreso: <b id="progresoContador">0</b>/<?php echo $totalPreguntas; ?></small>
                        <div class="progress mt-1" style="height:8px;">
                            <div class="progress-bar progress-bar-custom" id="progresoBarra" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-8 col-12">
            <div class="panel-card">
                <div class="panel-header d-flex justify-content-between align-items-center">
                    <h5 class="panel-title mb-0">Pregunta <span id="numPreguntaActual">1</span> de <?php echo $totalPreguntas; ?></h5>
                    <span class="badge badge-categoria" id="badgeCategoria">-</span>
                </div>
                <div class="panel-body">
                    <p class="pregunta-texto" id="textoPregunta"></p>
                    <div id="opcionesContainer"></div>
                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-secondary" id="btnAnterior" disabled>Anterior</button>
                        <button class="btn btn-primary-custom" id="btnSiguiente">Siguiente</button>
                    </div>
                </div>
            </div>
            <div class="text-end mt-3">
                <button class="btn btn-finalizar" data-bs-toggle="modal" data-bs-target="#modalFinalizar">Finalizar Examen</button>
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
                <p class="text-muted">Las preguntas sin responder quedarán en blanco.</p>
            </div>
            <div class="modal-footer modal-footer-custom justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continuar examen</button>
                <form action="index.php?action=examen_finalizar" method="post">
                    <input type="hidden" name="id_examen" value="<?php echo $examen['id_examen'] ?? 0; ?>">
                    <input type="hidden" name="id_sesion" value="<?php echo $_SESSION['id_sesion'] ?? 0; ?>">
                    <button type="submit" class="btn btn-finalizar">Sí, entregar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var totalPreguntas = <?php echo $totalPreguntas; ?>;
    var duracionMin = <?php echo $examen['duracion_minutos'] ?? 90; ?>;
    var idSesion = <?php echo $_SESSION['id_sesion'] ?? 0; ?>;
    var urlBase = "<?php echo $baseUrl; ?>index.php";
    var preguntas = <?php echo json_encode(array_values($preguntas)); ?>;
    
    console.log("=== DEBUG ===");
    console.log("urlBase:", urlBase);
    console.log("idSesion:", idSesion);
    console.log("totalPreguntas:", totalPreguntas);
    console.log("preguntas:", preguntas);
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    if (!preguntas || preguntas.length === 0) {
        $("#textoPregunta").text("No hay preguntas disponibles para este examen.");
        return;
    }

    // Temporizador
    let tiempoRestante = duracionMin * 60;
    let elementoTimer = $("#tiempoRestante");
    let timerBox = $("#temporizador");

    let intervalo = setInterval(function () {
        tiempoRestante--;
        if (tiempoRestante <= 0) {
            clearInterval(intervalo);
            $("form[action='index.php?action=examen_finalizar']").submit();
        }
        let minutos = Math.floor(tiempoRestante / 60);
        let segundos = tiempoRestante % 60;
        let mm = minutos < 10 ? "0" + minutos : minutos;
        let ss = segundos < 10 ? "0" + segundos : segundos;
        elementoTimer.text(mm + ":" + ss);
        if (tiempoRestante <= 300) {
            timerBox.addClass("urgente");
        }
    }, 1000);

    let respuestas = new Array(totalPreguntas).fill(null);
    let preguntaActual = 1;

    function mostrarPregunta(num) {
        let p = preguntas[num - 1];
        if (!p) return;
        
        $("#numPreguntaActual").text(num);
        $("#badgeCategoria").text(p.categoria_nombre || "General");
        $("#textoPregunta").text(p.texto);
        
        let opciones = "";
        let letras = ["a", "b", "c", "d"];
        let textos = [p.opcion_a, p.opcion_b, p.opcion_c, p.opcion_d];
        
        for (let i = 0; i < letras.length; i++) {
            if (textos[i] && textos[i].trim() !== "") {
                let checked = (respuestas[num - 1] === letras[i]) ? "checked" : "";
                opciones += '<label class="opcion-label">' +
                    '<input type="radio" name="respuesta" value="' + letras[i] + '" ' + checked + '>' +
                    textos[i] +
                    '</label>';
            }
        }
        
        $("#opcionesContainer").html(opciones);
        $("#btnAnterior").prop("disabled", num === 1);
        $("#btnSiguiente").text(num === totalPreguntas ? "Finalizar" : "Siguiente");
        
        $(".btn-nav-pregunta").removeClass("activa");
        $(".btn-nav-pregunta").eq(num - 1).addClass("activa");
    }

    function guardarRespuesta(num, respuesta) {
        let idPregunta = preguntas[num - 1].id_pregunta;
        
        console.log("Guardando respuesta:", {id_sesion: idSesion, id_pregunta: idPregunta, respuesta: respuesta});
        
        $.ajax({
            url: urlBase,
            type: "POST",
            data: {
                action: "examen_guardar_respuesta",
                id_sesion: idSesion,
                id_pregunta: idPregunta,
                respuesta: respuesta
            },
            dataType: "json",
            success: function(data) {
                console.log("Respuesta servidor:", data);
                if (data.response === "00") {
                    $(".btn-nav-pregunta").eq(num - 1).addClass("respondida");
                    
                    let respondidas = 0;
                    for (let i = 0; i < totalPreguntas; i++) {
                        if (respuestas[i] !== null) respondidas++;
                    }
                    $("#progresoContador").text(respondidas);
                    let pct = Math.round((respondidas / totalPreguntas) * 100);
                    $("#progresoBarra").css("width", pct + "%");
                } else {
                    console.error("Error:", data);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error AJAX:", error);
                console.error("Respuesta:", xhr.responseText);
            }
        });
    }

    $("#btnSiguiente").click(function() {
        let seleccionada = $("input[name='respuesta']:checked").val();
        
        if (seleccionada) {
            respuestas[preguntaActual - 1] = seleccionada;
            guardarRespuesta(preguntaActual, seleccionada);
            
            $(".btn-nav-pregunta").eq(preguntaActual - 1).addClass("respondida");
            
            let respondidas = 0;
            for (let i = 0; i < totalPreguntas; i++) {
                if (respuestas[i] !== null) respondidas++;
            }
            $("#progresoContador").text(respondidas);
            let pct = Math.round((respondidas / totalPreguntas) * 100);
            $("#progresoBarra").css("width", pct + "%");
        }
        
        if (preguntaActual < totalPreguntas) {
            preguntaActual++;
            mostrarPregunta(preguntaActual);
        } else {
            $("#modalFinalizar").modal("show");
        }
    });

    $("#btnAnterior").click(function() {
        if (preguntaActual > 1) {
            preguntaActual--;
            mostrarPregunta(preguntaActual);
        }
    });

    $(".btn-nav-pregunta").click(function() {
        let num = parseInt($(this).data("pregunta"));
        if (!isNaN(num)) {
            preguntaActual = num;
            mostrarPregunta(preguntaActual);
        }
    });

    mostrarPregunta(1);
});
</script>

</body>
</html>