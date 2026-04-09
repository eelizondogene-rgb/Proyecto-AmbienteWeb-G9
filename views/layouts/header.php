<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseUrl = '/Proyecto-AmbienteWeb-G9/public/';

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?action=login");
    exit;
}

$userRole = $_SESSION['usuario']['rol'] ?? 'estudiante';
$userEmail = htmlspecialchars($_SESSION['usuario']['email'] ?? 'Usuario');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExamWeb - <?php echo $pageTitle ?? 'Sistema'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>css/main.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>css/dashboard.css">
    <?php if (isset($additionalCss)): ?>
        <?php foreach ($additionalCss as $css): ?>
            <link rel="stylesheet" href="<?php echo $baseUrl; ?>css/<?php echo $css; ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="dashboard-page">
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand brand-nav" href="index.php?action=<?php echo $userRole === 'admin' ? 'admin_dashboard' : 'estudiante_bienvenida'; ?>">
                <span class="logo-nav">EW</span> ExamWeb
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php if ($userRole === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom <?php echo $activePage === 'dashboard' ? 'active' : ''; ?>" href="index.php?action=admin_dashboard">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom <?php echo $activePage === 'examenes' ? 'active' : ''; ?>" href="index.php?action=admin_examenes">Exámenes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom <?php echo $activePage === 'preguntas' ? 'active' : ''; ?>" href="index.php?action=admin_examenes">Preguntas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom <?php echo $activePage === 'codigos' ? 'active' : ''; ?>" href="index.php?action=admin_codigos">Códigos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom <?php echo $activePage === 'resultados' ? 'active' : ''; ?>" href="index.php?action=admin_resultados">Resultados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom <?php echo $activePage === 'usuarios' ? 'active' : ''; ?>" href="index.php?action=admin_usuarios">Usuarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom <?php echo $activePage === 'reportes' ? 'active' : ''; ?>" href="index.php?action=admin_reportes">Reportes</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom <?php echo $activePage === 'bienvenida' ? 'active' : ''; ?>" href="index.php?action=estudiante_bienvenida">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom <?php echo $activePage === 'perfil' ? 'active' : ''; ?>" href="index.php?action=estudiante_perfil">Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom <?php echo $activePage === 'historial' ? 'active' : ''; ?>" href="index.php?action=estudiante_historial">Historial</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom <?php echo $activePage === 'resultados' ? 'active' : ''; ?>" href="index.php?action=estudiante_resultados">Mis Resultados</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <span class="usuario-nav"><b><?php echo $userEmail; ?></b></span>
                    <a href="index.php?action=logout" class="btn btn-logout">Salir</a>
                </div>
            </div>
        </div>
    </nav>
    <main class="container-fluid py-4 px-4">