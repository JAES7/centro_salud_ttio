<?php
// controllers/ReporteController.php (CON EXPORTACIÓN CSV MEJORADA)

// Se eliminan los 'use' de PhpSpreadsheet

class ReporteController extends Controller {

    private $reporteModel;

    public function __construct() {
        parent::__construct();
        // --- SEGURIDAD: Solo Admin puede acceder a reportes ---
        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'admin') {
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }
        $this->reporteModel = $this->loadModel('ReporteModel');
    }

    public function index() {
        $data = [
            'titulo' => 'Reportes - Exportación de Datos',
        ];
        $this->renderView('reportes/index', $data, true);
    }
    
    /**
     * Genera y descarga el archivo CSV (simulando Excel) con todos los datos.
     * URL: /reporte/exportarExcel (Se mantiene la URL anterior)
     */
    public function exportarExcel() {
        try {
            $datos = $this->reporteModel->obtenerReporteCompleto();

            // 1. Cabeceras para forzar la descarga como CSV
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="Reporte_Citas_Triaje_' . date('Ymd_His') . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            // 2. Escribir las cabeceras del CSV
            $headers = [
                'ID Cita', 'Fecha/Hora', 'Turno', 'TICKET Nro', 'Hora Turno Est.', 'Total (S/)',
                'DNI Paciente', 'Nombre Paciente', 'Especialidad', 'Profesional',
                'Temp', 'Peso', 'Talla', 'PA', 'O2 (SpO2)', 'FC',
                'Servicios Detalle' 
            ];
            fputcsv($output, $headers, ';'); // Usamos punto y coma (;) como separador para mejor compatibilidad con Excel.

            // 3. Escribir los datos
            foreach ($datos as $atencion) {
                $serviciosTexto = "";
                foreach ($atencion->servicios_detalle as $servicio) {
                    $desc = str_replace(array("\n", "\r", ";"), '', $servicio->descripcion);
                    $serviciosTexto .= $desc . " (x" . $servicio->cantidad . " @ S/" . number_format($servicio->subtotal, 2) . ") / ";
                }
                $serviciosTexto = rtrim($serviciosTexto, ' / ');

                $rowData = [
                    $atencion->id_atencion,
                    $atencion->fecha_hora,
                    $atencion->turno,
                    $atencion->numero_ticket_diario,
                    $atencion->hora_turno_estimada,
                    number_format($atencion->total_atencion, 2),
                    $atencion->paciente_dni,
                    $atencion->paciente_nombre,
                    $atencion->especialidad,
                    $atencion->profesional,
                    $atencion->temperatura ?? 'N/A',
                    $atencion->peso ?? 'N/A',
                    $atencion->talla ?? 'N/A',
                    $atencion->presion_arterial ?? 'N/A',
                    $atencion->oxigenacion ?? 'N/A',
                    $atencion->frecuencia_cardiaca ?? 'N/A',
                    $serviciosTexto
                ];
                
                fputcsv($output, $rowData, ';');
            }

            // 4. Cerrar el flujo
            fclose($output);
            exit;

        } catch (Exception $e) {
            die("Error al generar el Reporte: " . $e->getMessage());
        }
    }
}
?>