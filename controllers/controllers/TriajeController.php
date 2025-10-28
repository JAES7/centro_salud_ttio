<?php
// controllers/TriajeController.php (CORREGIDO PARA EVITAR PANTALLAZO BLANCO)

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class TriajeController extends Controller {

    private $atencionModel; 
    private $reporteModel; 

    public function __construct() {
        parent::__construct();

        // --- SEGURIDAD POR ROL ---
        $rol = $_SESSION['rol'] ?? '';
        // Acepta admin, triaje O soporte
        if (!isset($_SESSION['id_usuario']) || ($rol != 'admin' && $rol != 'triaje' && $rol != 'soporte')) {
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }

        $this->atencionModel = $this->loadModel('AtencionModel');
        $this->reporteModel = $this->loadModel('ReporteModel'); 
    }
    
    public function index() {
        $busqueda = $_GET['q'] ?? '';
        $atencionEncontrada = null;
        $mensaje = null;

        if (!empty($busqueda)) {
            $atencionEncontrada = $this->atencionModel->getUltimaAtencionPendienteTriaje($busqueda);
            if (!$atencionEncontrada) {
                $mensaje = 'No se encontró una atención pendiente de triaje para DNI/Nombre: ' . htmlspecialchars($busqueda) . '. O la última ya fue registrada.';
            }
        }

        $data = [
            'titulo' => 'Triaje - Búsqueda de Paciente',
            'busqueda' => htmlspecialchars($busqueda),
            'atencion_encontrada' => $atencionEncontrada,
            'mensaje' => $mensaje
        ];

        $this->renderView('triaje/index', $data, true);
    }

    /**
     * Guarda los datos de triaje
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $id_atencion = $_POST['id_atencion'] ?? 0;

            if ($id_atencion > 0) {
                try {
                    $datos_triaje = $_POST;
                    
                    // Asegurarse de que el TriajeController esté usando el ReporteModel (que ya lo hace en el constructor)
                    // Y llamar a guardarTriaje en AtencionModel
                    $guardado_exitoso = $this->atencionModel->guardarTriaje($id_atencion, $datos_triaje);
                    
                    if ($guardado_exitoso) {
                        $_SESSION['mensaje_exito'] = "Datos de Triaje guardados con éxito para el Ticket ID $id_atencion.";
                    } else {
                        $_SESSION['mensaje_error'] = "Error desconocido al intentar guardar el Triaje.";
                    }
                    
                } catch (Exception $e) {
                    $_SESSION['mensaje_error'] = "Error al guardar el Triaje: " . $e->getMessage();
                }
            } else {
                $_SESSION['mensaje_error'] = "ID de atención inválido para guardar el Triaje.";
            }

            // Redirigir siempre a la página de triaje
            header('Location: ' . APP_URL . '/triaje');
            exit;

        } else {
            // Si intentan acceder por GET, los botamos
            header('Location: ' . APP_URL . '/triaje');
            exit;
        }
    }

    /**
     * Función que llama al ReporteController para exportar.
     */
    public function exportarExcelTodo() {
        
        // El require_once debe estar en el ReporteController para evitar el error aquí
        
        // Lógica para cargar el ReporteController y llamar a la exportación
        $reporteController = $this->loadController('ReporteController');
        $reporteController->exportarExcel();
        exit;
    }


    public function descargarCsvPaciente($id_atencion) { /* ... código ... */ }
}