document.addEventListener("DOMContentLoaded", function () {

    let tiempoTotal = 90 * 60;
    let tiempoRestante = tiempoTotal;
    let elementoTimer = document.getElementById("tiempoRestante");
    let timerBox = document.getElementById("temporizador");

    let intervalo = setInterval(function () {
        tiempoRestante--;

        if (tiempoRestante <= 0) {
            clearInterval(intervalo);
            document.querySelector("form[action='./index.php']").submit();
        }

        let minutos = Math.floor(tiempoRestante / 60);
        let segundos = tiempoRestante % 60;

        let minutosStr = minutos < 10 ? "0" + minutos : minutos;
        let segundosStr = segundos < 10 ? "0" + segundos : segundos;

        elementoTimer.innerText = minutosStr + ":" + segundosStr;

        if (tiempoRestante <= 300) {
            timerBox.classList.add("urgente");
        }
    }, 1000);

    let preguntaActual = 1;
    let totalPreguntas = 5;

    window.irPregunta = function (num) {
        preguntaActual = num;
        document.getElementById("numPreguntaActual").innerText = num;

        let botones = document.querySelectorAll(".btn-nav-pregunta");
        for (let i = 0; i < botones.length; i++) {
            botones[i].classList.remove("activa");
        }
        botones[num - 1].classList.add("activa");

        let btnAnterior = document.getElementById("btnAnterior");
        let btnSiguiente = document.getElementById("btnSiguiente");

        if (preguntaActual == 1) {
            btnAnterior.disabled = true;
        } else {
            btnAnterior.disabled = false;
        }

        if (preguntaActual == totalPreguntas) {
            btnSiguiente.innerText = "Finalizar";
        } else {
            btnSiguiente.innerText = "Siguiente";
        }
    };

    window.preguntaSiguiente = function () {
        let radios = document.querySelectorAll("input[name='respuesta']");
        let respondida = false;
        for (let i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                respondida = true;
            }
        }
        if (respondida) {
            let botones = document.querySelectorAll(".btn-nav-pregunta");
            botones[preguntaActual - 1].classList.add("respondida");
            botones[preguntaActual - 1].classList.remove("activa");
        }
        if (preguntaActual < totalPreguntas) {
            irPregunta(preguntaActual + 1);
        }
    };

    window.preguntaAnterior = function () {
        if (preguntaActual > 1) {
            irPregunta(preguntaActual - 1);
        }
    };
});