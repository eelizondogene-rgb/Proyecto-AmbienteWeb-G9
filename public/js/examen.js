$(function () {

    // ─── Temporizador ─────────────────────────────────────────────────
    let tiempoRestante = duracionMin * 60;
    let elementoTimer  = $("#tiempoRestante");
    let timerBox       = $("#temporizador");

    let intervalo = setInterval(function () {
        tiempoRestante--;

        if (tiempoRestante <= 0) {
            clearInterval(intervalo);
            $("form[action='index.php?action=examen_finalizar']").submit();
        }

        let minutos  = Math.floor(tiempoRestante / 60);
        let segundos = tiempoRestante % 60;
        let mm = minutos  < 10 ? "0" + minutos  : minutos;
        let ss = segundos < 10 ? "0" + segundos : segundos;

        elementoTimer.text(mm + ":" + ss);

        if (tiempoRestante <= 300) {
            timerBox.addClass("urgente");
        }
    }, 1000);

    // ─── Estado local de respuestas ────────────────────────────────────
    let respuestas     = new Array(totalPreguntas).fill(null);
    let preguntaActual = 1;

    // ─── Renderizar pregunta ───────────────────────────────────────────
    function mostrarPregunta(num) {
        let p = preguntas[num - 1];
        if (!p) return;

        $("#numPreguntaActual").text(num);
        $("#badgeCategoria").text(p.categoria_nombre || "General");
        $("#textoPregunta").text(p.texto);

        let opciones = "";
        let letras   = ["a", "b", "c", "d"];
        let textos   = [p.opcion_a, p.opcion_b, p.opcion_c, p.opcion_d];

        letras.forEach(function (letra, i) {
            if (!textos[i]) return;
            let checked = (respuestas[num - 1] === letra) ? "checked" : "";
            opciones += `
                <label class="opcion-label">
                    <input type="radio" name="respuesta" value="${letra}" ${checked}>
                    ${textos[i]}
                </label>`;
        });

        $("#opcionesContainer").html(opciones);

        $("#btnAnterior").prop("disabled", num === 1);
        $("#btnSiguiente").text(num === totalPreguntas ? "Finalizar" : "Siguiente");

        $(".btn-nav-pregunta").removeClass("activa");
        $(".btn-nav-pregunta").eq(num - 1).addClass("activa");
    }

    // ─── Guardar respuesta vía AJAX ────────────────────────────────────
    function guardarRespuesta(num, respuesta) {
        let idPregunta = preguntas[num - 1].id_pregunta;

        $.post(urlBase,
            {
                option:      "guardar_respuesta",
                id_sesion:   idSesion,
                id_pregunta: idPregunta,
                respuesta:   respuesta
            },
            function (data) {
                data = JSON.parse(data);
                if (data.response === "00") {
                    $(".btn-nav-pregunta").eq(num - 1)
                        .addClass("respondida")
                        .removeClass("activa");

                    let respondidas = respuestas.filter(r => r !== null).length;
                    $("#progresoContador").text(respondidas);
                    let pct = Math.round((respondidas / totalPreguntas) * 100);
                    $("#progresoBarra").css("width", pct + "%");
                }
            }
        );
    }

    // ─── Navegación ───────────────────────────────────────────────────
    window.irPregunta = function (num) {
        preguntaActual = num;
        mostrarPregunta(num);
    };

    window.preguntaSiguiente = function () {
        let seleccionada = $("input[name='respuesta']:checked").val();

        if (seleccionada) {
            respuestas[preguntaActual - 1] = seleccionada;
            guardarRespuesta(preguntaActual, seleccionada);
        }

        if (preguntaActual < totalPreguntas) {
            preguntaActual++;
            mostrarPregunta(preguntaActual);
        }
    };

    window.preguntaAnterior = function () {
        if (preguntaActual > 1) {
            preguntaActual--;
            mostrarPregunta(preguntaActual);
        }
    };

    // ─── Cargar primera pregunta al abrir ─────────────────────────────
    mostrarPregunta(1);
});