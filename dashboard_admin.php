<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExamWeb - Panel de Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body class="dashboard-page">

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand brand-nav" href="./dashboard_admin.php">
                <span class="logo-nav">EW</span> ExamWeb
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom active" href="./dashboard_admin.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="./examenes.php">Exámenes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="./codigos.php">Códigos de Acceso</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Otras opciones
                        </a>
                        <ul class="dropdown-menu dropdown-custom">
                            <li><a class="dropdown-item" href="./examen_estudiante.php">Vista Estudiante</a></li>
                            <li><a class="dropdown-item" href="./resultados_examen.php">resultados examen</a></li>
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
                <h2 class="page-title">Panel de Administrador</h2>
                <p class="page-subtitle">Resumen general del sistema</p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="stat-card stat-blue">
                    <div class="stat-number">12</div>
                    <div class="stat-label">Exámenes Activos</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="stat-card stat-green">
                    <div class="stat-number">248</div>
                    <div class="stat-label">Aspirantes Registrados</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="stat-card stat-orange">
                    <div class="stat-number">5</div>
                    <div class="stat-label">Sesiones en Curso</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="stat-card stat-red">
                    <div class="stat-number">134</div>
                    <div class="stat-label">Exámenes Completados</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8 col-md-12">
                <div class="panel-card">
                    <div class="panel-header">
                        <h5 class="panel-title">Exámenes Recientes</h5>
                        <a href="./examenes.php" class="btn btn-sm btn-outline-custom">Ver todos</a>
                    </div>
                    <div class="panel-body">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Fecha</th>
                                    <th>Aspirantes</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Admisión Ingeniería 2025</td>
                                    <td>10/03/2025</td>
                                    <td>45</td>
                                    <td><span class="badge badge-activo">Activo</span></td>
                                    <td><a href="./examenes.php?id=1" class="btn btn-xs btn-accion">Ver</a></td>
                                </tr>
                                <tr>
                                    <td>Prueba Matemáticas Básicas</td>
                                    <td>08/03/2025</td>
                                    <td>30</td>
                                    <td><span class="badge badge-pendiente">Pendiente</span></td>
                                    <td><a href="./examenes.php?id=2" class="btn btn-xs btn-accion">Ver</a></td>
                                </tr>
                                <tr>
                                    <td>Examen Diagnóstico General</td>
                                    <td>05/03/2025</td>
                                    <td>78</td>
                                    <td><span class="badge badge-finalizado">Finalizado</span></td>
                                    <td><a href="./examenes.php?id=3" class="btn btn-xs btn-accion">Ver</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="panel-card">
                    <div class="panel-header">
                        <h5 class="panel-title">Acciones Rápidas</h5>
                    </div>
                    <div class="panel-body d-flex flex-column gap-3">
                        <a href="./examenes.php" class="btn btn-accion-rapida">Crear Nuevo Examen</a>
                        <a href="./examenes.php" class="btn btn-accion-rapida">Ver Exámenes</a>
                        <a href="./codigos.php" class="btn btn-accion-rapida">Generar Códigos de Acceso</a>
                        <a href="./resultados_examen.php" class="btn btn-accion-rapida">Ver Resultados</a>
                        <a href="./perfil_estudiante.php" class="btn btn-accion-rapida">Ver Perfil Estudiante</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer-custom">
        <div class="container-fluid">
            <p class="mb-0">ExamWeb &copy; 2025 - Sistema de Gestión de Exámenes de Admisión</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/dashboard.js"></script>
</body>
</html>