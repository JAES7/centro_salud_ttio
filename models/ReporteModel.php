<?php
// models/ReporteModel.php (FINAL CON BÚSQUEDA POR PACIENTE)

class ReporteModel extends Model {

    public function __construct($db) {
        parent::__construct($db);
    }

    /**
     * Obtiene todos los datos de Atenciones y Triaje para exportación (general).
     */
    public function obtenerReporteCompleto() {
        // (SQL para el reporte general)
        $this->query("
            SELECT
                a.id_atencion, a.fecha_hora, a.turno, a.total AS total_atencion, a.numero_ticket_diario, a.hora_turno_estimada,
                p.dni AS paciente_dni, p.nombre_completo AS paciente_nombre, e.nombre AS especialidad, prof.nombre_completo AS profesional,
                t.temperatura, t.peso, t.talla, t.presion_arterial, t.oxigenacion, t.frecuencia_cardiaca
            FROM atenciones a
            JOIN pacientes p ON a.id_paciente = p.id_paciente
            JOIN profesionales prof ON a.id_profesional = prof.id_profesional
            JOIN especialidades e ON a.id_especialidad = e.id_especialidad
            LEFT JOIN triaje t ON a.id_atencion = t.id_atencion
            ORDER BY a.fecha_hora DESC
        ");

        $atenciones = $this->resultSet();
        $reporte = [];

        foreach ($atenciones as $atencion) {
            $atencion->servicios_detalle = $this->obtenerServiciosPorAtencion($atencion->id_atencion);
            $reporte[] = $atencion;
        }

        return $reporte;
    }

    /**
     * Obtiene TODO el historial de citas y triaje para UN paciente específico.
     */
    public function obtenerReporteCompletoPorPaciente($id_paciente) {
        $this->query("
            SELECT
                a.id_atencion, a.fecha_hora, a.turno, a.total AS total_atencion, a.numero_ticket_diario, a.hora_turno_estimada,
                e.nombre AS especialidad, prof.nombre_completo AS profesional,
                t.temperatura, t.peso, t.talla, t.presion_arterial, t.oxigenacion, t.frecuencia_cardiaca
            FROM atenciones a
            JOIN profesionales prof ON a.id_profesional = prof.id_profesional
            JOIN especialidades e ON a.id_especialidad = e.id_especialidad
            LEFT JOIN triaje t ON a.id_atencion = t.id_atencion
            WHERE a.id_paciente = :id_paciente -- FILTRO POR PACIENTE
            ORDER BY a.fecha_hora DESC
        ");
        $this->bind(':id_paciente', $id_paciente);

        $atenciones = $this->resultSet();
        $reporte = [];

        foreach ($atenciones as $atencion) {
            $atencion->servicios_detalle = $this->obtenerServiciosPorAtencion($atencion->id_atencion);
            $reporte[] = $atencion;
        }

        return $reporte;
    }

    /**
     * Obtiene el detalle de servicios para una atención específica
     */
    public function obtenerServiciosPorAtencion($id_atencion) {
        $this->query("
            SELECT
                s.descripcion,
                a_s.cantidad,
                a_s.precio_unitario,
                a_s.subtotal
            FROM atencion_servicios a_s
            JOIN servicios s ON a_s.id_servicio = s.id_servicio
            WHERE a_s.id_atencion = :id_atencion
        ");
        $this->bind(':id_atencion', $id_atencion);
        
        return $this->resultSet();
    }
}
?>