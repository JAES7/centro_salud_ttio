<?php
class EspecialidadesController extends Controller {

    public function __construct() {
        parent::__construct();
        // --- SEGURIDAD POR ROL ---
        $rol = $_SESSION['rol'] ?? 'guest'; 
        // Solo 'admin' y 'soporte' pueden ver esto
        if ($rol != 'admin' && $rol != 'soporte') { 
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }
    }
    
    /**
     * Muestra la página principal (lista) de especialidades
     */
    public function index() {
        $especialidadModel = $this->loadModel('EspecialidadModel');
        $data = [
            'titulo' => "Especialidades",
            'especialidades' => $especialidadModel->getAll()
        ];
        
        // Carga la vista (la crearemos en el Paso 3)
        $this->renderView('catalogo/especialidades', $data);
    }

    /**
     * Recibe el POST del modal para guardar
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? ''
            ];

            $especialidadModel = $this->loadModel('EspecialidadModel');
            $especialidadModel->guardar($datos);
        }
        // Redirigir de vuelta a la lista
        header('Location: ' . APP_URL . '/especialidad');
        exit;
    }
}
?>