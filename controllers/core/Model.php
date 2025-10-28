<?php
// core/Model.php (CON ARREGLO)

// ¡YA NO HEREDA DE DATABASE!
class Model {

    protected $db; // <-- 1. El modelo tiene una propiedad db
    protected $stmt; // (Para las consultas)

    // 2. Acepta la conexión de la BD ($db) que le pasa el Controlador
    public function __construct($db) {
        // 3. La guarda en su propia propiedad
        $this->db = $db;
    }

    // --- COPIAMOS LAS FUNCIONES DE CONSULTA AQUÍ ---
    // Ahora los modelos usarán estas funciones
    
    public function query($sql) {
        $this->stmt = $this->db->prepare($sql);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function rowCount() {
        return $this->stmt->rowCount();
    }
}
?>