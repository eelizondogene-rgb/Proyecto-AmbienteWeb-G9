<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExamWeb - Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body class="login-page">
    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-10 col-12">
                <div class="login-card p-5">
                    <div class="text-center mb-4">
                        <div class="logo-icon mb-3">EW</div>
                        <h1 class="brand-title">ExamWeb</h1>
                        <p class="brand-subtitle">Sistema de Gestión de Exámenes</p>
                    </div>

                    <?php if (!empty($_SESSION["error"])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="./auth/auth.php" method="post" id="formLogin">
                        <div class="mb-3">
                            <label for="usuario" class="form-label label-custom">Usuario</label>
                            <input type="text" class="form-control input-custom" id="usuario" name="usuario" placeholder="Ingrese su usuario" required>
                        </div>
                        <div class="mb-4">
                            <label for="clave" class="form-label label-custom">Contraseña</label>
                            <input type="password" class="form-control input-custom" id="clave" name="clave" placeholder="Ingrese su contraseña" required>
                        </div>
                        <button type="submit" class="btn btn-primary-custom w-100 mb-3">Ingresar</button>
                    </form>

                    <hr class="divider">
                    <p class="text-center acceso-text mb-2">¿Tiene código de acceso de examen?</p>
                    <form action="./auth/auth.php" method="post" id="formAcceso">
                        <div class="mb-3">
                            <input type="text" class="form-control input-custom text-center" id="codigo" name="codigo" placeholder="Ingrese código de acceso" maxlength="10">
                            <span id="msgCodigo" class="text-danger small" style="display:none;">El código es requerido</span>
                        </div>
                        <button type="submit" class="btn btn-secondary-custom w-100">Acceder al Examen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/validaciones.js"></script>
</body>
</html>