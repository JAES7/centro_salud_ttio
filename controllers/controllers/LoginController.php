<?php
// controllers/LoginController.php (CON LÓGICA DE CAMBIO FORZADO)

class LoginController extends Controller {
    private $usuarioModel;

    public function __construct() {
        parent::__construct();
        $this->usuarioModel = $this->loadModel('UsuarioModel');
    }

    public function index() {
        if (isset($_SESSION['id_usuario'])) {
            if ($_SESSION['cambiar_password'] ?? false) { 
                 header('Location: ' . APP_URL . '/login/cambioForzado');
                 exit;
            }
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }
        $this->renderView('login/index', [], false);
    }

    public function auth() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim(htmlspecialchars($_POST['username']));
            $password = trim(htmlspecialchars($_POST['password']));

            if (empty($username) || empty($password)) {
                $_SESSION['error_login'] = 'Por favor, complete todos los campos.';
                header('Location: ' . APP_URL . '/login');
                exit;
            }

            $usuario = $this->usuarioModel->getUsuarioPorUsername($username);

            // ¡IMPORTANTE! Usamos el modo inseguro (texto plano)
            if ($usuario && $password == $usuario->password_hash) { 
                
                $_SESSION['id_usuario'] = $usuario->id_usuario;
                $_SESSION['username'] = $usuario->username;
                $_SESSION['rol'] = $usuario->rol;
                $_SESSION['cambiar_password'] = $usuario->cambiar_password; 

                // LÓGICA DE REDIRECCIÓN FORZADA
                if ($_SESSION['cambiar_password']) {
                    header('Location: ' . APP_URL . '/login/cambioForzado');
                    exit;
                }

                header('Location: ' . APP_URL . '/dashboard');
                exit;

            } else {
                $_SESSION['error_login'] = 'Usuario o contraseña incorrectos.';
                header('Location: ' . APP_URL . '/login');
                exit;
            }
        }
        header('Location: ' . APP_URL . '/login');
        exit;
    }

    /**
     * Muestra el formulario para cambio de contraseña forzado.
     */
    public function cambioForzado() {
        if (!isset($_SESSION['id_usuario']) || !($_SESSION['cambiar_password'] ?? false)) {
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }
        $this->renderView('login/cambio_forzado', [], false);
    }

    /**
     * Procesa el cambio de contraseña forzado.
     */
    public function actualizarPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!($_SESSION['cambiar_password'] ?? false)) {
                 header('Location: ' . APP_URL . '/dashboard');
                 exit;
            }

            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            $id_usuario = $_SESSION['id_usuario'] ?? 0;

            if (empty($password) || $password != $password_confirm || $id_usuario == 0) {
                $_SESSION['error_cambio'] = 'Las contraseñas no coinciden o están vacías.';
                header('Location: ' . APP_URL . '/login/cambioForzado');
                exit;
            }

            try {
                $this->usuarioModel->actualizarPassword($id_usuario, $password); 
                
                $_SESSION['mensaje_exito'] = 'Contraseña actualizada con éxito.';
                $_SESSION['cambiar_password'] = 0; // Quitar el flag
                
                header('Location: ' . APP_URL . '/dashboard');
                exit;

            } catch (Exception $e) {
                $_SESSION['error_cambio'] = 'Error al actualizar la contraseña: ' . $e->getMessage();
                header('Location: ' . APP_URL . '/login/cambioForzado');
                exit;
            }
        }
        header('Location: ' . APP_URL . '/dashboard');
        exit;
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: ' . APP_URL . '/login');
        exit;
    }
}