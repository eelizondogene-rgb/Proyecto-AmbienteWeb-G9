document.addEventListener("DOMContentLoaded", function () {
    let formAcceso = document.querySelector("#formAcceso");
    let campoCodigo = document.getElementById("codigo");
    let msgCodigo = document.getElementById("msgCodigo");

    if (formAcceso) {
        formAcceso.addEventListener("submit", function (event) {
            if (campoCodigo.value == "") {
                campoCodigo.style.borderColor = "red";
                msgCodigo.style.display = "block";
                event.preventDefault();
            } else {
                campoCodigo.style.borderColor = "";
                msgCodigo.style.display = "none";
            }
        });
    }

    let formLogin = document.querySelector("#formLogin");
    let campoUsuario = document.getElementById("email");
    let campoClave = document.getElementById("contraseña");

    if (formLogin) {
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
    }
});