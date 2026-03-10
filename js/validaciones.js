document.addEventListener("DOMContentLoaded", function () {

    let formAcceso = document.querySelector("#formAcceso");
    let campoCodigo = document.getElementById("codigo");
    let msgCodigo = document.getElementById("msgCodigo");

    formAcceso.addEventListener("submit", function (event) {
        event.preventDefault();
        if (campoCodigo.value == "") {
            campoCodigo.style.borderColor = "red";
            msgCodigo.style.display = "block";
        } else {
            campoCodigo.style.borderColor = "";
            msgCodigo.style.display = "none";
            formAcceso.submit();
        }
    });

    let formLogin = document.querySelector("#formLogin");
    let campoUsuario = document.getElementById("usuario");
    let campoClave = document.getElementById("clave");

    formLogin.addEventListener("submit", function (event) {
        let valido = true;
        if (campoUsuario.value == "") {
            campoUsuario.style.borderColor = "red";
            valido = false;
        } else {
            campoUsuario.style.borderColor = "";
        }
        if (campoClave.value == "") {
            campoClave.style.borderColor = "red";
            valido = false;
        } else {
            campoClave.style.borderColor = "";
        }
        if (!valido) {
            event.preventDefault();
        }
    });
});