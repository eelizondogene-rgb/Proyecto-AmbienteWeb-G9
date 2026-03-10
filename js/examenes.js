document.addEventListener("DOMContentLoaded", function () {

    let buscador = document.getElementById("buscador");
    let filtroEstado = document.getElementById("filtroEstado");
    let filas = document.querySelectorAll("#cuerpoTabla tr");
    let msgSinResultados = document.getElementById("msgSinResultados");

    function filtrarTabla() {
        let textoBusqueda = buscador.value.toLowerCase();
        let estadoSeleccionado = filtroEstado.value.toLowerCase();
        let visibles = 0;

        for (let i = 0; i < filas.length; i++) {
            let nombreExamen = filas[i].querySelector("td:nth-child(2)").innerText.toLowerCase();
            let estadoBadge = filas[i].querySelector(".badge").innerText.toLowerCase();

            let coincideBusqueda = nombreExamen.includes(textoBusqueda);
            let coincideEstado = estadoSeleccionado == "" || estadoBadge.includes(estadoSeleccionado);

            if (coincideBusqueda && coincideEstado) {
                filas[i].style.display = "";
                visibles++;
            } else {
                filas[i].style.display = "none";
            }
        }

        if (visibles == 0) {
            msgSinResultados.style.display = "block";
        } else {
            msgSinResultados.style.display = "none";
        }
    }

    buscador.addEventListener("input", filtrarTabla);
    filtroEstado.addEventListener("change", filtrarTabla);

    let formExamen = document.getElementById("formExamen");
    let nombreExamen = document.getElementById("nombreExamen");
    let msgNombreExamen = document.getElementById("msgNombreExamen");

    formExamen.addEventListener("submit", function (event) {
        if (nombreExamen.value == "") {
            nombreExamen.style.borderColor = "red";
            msgNombreExamen.style.display = "block";
            event.preventDefault();
        } else {
            nombreExamen.style.borderColor = "";
            msgNombreExamen.style.display = "none";
        }
    });
});