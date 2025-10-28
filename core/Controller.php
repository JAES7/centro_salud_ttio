<?php
// core/Controller.php (¡CORREGIDO!)

class Controller {

    protected $db; // El controlador tendrá la BD

    public function __construct() {
        // El controlador crea la instancia de la BD
        $database = new Database();
        
        // --- ¡AQUÍ ESTABA EL ERROR! ---
        // Era un punto (.), debe ser una flecha (->)
        $this->db = $database->getDbConnection(); 
        // --- FIN DE LA CORRECCIÓN ---
    }

    /**
     * Carga un modelo desde la carpeta /models
     */
    public function loadModel($model) {
        $modelPath = APP_ROOT . '/models/' . $model . '.php';

        if (file_exists($modelPath)) {
            require_once $modelPath;
            // Pasa la conexión de la BD ($this.db) al constructor del modelo
            return new $model($this->db); // <-- Aquí también va con flecha
        } else {
            die("Error: El archivo del modelo no fue encontrado en: " . $modelPath);
        }
    }

    /**
     * Carga una vista desde la carpeta /views
     */
    public function renderView($view, $data = [], $useTemplate = true) {
        $viewPath = APP_ROOT . '/views/' . $view . '.php';

        if (file_exists($viewPath)) {
            // Extraer $data para que esté disponible en la vista
            extract($data);
            
            if ($useTemplate) {
                $templatePath = APP_ROOT . '/views/template.php';
                if (!file_exists($templatePath)) {
                    die("Error: La plantilla principal 'template.php' no fue encontrada.");
                }
                
                // Le decimos al template.php cuál es la vista de contenido que debe cargar.
                $contentViewPath = $viewPath; 

                // ...e incluimos la plantilla (que ahora cargará el contenido)
                require_once $templatePath;

            } else {
                // Si no usamos plantilla (como en el login), solo cargamos la vista
                require_once $viewPath;
            }

        } else {
            die("Error: La vista '" . $view . "' no fue encontrada en: " . $viewPath);
        }
    }
    protected function loadController($controllerName) {
        $controllerPath = APP_ROOT . '/controllers/' . $controllerName . '.php';
        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            if (class_exists($controllerName)) {
                return new $controllerName();
            } else {
                throw new Exception("La clase $controllerName no existe en $controllerPath");
            }
        } else {
            throw new Exception("No se encontró el controlador $controllerName en $controllerPath");
        }
    }
}
?>