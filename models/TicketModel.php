<?php
// models/TicketModel.php (ACTUALIZADO PARA LEER HORA_TURNO_ESTIMADA)

class TicketModel extends Model {

    public function __construct($db) {
        parent::__construct($db);
    }

    /**
     * Obtiene todos los datos necesarios para imprimir un ticket
     * basado en el ID de la atención.
     */
    public function getDatosTicket($id_atencion) {
        
        $datosTicket = [];

        // 1. Obtener datos principales (¡CON LA NUEVA HORA!)
        $this->query("
            SELECT 
                a.id_atencion, 
                a.fecha_hora, 
                a.turno, 
                a.total, 
                a.numero_ticket_diario,
                a.hora_turno_estimada, -- <-- ¡AÑADIDO!
                p.nombre_completo AS paciente_nombre,
                p.dni AS paciente_dni,
                prof.nombre_completo AS profesional_nombre,
                e.nombre AS especialidad_nombre,
                e.consultorio AS especialidad_consultorio,
                u.nombre_completo AS cajero_nombre 
            FROM atenciones a
            JOIN pacientes p ON a.id_paciente = p.id_paciente
            JOIN profesionales prof ON a.id_profesional = prof.id_profesional
            JOIN especialidades e ON a.id_especialidad = e.id_especialidad
            JOIN usuarios u ON a.id_usuario_caja = u.id_usuario
            WHERE a.id_atencion = :id_atencion
        ");
        $this->bind(':id_atencion', $id_atencion);
        $datosPrincipales = $this->single();

        if (!$datosPrincipales) {
            return null;
        }
        $datosTicket['atencion'] = $datosPrincipales;


        // 2. Obtener los servicios asociados (sin cambios)
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
        $servicios = $this->resultSet();
        
        $datosTicket['servicios'] = $servicios;

        return $datosTicket;
    }

}
?>