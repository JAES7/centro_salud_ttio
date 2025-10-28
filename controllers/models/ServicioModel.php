<?php
// models/ServicioModel.php

class ServicioModel extends Model {

    // El constructor que acepta la conexión $db
    public function __construct($db) {
        // Y se la pasa al 'padre' (Model)
        parent::__construct($db);
    }

    /**
     * Obtiene todos los servicios activos
     */
    public function getAll() {
        $this->query("
            SELECT s.*, e.nombre as nombre_especialidad 
            FROM servicios s
            JOIN especialidades e ON s.id_especialidad = e.id_especialidad
        ");
        
        $filas = $this->resultSet();
        return $filas;
    }
    
    /**
     * Obtiene todos los servicios DE UNA especialidad específica
     */
    public function getPorEspecialidad($id_especialidad) {
        $this->query("
            SELECT * FROM servicios 
            WHERE id_especialidad = :id_especialidad
        ");
        $this->bind(':id_especialidad', $id_especialidad);
        
        $filas = $this->resultSet();
        return $filas;
    }

    // (Aquí irán más funciones, como: getById, crear, actualizar, etc.)
}
?>