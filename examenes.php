<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExamWeb - Gestión de Exámenes</title>
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
                        <a class="nav-link nav-link-custom active" href="./examenes.php">Exámenes</a>
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
                <h2 class="page-title">Gestión de Exámenes</h2>
                <p class="page-subtitle">Crea, edita y administra los exámenes del sistema</p>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalNuevoExamen">
                    + Nuevo Examen
                </button>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <input type="text" class="form-control input-custom" id="buscador" placeholder="Buscar examen...">
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mt-2 mt-md-0">
                <select class="form-select input-custom" id="filtroEstado">
                    <option value="">Todos los estados</option>
                    <option value="activo">Activo</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="finalizado">Finalizado</option>
                </select>
            </div>
        </div>

        <div class="panel-card">
            <div class="panel-body">
                <table class="table table-custom" id="tablaExamenes">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre del Examen</th>
                            <th>Categoría</th>
                            <th>Preguntas</th>
                            <th>Duración</th>
                            <th>Fecha Disponible</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTabla">
                        <tr>
                            <td>1</td>
                            <td>Admisión Ingeniería 2025</td>
                            <td>Ciencias</td>
                            <td>40</td>
                            <td>90 min</td>
                            <td>10/03/2025</td>
                            <td><span class="badge badge-activo">Activo</span></td>
                            <td>
                                <button class="btn btn-xs btn-accion me-1">Editar</button>
                                <button class="btn btn-xs btn-eliminar">Eliminar</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Prueba Matemáticas Básicas</td>
                            <td>Matemáticas</td>
                            <td>25</td>
                            <td>60 min</td>
                            <td>08/03/2025</td>
                            <td><span class="badge badge-pendiente">Pendiente</span></td>
                            <td>
                                <button class="btn btn-xs btn-accion me-1">Editar</button>
                                <button class="btn btn-xs btn-eliminar">Eliminar</button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Examen Diagnóstico General</td>
                            <td>General</td>
                            <td>50</td>
                            <td>120 min</td>
                            <td>05/03/2025</td>
                            <td><span class="badge badge-finalizado">Finalizado</span></td>
                            <td>
                                <button class="btn btn-xs btn-accion me-1">Editar</button>
                                <button class="btn btn-xs btn-eliminar">Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <span id="msgSinResultados" style="display:none;" class="text-muted">No se encontraron exámenes.</span>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalNuevoExamen" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title">Crear Nuevo Examen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="./examenes.php" method="post" id="formExamen">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label label-custom">Nombre del Examen</label>
                                <input type="text" class="form-control input-custom" name="nombre" id="nombreExamen" placeholder="Ej: Admisión Ingeniería 2025" required>
                                <span id="msgNombreExamen" class="text-danger small" style="display:none;">Campo requerido</span>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label label-custom">Categoría</label>
                                <select class="form-select input-custom" name="categoria">
                                    <option value="">Seleccione...</option>
                                    <option value="ciencias">Ciencias</option>
                                    <option value="matematicas">Matemáticas</option>
                                    <option value="lenguaje">Lenguaje</option>
                                    <option value="general">General</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label label-custom">Duración (minutos)</label>
                                <input type="number" class="form-control input-custom" name="duracion" placeholder="90" min="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label label-custom">Fecha de Inicio</label>
                                <input type="date" class="form-control input-custom" name="fecha_inicio">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label label-custom">Fecha de Cierre</label>
                                <input type="date" class="form-control input-custom" name="fecha_cierre">
                            </div>
                            <div class="col-12">
                                <label class="form-label label-custom">Descripción</label>
                                <textarea class="form-control input-custom" name="descripcion" rows="3" placeholder="Descripción del examen..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formExamen" class="btn btn-primary-custom">Guardar Examen</button>
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
    <script src="./js/examenes.js"></script>
</body>
</html>









































