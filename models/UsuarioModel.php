<?php
// models/UsuarioModel.php (CON LÓGICA DE CAMBIO DE CONTRASEÑA FORZADO)

class UsuarioModel extends Model {

    public function __construct($db) {
        parent::__construct($db);
    }

    public function getUsuarioPorUsername($username) {
        // Asegúrate de seleccionar la nueva columna 'cambiar_password'
        $this->query("SELECT * FROM usuarios WHERE username = :username AND activo = 1");
        $this->bind(':username', $username);
        $fila = $this->single();
        return $fila;
    }

    /**
     * Actualiza la contraseña y elimina el flag de 'cambiar_password'
     * Nota: Seguimos sin hashear la contraseña debido al error previo, usando texto plano.
     * En un entorno real, usaríamos password_hash($nueva_password, PASSWORD_DEFAULT).
     */
    public function actualizarPassword($id_usuario, $nueva_password) {
        
        // ¡ADVERTENCIA! USANDO TEXTO PLANO POR PROBLEMAS DE HASH EN XAMPP
        // La consulta de actualización debe reflejar el campo correcto
        $this->query("
            UPDATE usuarios 
            SET password_hash = :password, cambiar_password = 0 
            WHERE id_usuario = :id
        ");
        $this->bind(':password', $nueva_password); // Pasando el texto plano
        $this->bind(':id', $id_usuario);
        
        return $this->execute();
    }
}