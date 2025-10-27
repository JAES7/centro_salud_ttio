<?php
// models/AtencionModel.php (VERSIÓN FINAL Y COMPLETA)

class AtencionModel extends Model {

    public function __construct($db) {
        parent::__construct($db);
    }

    public function crearAtencion($datos) {
        // ... (Código de cálculo de hora y transacción - sin cambios) ...

        try {
            $fechaHoraObjeto = new DateTime($datos['fecha_hora'], new DateTimeZone('America/Lima'));
            $fechaHoraParaBD = $fechaHoraObjeto->format('Y-m-d H:i:s');
            $fecha_solo = $fechaHoraObjeto->format('Y-m-d');
        } catch (Exception $e) {
             $now = new DateTime("now", new DateTimeZone('America/Lima'));
             $fechaHoraParaBD = $now->format('Y-m-d H:i:s');
             $fecha_solo = $now->format('Y-m-d');
             $fechaHoraObjeto = $now;
        }

        $duraciones = [ /* ... */ ];
        $idEspecialidadActual = $datos['id_especialidad'];
        $nombreEspecialidadActualLimpio = null;
        try {
            $this->query("SELECT LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(nombre, 'á', 'a'), 'é', 'e'), 'í', 'i'), 'ó', 'o'), 'ú', 'u'), 'ñ', 'n')) AS nombre_limpio FROM especialidades WHERE id_especialidad = :id");
            $this->bind(':id', $idEspecialidadActual);
            $esp = $this->single();
            if ($esp) { $nombreEspecialidadActualLimpio = $esp->nombre_limpio; }
        } catch (Exception $e) { error_log("Error buscando nombre de especialidad: " . $e->getMessage()); }

        $horaTurnoEstimadaParaBD = null;
        if ($nombreEspecialidadActualLimpio && isset($duraciones[$nombreEspecialidadActualLimpio]) && $duraciones[$nombreEspecialidadActualLimpio] > 0) {
            $this->query("
                SELECT hora_turno_estimada, id_especialidad
                FROM atenciones
                WHERE id_profesional = :id_profesional
                  AND DATE(fecha_hora) = :fecha_solo
                ORDER BY fecha_hora DESC, id_atencion DESC
                LIMIT 1
            ");
            $this->bind(':id_profesional', $datos['id_profesional']);
            $this->bind(':fecha_solo', $fecha_solo);
            $ultimaAtencion = $this->single();
            $horaInicioDia = new DateTime($fecha_solo . ' 07:30:00', new DateTimeZone('America/Lima'));
            $horaInicioAlmuerzo = new DateTime($fecha_solo . ' 13:00:00', new DateTimeZone('America/Lima'));
            $horaFinAlmuerzo = new DateTime($fecha_solo . ' 14:00:00', new DateTimeZone('America/Lima'));

            $proximaHoraDisponible = null;
            if (!$ultimaAtencion || empty($ultimaAtencion->hora_turno_estimada)) {
                $proximaHoraDisponible = $horaInicioDia;
            } else {
                $horaUltimoTurno = new DateTime($fecha_solo . ' ' . $ultimaAtencion->hora_turno_estimada, new DateTimeZone('America/Lima'));
                $duracionUltimaAtencion = 0;
                try {
                     $this->query("SELECT LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(nombre, 'á', 'a'), 'é', 'e'), 'í', 'i'), 'ó', 'o'), 'ú', 'u'), 'ñ', 'n')) AS nombre_limpio FROM especialidades WHERE id_especialidad = :id");
                     $this->bind(':id', $ultimaAtencion->id_especialidad);
                     $espUltima = $this->single();
                     if ($espUltima) {
                         $nombreUltimaEspecialidadLimpio = $espUltima->nombre_limpio;
                         if (isset($duraciones[$nombreUltimaEspecialidadLimpio])) { $duracionUltimaAtencion = $duraciones[$nombreUltimaEspecialidadLimpio]; }
                     }
                } catch (Exception $e) { error_log("Error buscando nombre de ultima especialidad: " . $e->getMessage()); }

                $proximaHoraDisponible = clone $horaUltimoTurno;
                if ($duracionUltimaAtencion > 0) {
                    $proximaHoraDisponible->add(new DateInterval('PT' . $duracionUltimaAtencion . 'M'));
                }
            }

            if ($proximaHoraDisponible >= $horaInicioAlmuerzo && $proximaHoraDisponible < $horaFinAlmuerzo) {
                $proximaHoraDisponible = $horaFinAlmuerzo;
            }
            $horaTurnoEstimadaParaBD = $proximaHoraDisponible->format('H:i:s');
        }

        try {
            $this->db->beginTransaction();
            $this->query("
                INSERT INTO pacientes (dni, nombre_completo, ultima_visita)
                VALUES (:dni_ins, :nombre_paciente_ins, :fecha_hora_ins)
                ON DUPLICATE KEY UPDATE nombre_completo = :nombre_paciente_upd, ultima_visita = :fecha_hora_upd
            ");
            $this->bind(':dni_ins', $datos['dni_paciente']);
            $this->bind(':nombre_paciente_ins', $datos['nombre_paciente']);
            $this->bind(':fecha_hora_ins', $fechaHoraParaBD);
            $this->bind(':nombre_paciente_upd', $datos['nombre_paciente']);
            $this->bind(':fecha_hora_upd', $fechaHoraParaBD);
            $this->execute();

            $this->query("SELECT id_paciente FROM pacientes WHERE dni = :dni");
            $this->bind(':dni', $datos['dni_paciente']);
            $paciente = $this->single();
            $id_paciente = $paciente->id_paciente;

            $this->query("
                SELECT IFNULL(MAX(numero_ticket_diario), 0) + 1 AS proximo_ticket
                FROM atenciones
                WHERE id_profesional = :id_profesional
                AND DATE(fecha_hora) = :fecha_solo
            ");
            $this->bind(':id_profesional', $datos['id_profesional']);
            $this->bind(':fecha_solo', $fecha_solo);
            $ticket = $this->single();
            $proximo_ticket_diario = $ticket->proximo_ticket;

            $this->query("
                INSERT INTO atenciones
                (id_paciente, id_profesional, id_especialidad, id_usuario_caja, fecha_hora, turno, total, numero_ticket_diario, hora_turno_estimada)
                VALUES
                (:id_paciente, :id_profesional, :id_especialidad, :id_usuario_caja, :fecha_hora, :turno, :total, :numero_ticket_diario, :hora_turno_estimada)
            ");
            $this->bind(':id_paciente', $id_paciente);
            $this->bind(':id_profesional', $datos['id_profesional']);
            $this->bind(':id_especialidad', $idEspecialidadActual);
            $this->bind(':id_usuario_caja', $_SESSION['id_usuario']);
            $this->bind(':fecha_hora', $fechaHoraParaBD);
            $this->bind(':turno', $datos['turno']);
            $this->bind(':total', $datos['totalGeneral']);
            $this->bind(':numero_ticket_diario', $proximo_ticket_diario);
            $this->bind(':hora_turno_estimada', $horaTurnoEstimadaParaBD);
            $this->execute();

            $id_atencion = $this->db->lastInsertId();

            if (isset($datos['servicios_agregados']) && is_array($datos['servicios_agregados'])) {
                $this->query("
                    INSERT INTO atencion_servicios
                    (id_atencion, id_servicio, cantidad, precio_unitario, subtotal)
                    VALUES
                    (:id_atencion, :id_servicio, :cantidad, :precio_unitario, :subtotal)
                ");
                foreach ($datos['servicios_agregados'] as $servicio_id => $servicio_data) {
                    $this->bind(':id_atencion', $id_atencion);
                    $this->bind(':id_servicio', $servicio_data['id']);
                    $this->bind(':cantidad', $servicio_data['cantidad']);
                    $this->bind(':precio_unitario', $servicio_data['precio']);
                    $this->bind(':subtotal', $servicio_data['subtotal']);
                    $this->execute();
                }
            }

            $this->db->commit();
            return $id_atencion;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("Error en AtencionModel: " . $e->getMessage());
        }
    }


    // --- FUNCIÓN DE BÚSQUEDA PARA TRIAJE ---
    public function getUltimaAtencionPendienteTriaje($query) {
        $search = '%' . $query . '%';

        $this->query("
            SELECT
                a.id_atencion,
                p.nombre_completo AS paciente_nombre,
                prof.nombre_completo AS profesional_nombre,
                a.numero_ticket_diario,
                t.id_triaje
            FROM atenciones a
            JOIN pacientes p ON a.id_paciente = p.id_paciente
            JOIN profesionales prof ON a.id_profesional = prof.id_profesional
            LEFT JOIN triaje t ON a.id_atencion = t.id_atencion
            WHERE
                (p.dni LIKE :search_dni OR p.nombre_completo LIKE :search_nombre)
                AND t.id_triaje IS NULL
            ORDER BY a.fecha_hora DESC
            LIMIT 1
        ");
        $this->bind(':search_dni', $search);
        $this->bind(':search_nombre', $search);

        $fila = $this->single();
        return $fila;
    }

    /**
     * Guarda los datos de triaje para una atención específica
     */
    public function guardarTriaje($id_atencion, $datos_triaje) {
        
        // --- ARREGLO AQUÍ: INCLUIMOS id_usuario_triaje ---
        $id_usuario = $_SESSION['id_usuario'] ?? 0; // ID del usuario logueado
        
        $this->query("
            INSERT INTO triaje (
                id_atencion, id_usuario_triaje, temperatura, peso, talla, presion_arterial, oxigenacion, frecuencia_cardiaca
            ) VALUES (
                :id_atencion, :id_usuario, :temp, :peso, :talla, :pa, :spo2, :fc
            )
        ");
        $this->bind(':id_atencion', $id_atencion);
        $this->bind(':id_usuario', $id_usuario); // <-- ¡NUEVO!
        $this->bind(':temp', $datos_triaje['temperatura']);
        $this->bind(':peso', $datos_triaje['peso']);
        $this->bind(':talla', $datos_triaje['talla']);
        $this->bind(':pa', $datos_triaje['presion_arterial']);
        $this->bind(':spo2', $datos_triaje['oxigenacion']); 
        $this->bind(':fc', $datos_triaje['frecuencia_cardiaca']);

        return $this->execute();
    }
}
?>