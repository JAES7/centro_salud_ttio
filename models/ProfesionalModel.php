<?php
// models/ProfesionalModel.php

class ProfesionalModel extends Model {

    // El constructor que acepta la conexión $db
    public function __construct($db) {
        // Y se la pasa al 'padre' (Model)
        parent::__construct($db);
    }

    /**
     * Obtiene todos los profesionales activos
     */
    public function getAll() {
        // Obtenemos los profesionales y, de paso, el nombre de su especialidad
        $this->query("
            SELECT p.*, e.nombre as nombre_especialidad 
            FROM profesionales p
            JOIN especialidades e ON p.id_especialidad = e.id_especialidad
        ");
        
        $filas = $this->resultSet();
        return $filas;
    }
    
    /**
     * Obtiene todos los profesionales DE UNA especialidad específica
     */
    public function getPorEspecialidad($id_especialidad) {
        $this->query("
            SELECT * FROM profesionales 
            WHERE id_especialidad = :id_especialidad
        ");
        $this->bind(':id_especialidad', $id_especialidad);
        
        $filas = $this->resultSet();
        return $filas;
    }

    // (Aquí irán más funciones, como: getById, crear, actualizar, etc.)
}
?>