<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExamWeb - Códigos de Acceso</title>
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
                        <a class="nav-link nav-link-custom active" href="./codigos.php">Códigos de Acceso</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Otras opciones
                        </a>
                        <ul class="dropdown-menu dropdown-custom">
                            <li><a class="dropdown-item" href="./examen_estudiante.php">Vista Estudiante</a></li>
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
                <h2 class="page-title">Códigos de Acceso</h2>
                <p class="page-subtitle">Genera y administra los códigos para los aspirantes</p>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalGenerarCodigos">
                    Generar Códigos
                </button>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="stat-card stat-blue">
                    <div class="stat-number">30</div>
                    <div class="stat-label">Códigos Generados</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="stat-card stat-green">
                    <div class="stat-number">18</div>
                    <div class="stat-label">Códigos Usados</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12">
                <div class="stat-card stat-orange">
                    <div class="stat-number">12</div>
                    <div class="stat-label">Códigos Disponibles</div>
                </div>
            </div>
        </div>

        <div class="panel-card">
            <div class="panel-header">
                <h5 class="panel-title">Listado de Códigos</h5>
            </div>
            <div class="panel-body">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Examen Asignado</th>
                            <th>Generado</th>
                            <th>Vencimiento</th>
                            <th>Usos Máx.</th>
                            <th>Usos Actuales</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code class="codigo-tag">ADM-2025-X1</code></td>
                            <td>Admisión Ingeniería 2025</td>
                            <td>01/03/2025</td>
                            <td>15/03/2025</td>
                            <td>1</td>
                            <td>1</td>
                            <td><span class="badge badge-finalizado">Usado</span></td>
                        </tr>
                        <tr>
                            <td><code class="codigo-tag">ADM-2025-X2</code></td>
                            <td>Admisión Ingeniería 2025</td>
                            <td>01/03/2025</td>
                            <td>15/03/2025</td>
                            <td>1</td>
                            <td>0</td>
                            <td><span class="badge badge-activo">Disponible</span></td>
                        </tr>
                        <tr>
                            <td><code class="codigo-tag">MAT-2025-B7</code></td>
                            <td>Prueba Matemáticas Básicas</td>
                            <td>05/03/2025</td>
                            <td>20/03/2025</td>
                            <td>1</td>
                            <td>0</td>
                            <td><span class="badge badge-activo">Disponible</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalGenerarCodigos" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content modal-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title">Generar Códigos de Acceso</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="./codigos.php" method="post" id="formCodigos">
                        <div class="mb-3">
                            <label class="form-label label-custom">Examen</label>
                            <select class="form-select input-custom" name="examen_id" required>
                                <option value="">Seleccione un examen...</option>
                                <option value="1">Admisión Ingeniería 2025</option>
                                <option value="2">Prueba Matemáticas Básicas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label label-custom">Cantidad de Códigos</label>
                            <input type="number" class="form-control input-custom" name="cantidad" placeholder="Ej: 30" min="1" max="200" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label label-custom">Fecha de Vencimiento</label>
                            <input type="date" class="form-control input-custom" name="vencimiento" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label label-custom">Usos por Código</label>
                            <input type="number" class="form-control input-custom" name="usos_max" value="1" min="1">
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formCodigos" class="btn btn-primary-custom">Generar</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-custom">
        <div class="container-fluid">
            <p class="mb-0">ExamWeb &copy; 2025 - Sistema de Gestión de Exámenes de Admisión</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/dashboard.js"></script>
</body>
</html>