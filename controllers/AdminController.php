<?php

class AdminController
{
    private $db;
    private $examenModel;
    private $preguntaModel;
    private $codigoModel;
    private $resultadoModel;
    private $usuarioModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->examenModel = new Examen($db);
        $this->preguntaModel = new Pregunta($db);
        $this->codigoModel = new CodigoAcceso($db);
        $this->resultadoModel = new ResultadoExamen($db);
        $this->usuarioModel = new Usuario($db);
        
        $auth = new AuthController($db);
        $auth->verificarRol('admin');
    }

    public function dashboard()
    {
        $pageTitle = "Panel de Administrador";
        $activePage = "dashboard";
        
        $totalExamenes = $this->examenModel->getTotal();
        $totalEstudiantes = $this->usuarioModel->getTotalEstudiantes();
        $totalCodigos = $this->codigoModel->getTotal();
        $totalResultados = $this->resultadoModel->getTotal();
        $promedioGeneral = $this->resultadoModel->getPromedioGeneral();
        $examenesRecientes = $this->examenModel->getRecientes(5);
        
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/dashboard.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function examenes()
    {
        $pageTitle = "Gestión de Exámenes";
        $activePage = "examenes";
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'crear':
                        $this->crearExamen();
                        break;
                    case 'editar':
                        $this->editarExamen();
                        break;
                    case 'eliminar':
                        $this->eliminarExamen();
                        break;
                    case 'cambiar_estado':
                        $this->cambiarEstadoExamen();
                        break;
                }
            }
        }
        
        $examenes = $this->examenModel->getAll();
        
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/examenes.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    private function crearExamen()
    {
        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'duracion_minutos' => $_POST['duracion_minutos'] ?? 60,
            'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
            'fecha_cierre' => $_POST['fecha_cierre'] ?? null,
            'estado' => $_POST['estado'] ?? 'borrador',
            'creado_por' => $_SESSION['usuario']['id_usuario']
        ];
        
        if ($this->examenModel->crear($data)) {
            $_SESSION['success'] = "Examen creado correctamente";
        } else {
            $_SESSION['error'] = "Error al crear el examen";
        }
        header("Location: index.php?action=admin_examenes");
        exit;
    }

    private function editarExamen()
    {
        $id = $_POST['id_examen'] ?? 0;
        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'duracion_minutos' => $_POST['duracion_minutos'] ?? 60,
            'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
            'fecha_cierre' => $_POST['fecha_cierre'] ?? null,
            'estado' => $_POST['estado'] ?? 'borrador'
        ];
        
        if ($this->examenModel->actualizar($id, $data)) {
            $_SESSION['success'] = "Examen actualizado correctamente";
        } else {
            $_SESSION['error'] = "Error al actualizar el examen";
        }
        header("Location: index.php?action=admin_examenes");
        exit;
    }

    private function eliminarExamen()
    {
        $id = $_POST['id_examen'] ?? 0;
        
        if ($this->examenModel->eliminar($id)) {
            $_SESSION['success'] = "Examen eliminado correctamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el examen";
        }
        header("Location: index.php?action=admin_examenes");
        exit;
    }

    private function cambiarEstadoExamen()
    {
        $id = $_POST['id_examen'] ?? 0;
        $estado = $_POST['estado'] ?? 'borrador';
        
        if ($this->examenModel->cambiarEstado($id, $estado)) {
            $_SESSION['success'] = "Estado del examen actualizado";
        } else {
            $_SESSION['error'] = "Error al cambiar el estado";
        }
        header("Location: index.php?action=admin_examenes");
        exit;
    }

    public function preguntas()
    {
        $pageTitle = "Gestión de Preguntas";
        $activePage = "examenes";
        
        $id_examen = $_GET['id_examen'] ?? 0;
        $examen = $this->examenModel->getById($id_examen);
        
        if (!$examen) {
            header("Location: index.php?action=admin_examenes");
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'crear':
                        $this->crearPregunta($id_examen);
                        break;
                    case 'editar':
                        $this->editarPregunta();
                        break;
                    case 'eliminar':
                        $this->eliminarPregunta();
                        break;
                }
            }
        }
        
        $preguntas = $this->preguntaModel->getByExamen($id_examen);
        $totalPreguntas = $this->preguntaModel->getTotalByExamen($id_examen);
        
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/preguntas.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    private function crearPregunta($id_examen)
    {
        $orden = $this->preguntaModel->getTotalByExamen($id_examen) + 1;
        
        $data = [
            'id_examen' => $id_examen,
            'texto' => $_POST['texto'] ?? '',
            'opcion_a' => $_POST['opcion_a'] ?? '',
            'opcion_b' => $_POST['opcion_b'] ?? '',
            'opcion_c' => $_POST['opcion_c'] ?? '',
            'opcion_d' => $_POST['opcion_d'] ?? '',
            'respuesta_correcta' => $_POST['respuesta_correcta'] ?? '',
            'puntos' => $_POST['puntos'] ?? 1,
            'orden' => $orden
        ];
        
        if ($this->preguntaModel->crear($data)) {
            $_SESSION['success'] = "Pregunta creada correctamente";
        } else {
            $_SESSION['error'] = "Error al crear la pregunta";
        }
        header("Location: index.php?action=admin_preguntas&id_examen=" . $id_examen);
        exit;
    }

    private function editarPregunta()
    {
        $id = $_POST['id_pregunta'] ?? 0;
        $id_examen = $_POST['id_examen'] ?? 0;
        
        $data = [
            'texto' => $_POST['texto'] ?? '',
            'opcion_a' => $_POST['opcion_a'] ?? '',
            'opcion_b' => $_POST['opcion_b'] ?? '',
            'opcion_c' => $_POST['opcion_c'] ?? '',
            'opcion_d' => $_POST['opcion_d'] ?? '',
            'respuesta_correcta' => $_POST['respuesta_correcta'] ?? '',
            'puntos' => $_POST['puntos'] ?? 1,
            'orden' => $_POST['orden'] ?? 0
        ];
        
        if ($this->preguntaModel->actualizar($id, $data)) {
            $_SESSION['success'] = "Pregunta actualizada correctamente";
        } else {
            $_SESSION['error'] = "Error al actualizar la pregunta";
        }
        header("Location: index.php?action=admin_preguntas&id_examen=" . $id_examen);
        exit;
    }

    private function eliminarPregunta()
    {
        $id = $_POST['id_pregunta'] ?? 0;
        $id_examen = $_POST['id_examen'] ?? 0;
        
        if ($this->preguntaModel->eliminar($id)) {
            $_SESSION['success'] = "Pregunta eliminada correctamente";
        } else {
            $_SESSION['error'] = "Error al eliminar la pregunta";
        }
        header("Location: index.php?action=admin_preguntas&id_examen=" . $id_examen);
        exit;
    }

    public function codigos()
    {
        $pageTitle = "Códigos de Acceso";
        $activePage = "codigos";
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'generar':
                        $this->generarCodigos();
                        break;
                    case 'asignar':
                        $this->asignarCodigo();
                        break;
                    case 'eliminar':
                        $this->eliminarCodigo();
                        break;
                    case 'revocar':
                        $this->revocarCodigo();
                        break;
                }
            }
        }
        
        $codigos = $this->codigoModel->getAllWithAsignacion();
        $examenes = $this->examenModel->getAll();
        $estudiantes = $this->usuarioModel->getAllEstudiantes();
        
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/codigos.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    private function generarCodigos()
    {
        $id_examen = $_POST['id_examen'] ?? 0;
        $cantidad = $_POST['cantidad'] ?? 1;
        $usos_max = $_POST['usos_max'] ?? 1;
        $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? null;
        $id_estudiante = $_POST['id_estudiante'] ?? null;
        
        $codigos = $this->codigoModel->generarMultiples($id_examen, $cantidad, $usos_max, $fecha_vencimiento);
        
        if (count($codigos) > 0) {
            $_SESSION['success'] = count($codigos) . " códigos generados correctamente";
            $_SESSION['codigos_generados'] = $codigos;
            
            // Si se seleccionó un estudiante, asignar todos los códigos
            if ($id_estudiante && $id_estudiante > 0) {
                $asignacionModel = new AsignacionCodigo($this->db);
                $asignados = 0;
                
                foreach ($codigos as $codigo) {
                    // Obtener el id_codigo del código generado
                    $codigoData = $this->codigoModel->getByCodigo($codigo);
                    if ($codigoData && $asignacionModel->crear($id_estudiante, $codigoData['id_codigo'])) {
                        $asignados++;
                    }
                }
                $_SESSION['success'] .= " | $asignados códigos asignados al estudiante";
            }
        } else {
            $_SESSION['error'] = "Error al generar los códigos";
        }
        header("Location: index.php?action=admin_codigos");
        exit;
    }

    private function asignarCodigo()
{
    $id_codigo = isset($_POST['id_codigo']) ? intval($_POST['id_codigo']) : 0;
    $id_estudiante = isset($_POST['id_estudiante']) ? intval($_POST['id_estudiante']) : 0;
    
    if ($id_codigo <= 0 || $id_estudiante <= 0) {
        $_SESSION['error'] = "Datos inválidos para la asignación";
        header("Location: index.php?action=admin_codigos");
        exit;
    }
    
    $asignacionModel = new AsignacionCodigo($this->db);
    
    if ($asignacionModel->crear($id_estudiante, $id_codigo)) {
        $_SESSION['success'] = "Código asignado correctamente al estudiante";
    } else {
        $_SESSION['error'] = "Error al asignar el código. Verifica que el estudiante exista.";
    }
    header("Location: index.php?action=admin_codigos");
    exit;
}

    private function eliminarCodigo()
    {
        $id = $_POST['id_codigo'] ?? 0;
        
        if ($this->codigoModel->eliminar($id)) {
            $_SESSION['success'] = "Código eliminado correctamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el código";
        }
        header("Location: index.php?action=admin_codigos");
        exit;
    }

    private function revocarCodigo()
    {
        $id = $_POST['id_codigo'] ?? 0;
        
        if ($this->codigoModel->revocar($id)) {
            $_SESSION['success'] = "Código revocado correctamente";
        } else {
            $_SESSION['error'] = "Error al revocar el código";
        }
        header("Location: index.php?action=admin_codigos");
        exit;
    }

    public function resultados()
    {
        $pageTitle = "Resultados";
        $activePage = "resultados";
        
        $id_examen = $_GET['id_examen'] ?? 0;
        
        if ($id_examen > 0) {
            $resultados = $this->resultadoModel->getByExamen($id_examen);
            $estadisticas = $this->resultadoModel->getEstadisticasPorExamen($id_examen);
            $examen = $this->examenModel->getById($id_examen);
        } else {
            $resultados = $this->resultadoModel->getAll();
            $estadisticas = null;
            $examen = null;
        }
        
        $examenes = $this->examenModel->getAll();
        
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/resultados.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function usuarios()
    {
        $pageTitle = "Gestión de Usuarios";
        $activePage = "usuarios";
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'crear':
                        $this->crearUsuario();
                        break;
                    case 'editar':
                        $this->editarUsuario();
                        break;
                    case 'eliminar':
                        $this->eliminarUsuario();
                        break;
                }
            }
        }
        
        $estudiantes = $this->usuarioModel->getAllEstudiantes();
        
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/usuarios.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    private function crearUsuario()
    {
        $data = [
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'nombre' => $_POST['nombre'] ?? '',
            'apellidos' => $_POST['apellidos'] ?? '',
            'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
            'telefono' => $_POST['telefono'] ?? ''
        ];
        
        if ($this->usuarioModel->crearEstudiante($data)) {
            $_SESSION['success'] = "Estudiante creado correctamente";
        } else {
            $_SESSION['error'] = "Error al crear el estudiante";
        }
        header("Location: index.php?action=admin_usuarios");
        exit;
    }

    private function editarUsuario()
    {
        $id_usuario = $_POST['id_usuario'] ?? 0;
        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'apellidos' => $_POST['apellidos'] ?? '',
            'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
            'telefono' => $_POST['telefono'] ?? ''
        ];
        
        if ($this->usuarioModel->actualizarEstudiante($id_usuario, $data)) {
            $_SESSION['success'] = "Estudiante actualizado correctamente";
        } else {
            $_SESSION['error'] = "Error al actualizar el estudiante";
        }
        header("Location: index.php?action=admin_usuarios");
        exit;
    }

    private function eliminarUsuario()
    {
        $id_usuario = $_POST['id_usuario'] ?? 0;
        
        if ($this->usuarioModel->eliminarEstudiante($id_usuario)) {
            $_SESSION['success'] = "Estudiante eliminado correctamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el estudiante";
        }
        header("Location: index.php?action=admin_usuarios");
        exit;
    }
}
?>