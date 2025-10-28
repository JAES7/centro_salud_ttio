<?php
// controllers/CajaController.php (CON ARREGLO DE RUTA DE TCPDF)

// --- ¡AQUÍ ESTÁ LA CORRECCIÓN DE LA RUTA! ---
// Usamos dirname(__DIR__) para subir un nivel y encontrar assets/lib/
require_once dirname(__DIR__) . '/assets/lib/tcpdf/tcpdf_barcodes_1d.php';


class CajaController extends Controller {

    private $especialidadModel;
    private $profesionalModel;
    private $servicioModel;
    private $atencionModel;
    private $ticketModel;

    public function __construct() {
        parent::__construct();
        $rol = $_SESSION['rol'] ?? '';
        // Acepta admin, caja O soporte
        if (!isset($_SESSION['id_usuario']) || ($rol != 'admin' && $rol != 'caja' && $rol != 'soporte')) {
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }
        $this->especialidadModel = $this->loadModel('EspecialidadModel');
        $this->profesionalModel = $this->loadModel('ProfesionalModel');
        $this->servicioModel = $this->loadModel('ServicioModel');
        $this->atencionModel = $this->loadModel('AtencionModel');
        $this->ticketModel = $this->loadModel('TicketModel');
    }

    public function index() {
        $especialidades = $this->especialidadModel->getAll();
        $data = [
            'titulo' => 'Caja - Nueva Atención',
            'especialidades' => $especialidades,
            'profesionales' => [],
            'servicios' => []
        ];
        $this->renderView('caja/index', $data, true);
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = $_POST;
            try {
                $id_nueva_atencion = $this->atencionModel->crearAtencion($datos);
                if ($id_nueva_atencion) {
                    $_SESSION['mensaje_exito'] = "¡Atención registrada con éxito! (ID: $id_nueva_atencion)";
                    $_SESSION['ultimo_id_atencion'] = $id_nueva_atencion;
                } else {
                    $_SESSION['mensaje_error'] = "Ocurrió un error desconocido al guardar.";
                }
            } catch (Exception $e) {
                $_SESSION['mensaje_error'] = "<b>Error al guardar:</b> " . $e->getMessage();
            }
            header('Location: ' . APP_URL . '/caja');
            exit;
        } else {
            header('Location: ' . APP_URL . '/caja');
            exit;
        }
    }

    /**
     * Imprime el ticket (con GENERACIÓN DE CÓDIGO DE BARRAS)
     */
    public function imprimir($id_atencion = 0) {
        $id_atencion = filter_var($id_atencion, FILTER_VALIDATE_INT);
        if ($id_atencion === false || $id_atencion <= 0) { die("Error: ID inválido."); }

        $datosTicket = $this->ticketModel->getDatosTicket($id_atencion);
        if (!$datosTicket) { die("Error: No se encontraron datos."); }
        $atencion = $datosTicket['atencion'];

        // --- GENERACIÓN DEL CÓDIGO DE BARRAS ---
        $barcodeData = null;
        $barcodeError = null;
        try {
            // Crear el texto para el código de barras
            $nombreLimpio = preg_replace('/[^A-Za-z0-9\s]/', '', $atencion->profesional_nombre);
            $consultorio = $atencion->especialidad_consultorio ?? 'S/C';
            $ticketDiario = $atencion->numero_ticket_diario ?? '0';
            $barcodeText = $consultorio . '|' . $nombreLimpio . '|' . $ticketDiario;

            // Verificamos si la clase existe antes de usarla
            if (!class_exists('TCPDFBarcode')) {
                throw new Exception('La clase TCPDFBarcode no se encontró. Verifica la inclusión y ruta de la librería.');
            }

            $barcode = new TCPDFBarcode($barcodeText, 'C128');
            $barcodeImage = $barcode->getBarcodePngData(1.5, 50, [0, 0, 0]);

            if ($barcodeImage) {
                $barcodeData = 'data:image/png;base64,' . base64_encode($barcodeImage);
            } else {
                 throw new Exception('TCPDFBarcode::getBarcodePngData() devolvió datos vacíos. Verifica los datos de entrada o la configuración de GD.');
            }

        } catch (Exception $e) {
            $barcodeError = $e->getMessage();
            $barcodeData = null;
        }
        // --- FIN GENERACIÓN CÓDIGO DE BARRAS ---

        $data = [
            'titulo' => 'Boleta de Atención',
            'datos' => $datosTicket,
            'codigo_barras_base64' => $barcodeData,
            'error_codigo_barras' => $barcodeError
        ];

        $this->renderView('ticket/boleta_termica', $data, false);
    }

/**
    * API: Obtiene Profesionales por Especialidad
     */
    public function getProfesionales($id_especialidad = 0) {
        // La restricción de seguridad ya fue validada en el __construct, si llegó aquí, es válido.
        $id_especialidad = filter_var($id_especialidad, FILTER_VALIDATE_INT);
        $datos = ($id_especialidad > 0) ? $this->profesionalModel->getPorEspecialidad($id_especialidad) : [];
        header('Content-Type: application/json');
        echo json_encode($datos);
        exit;
    }

    /**
     * API: Obtiene Servicios por Especialidad
     */
    public function getServicios($id_especialidad = 0) {
        // La restricción de seguridad ya fue validada en el __construct, si llegó aquí, es válido.
        $id_especialidad = filter_var($id_especialidad, FILTER_VALIDATE_INT);
        $datos = ($id_especialidad > 0) ? $this->servicioModel->getPorEspecialidad($id_especialidad) : [];
        header('Content-Type: application/json');
        echo json_encode($datos);
        exit;
    }
}
?>