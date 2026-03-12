<?php
session_start();

$usuario = $_POST["usuario"] ?? "";
$clave = $_POST["clave"] ?? "";

if ($usuario == "admin" && $clave == "1234") {
    $_SESSION["nombre"] = "Administrador";
    $_SESSION["rol"] = "admin";
    header("Location: ../dashboard_admin.php");
} elseif ($usuario == "estudiante" && $clave == "1234") {
    $_SESSION["nombre"] = "Estudiante Demo";
    $_SESSION["rol"] = "estudiante";
    header("Location: ../bienvenida_estudiante.php");
} else {
    $_SESSION["error"] = "Usuario o contraseña incorrectos.";
    header("Location: ../index.php");
}