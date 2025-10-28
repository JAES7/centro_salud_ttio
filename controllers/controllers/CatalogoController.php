<?php
// controllers/CatalogoController.php (CRUD GENÉRICO CON RESTRICCIÓN DE ROL)

class CatalogoController extends Controller {

    private $catalogoModel;

    public function __construct() {
        parent::__construct();
        
        // --- SEGURIDAD POR ROL ---
        <?php
// controllers/CatalogoController.php (CRUD GENÉRICO CON RESTRICCIÓN DE ROL)

class CatalogoController extends Controller {

    private $catalogoModel;

    public function __construct() {
        parent::__construct();
        
        // --- SEGURIDAD POR ROL ---
        $rol = $_SESSION['rol'] ?? '';
        if (!isset($_SESSION['id_usuario']) || ($rol != 'admin' && $rol != 'soporte')) {
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }
        // --- FIN SEGURIDAD ---

        $this->catalogoModel = $this->loadModel('CatalogoModel');
    }
    
    // (El resto del código del controlador sigue aquí)
    private function getTableConfig() { /* ... */ }
    public function index() { /* ... */ }
    public function add() { /* ... */ }
    public function update() { /* ... */ }
    public function delete($id) { /* ... */ }
}
        // --- FIN SEGURIDAD ---

        $this->catalogoModel = $this->loadModel('CatalogoModel');
    }
    
    // (Resto del código del controlador sigue aquí)
    private function getTableConfig() { /* ... */ }
    public function index() { /* ... */ }
    public function add() { /* ... */ }
    public function update() { /* ... */ }
    public function delete($id) { /* ... */ }
}