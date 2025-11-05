<?php
class ProfesionalesController extends Controller {

    public function __construct() {
        parent::__construct();
        // --- SEGURIDAD POR ROL ---
        $rol = $_SESSION['rol'] ?? 'guest'; 
        if ($rol != 'admin' && $rol != 'soporte') { 
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }
    }
    
    /**
     * Muestra la página principal (lista) de profesionales
     */
    public function index() {
        // Carga los modelos
        $profesionalModel = $this->loadModel('ProfesionalModel');
        $especialidadModel = $this->loadModel('EspecialidadModel');
        
        $data = [
            'titulo' => "Profesionales",
            'profesionales' => $profesionalModel->getAll(), // Para la tabla
            'especialidades' => $especialidadModel->getAll() // Para el <select> del modal
        ];
        
        // Carga la vista que ya tienes y le pasa los datos
        $this->renderView('catalogo/profesionales', $data);
    }

    /**
     * Recibe el POST del modal para guardar
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                'nombres' => $_POST['nombres'] ?? '',
                'apellidos' => $_POST['apellidos'] ?? '',
                'cmp' => $_POST['cmp'] ?? '',
                'dni' => $_POST['dni'] ?? '',
                'id_especialidad' => $_POST['id_especialidad'] ?? null
            ];

            $profesionalModel = $this->loadModel('ProfesionalModel');
            $profesionalModel->guardar($datos);
        }
        // Redirigir de vuelta a la lista
        header('Location: ' . APP_URL . '/profesional');
        exit;
    }
}
?>