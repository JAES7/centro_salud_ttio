<?php
// controllers/PacienteController.php (CON IMPRIMIR TODOS)

class PacienteController extends Controller {

    private $pacienteModel;
    private $reporteModel; 

    public function __construct() {
        parent::__construct();

        // --- SEGURIDAD POR ROL ---
        $rol = $_SESSION['rol'] ?? '';
        // Acepta admin, triaje, caja O soporte
        if (!isset($_SESSION['id_usuario']) || ($rol != 'admin' && $rol != 'triaje' && $rol != 'caja' && $rol != 'soporte')) {
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }

        $this->pacienteModel = $this->loadModel('PacienteModel');
        $this->reporteModel = $this->loadModel('ReporteModel'); 
    }

    /**
     * Muestra la lista de pacientes con opciones de búsqueda y CRUD.
     */
    public function index() {
        $busqueda = $_GET['q'] ?? '';
        $incluirEliminados = isset($_GET['e']) ? true : false;
        
        $pacientes = $this->pacienteModel->buscarPacientes($busqueda, $incluirEliminados);

        $data = [
            'titulo' => 'Pacientes - Búsqueda y Gestión',
            'pacientes' => $pacientes,
            'busqueda' => htmlspecialchars($busqueda),
            'incluir_eliminados' => $incluirEliminados
        ];

        $this->renderView('pacientes/index', $data, true);
    }

    // --- FUNCIONES DE EDICIÓN Y BORRADO (Lógica sin cambios) ---
    public function editar($id_paciente) { 
        $paciente = $this->pacienteModel->getById($id_paciente);
        if (!$paciente) { $_SESSION['mensaje_error'] = "Paciente no encontrado."; header('Location: ' . APP_URL . '/paciente'); exit; }
        $data = [ 'titulo' => 'Editar Paciente: ' . $paciente->nombre_completo, 'paciente' => $paciente ];
        $this->renderView('pacientes/form', $data, true); 
    }
    public function actualizar() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_paciente = $_POST['id_paciente'] ?? 0;
            $dni = trim(htmlspecialchars($_POST['dni']));
            $nombre_completo = trim(htmlspecialchars($_POST['nombre_completo']));

            if (empty($id_paciente) || empty($dni) || empty($nombre_completo)) {
                $_SESSION['mensaje_error'] = "Todos los campos son obligatorios.";
                header('Location: ' . APP_URL . '/paciente/editar/' . $id_paciente);
                exit;
            }
            try {
                $this->pacienteModel->update($id_paciente, $dni, $nombre_completo);
                $_SESSION['mensaje_exito'] = "Paciente ID $id_paciente actualizado con éxito.";
            } catch (Exception $e) {
                $_SESSION['mensaje_error'] = "Error al actualizar: " . $e->getMessage();
            }
            header('Location: ' . APP_URL . '/paciente');
            exit;
        }
        header('Location: ' . APP_URL . '/paciente');
        exit;
    }
    public function softDelete($id_paciente) { 
        if ($_SESSION['rol'] != 'admin') { $_SESSION['mensaje_error'] = "Permiso denegado."; header('Location: ' . APP_URL . '/paciente'); exit; }
        try { $this->pacienteModel->softDelete($id_paciente); $_SESSION['mensaje_exito'] = "Paciente ID $id_paciente marcado como inactivo (Borrado Suave)."; } catch (Exception $e) { $_SESSION['mensaje_error'] = "Error al marcar como inactivo: " . $e->getMessage(); }
        header('Location: ' . APP_URL . '/paciente'); exit;
    }
    public function hardDelete($id_paciente) {
        if ($_SESSION['rol'] != 'admin') { $_SESSION['mensaje_error'] = "Permiso denegado."; header('Location: ' . APP_URL . '/paciente'); exit; }
        try { $this->pacienteModel->hardDelete($id_paciente); $_SESSION['mensaje_exito'] = "Paciente ID $id_paciente y todos sus registros ELIMINADOS permanentemente."; } catch (Exception $e) { $_SESSION['mensaje_error'] = "Error fatal al eliminar permanentemente: " . $e->getMessage(); }
        header('Location: ' . APP_URL . '/paciente'); exit;
    }
    public function restore($id_paciente) {
        if ($_SESSION['rol'] != 'admin') { $_SESSION['mensaje_error'] = "Permiso denegado."; header('Location: ' . APP_URL . '/paciente'); exit; }
        try { $this->pacienteModel->restoreSoftDelete($id_paciente); $_SESSION['mensaje_exito'] = "Paciente ID $id_paciente restaurado correctamente."; } catch (Exception $e) { $_SESSION['mensaje_error'] = "Error al restaurar: " . $e->getMessage(); }
        header('Location: ' . APP_URL . '/paciente'); exit;
    }
    public function exportarReporte() {
         $reporteController = $this->loadController('ReporteController');
        $reporteController->exportarExcel(); 
        exit;
    }
    public function imprimirPaciente($id_paciente) { 
        $paciente = $this->pacienteModel->getById($id_paciente);
        if (!$paciente) { die("Paciente no encontrado."); }
        $data = [
            'titulo' => 'Reporte Histórico - ' . $paciente->nombre_completo,
            'paciente' => $paciente,
            'atenciones_historicas' => $this->reporteModel->obtenerReporteCompletoPorPaciente($id_paciente) 
        ];
        $this->renderView('pacientes/reporte_pdf', $data, false);
    } 


    // --- ¡NUEVA FUNCIÓN! IMPRIMIR TODOS ---
    /**
     * Muestra una vista de impresión con TODOS los pacientes activos.
     */
    public function imprimirTodos() {
        // Obtenemos TODOS los pacientes activos (sin borrado suave)
        $pacientes = $this->pacienteModel->buscarPacientes('', false); 
        
        $data = [
            'titulo' => 'Reporte General de Pacientes Activos',
            'pacientes' => $pacientes
        ];
        
        // Renderizamos la vista simple de impresión (sin template)
        $this->renderView('pacientes/reporte_todos', $data, false); 
    }
}