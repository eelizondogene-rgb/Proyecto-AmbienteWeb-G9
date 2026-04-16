$(function () {
    console.log("=== EXAMEN JS INICIADO ===");
    console.log("idSesion:", idSesion);
    console.log("urlBase:", urlBase);
    console.log("totalPreguntas:", totalPreguntas);

    if (!preguntas || preguntas.length === 0) {
        $("#textoPregunta").text("No hay preguntas disponibles para este examen.");
        return;
    }

    if (idSesion === 0 || idSesion === null) {
        console.error("ERROR: idSesion es 0, no se pueden guardar respuestas");
        alert("Error: No se pudo iniciar la sesión del examen. Recarga la página.");
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
        if (tiempoRestante <= 300) timerBox.addClass("urgente");
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
                opciones += '<label class="opcion-label"><input type="radio" name="respuesta" value="' + letras[i] + '" ' + checked + '>' + textos[i] + '</label>';
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

    console.log("Enviando:", {id_sesion: idSesion, id_pregunta: idPregunta, respuesta: respuesta});

    $.ajax({
        url: urlBase + "?action=examen_guardar_respuesta", // acción en la URL
        type: "POST",
        data: {
            id_sesion: idSesion,
            id_pregunta: idPregunta,
            respuesta: respuesta
        },
        dataType: "json",
        success: function(data) {
            console.log("Respuesta del servidor:", data);
            if (data.response === "00") {
                $(".btn-nav-pregunta").eq(num - 1).addClass("respondida");
                let respondidas = 0;
                for (var i = 0; i < totalPreguntas; i++) if (respuestas[i] !== null) respondidas++;
                $("#progresoContador").text(respondidas);
                $("#progresoBarra").css("width", Math.round((respondidas / totalPreguntas) * 100) + "%");
            } else {
                console.error("Error del servidor:", data.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error AJAX:", error);
            console.error("Respuesta del servidor:", xhr.responseText);
        }
    });
}


    $("#btnSiguiente").off("click").on("click", function() {
        let seleccionada = $("input[name='respuesta']:checked").val();
        if (seleccionada) {
            respuestas[preguntaActual - 1] = seleccionada;
            guardarRespuesta(preguntaActual, seleccionada);
            $(".btn-nav-pregunta").eq(preguntaActual - 1).addClass("respondida");
            let respondidas = 0;
            for (var i = 0; i < totalPreguntas; i++) if (respuestas[i] !== null) respondidas++;
            $("#progresoContador").text(respondidas);
            $("#progresoBarra").css("width", Math.round((respondidas / totalPreguntas) * 100) + "%");
        }
        if (preguntaActual < totalPreguntas) {
            preguntaActual++;
            mostrarPregunta(preguntaActual);
        } else {
            $("#modalFinalizar").modal("show");
        }
    });

    $("#btnAnterior").off("click").on("click", function() {
        if (preguntaActual > 1) {
            preguntaActual--;
            mostrarPregunta(preguntaActual);
        }
    });

    $(".btn-nav-pregunta").off("click").on("click", function() {
        let num = parseInt($(this).data("pregunta"));
        if (!isNaN(num)) {
            preguntaActual = num;
            mostrarPregunta(preguntaActual);
        }
    });

    mostrarPregunta(1);
});