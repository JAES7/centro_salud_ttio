<?php
// controllers/DashboardController.php (CORREGIDO)

class DashboardController extends Controller {

    public function __construct() {
        // Ejecuta el constructor del 'padre' (Controller)
        // para crear la conexión $this->db
        parent::__construct();

        // --- ¡SEGURIDAD! ---
        // Verificamos si el usuario ha iniciado sesión.
        // Si no, lo "botamos" al login.
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        // --- FIN DE SEGURIDAD ---
    }

    /**
     * Muestra la página principal del dashboard
     */
    
    // --- ¡AQUÍ ESTABA EL ERROR! (Decía 'publicaS') ---
    public function index() { // <-- CORREGIDO
    
        // Preparamos los datos para la vista
        $data = [
            'titulo' => 'Panel de Bienvenida',
            'nombre_usuario' => $_SESSION['username'] ?? 'Usuario'
        ];

        // Cargamos la vista (esta vez SÍ usa el template.php)
        $this->renderView('dashboard/index', $data, true);
    }
}
?>