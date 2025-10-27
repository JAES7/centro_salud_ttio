<?php
// models/PacienteModel.php (CON LÓGICA DE EDICIÓN)

class PacienteModel extends Model {

    public function __construct($db) {
        parent::__construct($db);
    }

    /**
     * Busca pacientes por DNI o nombre, excluyendo los eliminados suavemente.
     */
    public function buscarPacientes($query = '', $incluirEliminados = false) {
        $search = '%' . $query . '%';
        
        $sql = "
            SELECT 
                id_paciente, 
                dni, 
                nombre_completo, 
                ultima_visita, 
                eliminado_suavemente
            FROM pacientes 
            WHERE (dni LIKE :search_dni OR nombre_completo LIKE :search_nombre)
        ";
        
        if (!$incluirEliminados) {
            $sql .= " AND eliminado_suavemente = 0";
        }
        
        $sql .= " ORDER BY nombre_completo ASC";

        $this->query($sql);
        $this->bind(':search_dni', $search);
        $this->bind(':search_nombre', $search);
        
        return $this->resultSet();
    }

    /**
     * Obtiene un paciente por su ID.
     */
    public function getById($id_paciente) {
        $this->query("SELECT * FROM pacientes WHERE id_paciente = :id");
        $this->bind(':id', $id_paciente);
        return $this->single();
    }
    
    /**
     * Actualiza los datos de un paciente.
     */
    public function update($id_paciente, $dni, $nombre_completo) {
        $this->query("UPDATE pacientes SET dni = :dni, nombre_completo = :nombre WHERE id_paciente = :id");
        $this->bind(':dni', $dni);
        $this->bind(':nombre', $nombre_completo);
        $this->bind(':id', $id_paciente);
        return $this->execute();
    }

    // (Funciones Soft Delete y Hard Delete sin cambios)
    public function softDelete($id_paciente) {
        $this->query("UPDATE pacientes SET eliminado_suavemente = 1 WHERE id_paciente = :id");
        $this->bind(':id', $id_paciente);
        return $this->execute();
    }
    public function restoreSoftDelete($id_paciente) {
        $this->query("UPDATE pacientes SET eliminado_suavemente = 0 WHERE id_paciente = :id");
        $this->bind(':id', $id_paciente);
        return $this->execute();
    }
    public function hardDelete($id_paciente) {
        try {
            $this->db->beginTransaction();
            $this->query("SELECT id_atencion FROM atenciones WHERE id_paciente = :id_paciente");
            $this->bind(':id_paciente', $id_paciente);
            $atenciones = $this->resultSet();
            if (!empty($atenciones)) {
                $atencion_ids = array_column($atenciones, 'id_atencion');
                foreach ($atencion_ids as $id) {
                    $this->query("DELETE FROM triaje WHERE id_atencion = :id");
                    $this->bind(':id', $id);
                    $this->execute();
                    $this->query("DELETE FROM atencion_servicios WHERE id_atencion = :id");
                    $this->bind(':id', $id);
                    $this->execute();
                }
                $this->query("DELETE FROM atenciones WHERE id_paciente = :id_paciente");
                $this->bind(':id_paciente', $id_paciente);
                $this->execute();
            }
            $this->query("DELETE FROM pacientes WHERE id_paciente = :id_paciente");
            $this->bind(':id_paciente', $id_paciente);
            $this->execute();
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("Error fatal al eliminar paciente y registros asociados: " . $e->getMessage());
        }
    }
}
?>