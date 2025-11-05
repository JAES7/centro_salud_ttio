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
    public function getById($id) {
        $this->query("SELECT * FROM servicios WHERE id_servicio = :id");
        $this->bind(':id', $id);
        // Asumiendo que resultSet() devuelve todos, y single() devuelve solo uno.
        return $this->single(); 
    }
    public function actualizar($data) {
        $this->query("UPDATE servicios SET descripcion = :descripcion, monto = :monto, id_especialidad = :id_especialidad 
                      WHERE id_servicio = :id_servicio");
        
        $this->bind(':descripcion', $data['nombre']); // Usamos 'nombre' del formulario
        $this->bind(':monto', $data['precio']);       // Usamos 'precio' del formulario
        $this->bind(':id_especialidad', $data['id_especialidad']);
        $this->bind(':id_servicio', $data['id_servicio']); // Necesitas el ID para el WHERE

        return $this->execute();
    }

    public function eliminar($id) {
        $this->query("DELETE FROM servicios WHERE id_servicio = :id");
        $this->bind(':id', $id);
        
        // Usamos el mismo patrón de ejecución
        return $this->execute();
    }

    // En models/ServicioModel.php - Método guardar()

    public function guardar($data) {
        // 1. Preparar valores de entrada
        
        // Si id_especialidad es null, usa 0 o un ID de especialidad por defecto.
        // Esto es CRUCIAL si el campo id_especialidad es NOT NULL en la BD.
        $id_especialidad = $data['id_especialidad'] ?? 0; 
        
        // Asegurarse de que el monto sea un número decimal.
        $monto = floatval($data['precio']); 

        // 2. Definir la consulta SQL
        $this->query("INSERT INTO servicios (descripcion, monto, id_especialidad, activo) 
                    VALUES (:nombre, :precio, :id_especialidad, 1)"); // <--- ACTIVO AÑADIDO Y FIJADO A 1
        
        // 3. Enlazar parámetros (usando los nombres del formulario del controlador)
        $this->bind(':nombre', $data['nombre']);
        $this->bind(':precio', $monto);
        $this->bind(':id_especialidad', $id_especialidad);

        // 4. Ejecución y Depuración
        if ($this->execute()) {
            return true; 
        } else {
            // Si falla, muestra el error SQL (solo para depuración)
            echo "<h1>¡FALLO CRÍTICO DE BASE DE DATOS!</h1>";
            print_r($this->db->errorInfo()); 
            die(); 
        }
    }
    // (Aquí irán más funciones, como: getById, crear, actualizar, etc.)
}
?>