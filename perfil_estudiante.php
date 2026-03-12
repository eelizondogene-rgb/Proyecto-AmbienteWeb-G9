<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExamWeb - Perfil</title>
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
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="./resultados_examen.php">Resultados</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">Otras opciones</a>
                        <ul class="dropdown-menu dropdown-custom">
                            <li><a class="dropdown-item" href="./examen_estudiante.php">Vista Estudiante</a></li>
                            <li><a class="dropdown-item active" href="./perfil_estudiante.php">Perfil</a></li>
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
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="page-title">Perfil</h2>
                <p class="page-subtitle">Información del estudiante</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="panel-card">
                    <div class="panel-header"><h5 class="panel-title">Información Personal</h5></div>
                    <div class="panel-body text-center">
                        <div class="logo-icon mx-auto mb-3" style="width:80px;height:80px;font-size:32px;">MP</div>
                        <h4>María Pérez González</h4>
                        <p class="text-muted">Aspirante</p>
                        <hr>
                        <div class="text-start">
                            <p><strong>Email:</strong> maria.perez@example.com</p>
                            <p><strong>ID:</strong> 1-2345-6789</p>
                            <p><strong>Tel:</strong> 8888-7777</p>
                            <p><strong>Código:</strong> <code class="codigo-tag">ADM-2025-X1</code></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="row g-3 mb-4">
                    <div class="col-md-4"><div class="stat-card stat-blue p-3"><div class="stat-number h3 mb-0">3</div><div>Realizados</div></div></div>
                    <div class="col-md-4"><div class="stat-card stat-green p-3"><div class="stat-number h3 mb-0">85.7</div><div>Promedio</div></div></div>
                    <div class="col-md-4"><div class="stat-card stat-orange p-3"><div class="stat-number h3 mb-0">2</div><div>Aprobados</div></div></div>
                </div>

                <div class="panel-card">
                    <div class="panel-header"><h5 class="panel-title">Historial</h5></div>
                    <div class="panel-body">
                        <table class="table table-custom">
                            <thead><tr><th>Examen</th><th>Fecha</th><th>Puntaje</th><th>Estado</th></tr></thead>
                            <tbody>
                                <tr><td>Ingeniería 2025</td><td>10/03</td><td>85/100</td><td><span class="badge badge-activo">Aprobado</span></td></tr>
                                <tr><td>Matemáticas</td><td>08/03</td><td>45/60</td><td><span class="badge badge-pendiente">Revisión</span></td></tr>
                                <tr><td>Diagnóstico</td><td>05/03</td><td>110/120</td><td><span class="badge badge-activo">Aprobado</span></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer-custom"><div class="container-fluid"><p class="mb-0">ExamWeb &copy; 2025</p></div></footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/dashboard.js"></script>
</body>
</html>