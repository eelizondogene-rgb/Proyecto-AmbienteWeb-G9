<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ExamWeb - Mis Resultados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body class="dashboard-page">
    <!-- navbar igual -->
    <main class="container py-4">
        <h2 class="page-title">Mis Resultados</h2>
        <div class="panel-card mt-4">
            <div class="panel-body">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Examen</th>
                            <th>Fecha</th>
                            <th>Puntaje</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Admisión Ingeniería 2025</td>
                            <td>10/03/2025</td>
                            <td>85/100</td>
                            <td><span class="badge badge-activo">Aprobado</span></td>
                            <td><button class="btn btn-xs btn-accion">Ver detalles</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>