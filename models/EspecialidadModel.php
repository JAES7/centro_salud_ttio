<?php
// models/EspecialidadModel.php

class EspecialidadModel extends Model {

    // El constructor que acepta la conexión $db
    public function __construct($db) {
        // Y se la pasa al 'padre' (Model)
        parent::__construct($db);
    }

    /**
     * Obtiene todas las especialidades activas
     */
    public function getAll() {
        // Usamos $this->db (que viene del 'padre')
        $this->query("SELECT * FROM especialidades");
        
        $filas = $this->resultSet();
        return $filas;
    }

    // (Aquí irán más funciones, como: getById, crear, actualizar, etc.)
}
?>