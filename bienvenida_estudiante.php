<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExamWeb - Bienvenido</title>
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
                        <a class="nav-link nav-link-custom" href="./dashboard_admin.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="./examenes.php">Exámenes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="./codigos.php">Códigos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="./resultados_examen.php">Resultados</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                            Estudiante
                        </a>
                        <ul class="dropdown-menu dropdown-custom">
                            <li><a class="dropdown-item active" href="./bienvenida_estudiante.php">Bienvenida</a></li>
                            <li><a class="dropdown-item" href="./examen_estudiante.php">Vista Examen</a></li>
                            <li><a class="dropdown-item" href="./perfil_estudiante.php">Perfil</a></li>
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

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="panel-card text-center">
                    <div class="panel-header">
                        <h5 class="panel-title mb-0">Bienvenido a ExamWeb</h5>
                    </div>
                    <div class="panel-body py-5">
                        <div class="logo-icon mx-auto mb-4" style="width: 100px; height: 100px; font-size: 40px; background: var(--primario);">
                            EW
                        </div>
                        
                        <h2 class="mb-3">¡Hola, <?php echo $_SESSION["nombre"] ?? "Estudiante"; ?>!</h2>
                        <p class="lead mb-4">Has iniciado sesión correctamente en el sistema de exámenes.</p>
                        
                        <div class="row g-4 mt-4">
                            <div class="col-md-6">
                                <div class="stat-card stat-blue p-4">
                                    <div class="stat-number h1 mb-2">1</div>
                                    <div class="stat-label">Examen pendiente</div>
                                    <p class="mt-3 small">Admisión Ingeniería 2025</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stat-card stat-green p-4">
                                    <div class="stat-number h1 mb-2">90</div>
                                    <div class="stat-label">Minutos disponibles</div>
                                    <p class="mt-3 small">Tiempo estimado del examen</p>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4 text-start">
                            <strong>📝 Instrucciones importantes:</strong>
                            <ul class="mb-0 mt-2">
                                <li>El examen tiene una duración de 90 minutos</li>
                                <li>Una vez iniciado, no puedes pausar el tiempo</li>
                                <li>Puedes navegar entre preguntas usando los botones</li>
                                <li>Las preguntas respondidas se marcan en verde</li>
                                <li>Al finalizar, haz clic en "Finalizar Examen"</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-3 mt-4">
                            <a href="./examen_estudiante.php" class="btn btn-primary-custom btn-lg">Comenzar Examen</a>
                            <a href="./perfil_estudiante.php" class="btn btn-secondary-custom">Ver mi perfil</a>
                        </div>
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
</body>
</html>