<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExamWeb - Resultados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body class="dashboard-page">

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand brand-nav" href="./dashboard_admin.php"><span class="logo-nav">EW</span> ExamWeb</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="./dashboard_admin.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="./examenes.php">Exámenes</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="./codigos.php">Códigos</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom active" href="./resultados_examen.php">Resultados</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Otras opciones</a>
                        <ul class="dropdown-menu dropdown-custom">
                            <li><a class="dropdown-item" href="./examen_estudiante.php">Vista Estudiante</a></li>
                            <li><a class="dropdown-item" href="./perfil_estudiante.php">Perfil Estudiante</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <?php if (!empty($_SESSION["nombre"])): ?>
                        <span class="usuario-nav"><b><?php echo $_SESSION["nombre"]; ?></b></span>
                    <?php endif; ?>
                    <a href="./auth/logout.php" class="btn btn-logout">Salir</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="container-fluid py-4 px-4">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="page-title">Resultados</h2>
                <p class="page-subtitle">Desempeño de aspirantes</p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6"><div class="stat-card stat-blue"><div class="stat-number">45</div><div class="stat-label">Calificados</div></div></div>
            <div class="col-lg-3 col-md-6"><div class="stat-card stat-green"><div class="stat-number">82.5</div><div class="stat-label">Promedio</div></div></div>
            <div class="col-lg-3 col-md-6"><div class="stat-card stat-orange"><div class="stat-number">12</div><div class="stat-label">Pendientes</div></div></div>
            <div class="col-lg-3 col-md-6"><div class="stat-card stat-red"><div class="stat-number">33</div><div class="stat-label">Aprobados</div></div></div>
        </div>

        <div class="panel-card">
            <div class="panel-header"><h5 class="panel-title">Resultados por Aspirante</h5></div>
            <div class="panel-body">
                <table class="table table-custom">
                    <thead><tr><th>#</th><th>Aspirante</th><th>Examen</th><th>Puntaje</th><th>Estado</th><th>Acción</th></tr></thead>
                    <tbody>
                        <tr><td>1</td><td>María Pérez</td><td>Ingeniería 2025</td><td>85/100</td><td><span class="badge badge-activo">Aprobado</span></td><td><a href="./perfil_estudiante.php?id=1" class="btn btn-xs btn-accion">Ver</a></td></tr>
                        <tr><td>2</td><td>Carlos Rodríguez</td><td>Ingeniería 2025</td><td>92/100</td><td><span class="badge badge-activo">Aprobado</span></td><td><a href="./perfil_estudiante.php?id=2" class="btn btn-xs btn-accion">Ver</a></td></tr>
                        <tr><td>3</td><td>Ana Jiménez</td><td>Matemáticas</td><td>45/60</td><td><span class="badge badge-pendiente">Revisión</span></td><td><a href="./perfil_estudiante.php?id=3" class="btn btn-xs btn-accion">Ver</a></td></tr>
                        <tr><td>4</td><td>Andrés Castro</td><td>Diagnóstico</td><td>110/120</td><td><span class="badge badge-activo">Aprobado</span></td><td><a href="./perfil_estudiante.php?id=4" class="btn btn-xs btn-accion">Ver</a></td></tr>
                        <tr><td>5</td><td>Laura Chaves</td><td>Ingeniería 2025</td><td>58/100</td><td><span class="badge badge-finalizado">No aprobado</span></td><td><a href="./perfil_estudiante.php?id=5" class="btn btn-xs btn-accion">Ver</a></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="footer-custom"><div class="container-fluid"><p class="mb-0">ExamWeb &copy; 2025</p></div></footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/dashboard.js"></script>
</body>
</html>