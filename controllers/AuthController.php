<?php
class AuthController
{
    private $db;
    private $usuarioModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->usuarioModel = new Usuario($db);
    }

    public function login()
    {
        if (isset($_SESSION['usuario'])) {
            $this->redirectByRole($_SESSION['usuario']['rol']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['contraseña'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Todos los campos son requeridos";
                header("Location: index.php?action=login");
                exit;
            }

            $user = $this->usuarioModel->login($email, $password);

            if ($user) {
                $_SESSION['usuario'] = $user;
                $this->usuarioModel->registrarAcceso($user['id_usuario']);
                $this->redirectByRole($user['rol']);
            } else {
                $_SESSION['error'] = "Email o contraseña incorrectos";
                header("Location: index.php?action=login");
                exit;
            }
        } else {
            include dirname(__DIR__) . '/views/auth/login.php';
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }

    private function redirectByRole($rol)
    {
        switch ($rol) {
            case 'admin':
                header("Location: index.php?action=admin_dashboard");
                break;
            case 'estudiante':
                header("Location: index.php?action=estudiante_bienvenida");
                break;
            default:
                header("Location: index.php?action=login");
        }
        exit;
    }

    public function verificarSesion()
    {
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?action=login");
            exit;
        }
    }

    public function verificarRol($rol)
    {
        $this->verificarSesion();
        if ($_SESSION['usuario']['rol'] !== $rol) {
            header("Location: index.php?action=login");
            exit;
        }
    }
}