<?php
class ServiciosController extends Controller {

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
     * Muestra la página principal (lista) de servicios
     */
    public function index() {
        $servicioModel = $this->loadModel('ServicioModel');
        $especialidadModel = $this->loadModel('EspecialidadModel');
        
        $data = [
            'titulo' => "Servicios",
            'servicios' => $servicioModel->getAll(),
            'especialidades' => $especialidadModel->getAll() // Para el <select> del modal
        ];
        
        // Carga la vista (la crearemos en el Paso 3)
        $this->renderView('catalogo/servicios', $data);
    }

    public function editar($id) {
        // Esta función típicamente carga los datos del servicio y especialidades
        $servicioModel = $this->loadModel('ServicioModel');
        $especialidadModel = $this->loadModel('EspecialidadModel');
        
        $data = [
            'titulo' => "Editar Servicio",
            'servicio' => $servicioModel->getById($id), // Carga el servicio a editar
            'especialidades' => $especialidadModel->obtenerTodos() 
        ];
        
        // Idealmente, usarías una vista separada: $this->renderView('catalogo/servicios_editar', $data);
        // Aquí puedes cargar un modal de edición en la vista principal si lo deseas.
    }

    /**
     * Recibe el POST del formulario para actualizar
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                'id_servicio' => $_POST['id_servicio'] ?? 0, // Necesitas un campo oculto con el ID
                'nombre' => $_POST['nombre'] ?? '',
                'precio' => $_POST['precio'] ?? 0.0,
                'id_especialidad' => $_POST['id_especialidad'] ?? null
            ];

            $servicioModel = $this->loadModel('ServicioModel');
            $servicioModel->actualizar($datos);
        }
        // Redirigir de vuelta a la lista
        header('Location: ' . APP_URL . '/servicio');
        exit;
    }
    public function eliminar($id) {
        if (!is_numeric($id)) {
            // Manejo básico de error si el ID no es válido
            header('Location: ' . APP_URL . '/servicio');
            exit;
        }
        
        $servicioModel = $this->loadModel('ServicioModel');
        $servicioModel->eliminar($id); 

        // Redirigir de vuelta a la lista
        header('Location: ' . APP_URL . '/servicio');
        exit;
    }

    /**
     * Recibe el POST del modal para guardar
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'precio' => $_POST['precio'] ?? 0.0,
                'id_especialidad' => $_POST['id_especialidad'] ?? null
            ];

            $servicioModel = $this->loadModel('ServicioModel');
            $servicioModel->guardar($datos);
        }
        // Redirigir de vuelta a la lista
        header('Location: ' . APP_URL . '/servicio');
        exit;
    }

    
}
?>