<?php
// core/App.php (Limpio y Robusto para AJAX)

class App {
    protected $controller = 'LoginController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Verificar si el controlador existe
        if (isset($url[0]) && file_exists(APP_ROOT . '/controllers/' . ucwords($url[0]) . 'Controller.php')) {
            $this->controller = ucwords($url[0]) . 'Controller';
            unset($url[0]);
        
        } elseif (!isset($url[0]) && isset($_SESSION['id_usuario'])) {
            $this->controller = 'DashboardController';
        }
        
        $controllerPath = APP_ROOT . '/controllers/' . $this->controller . '.php';
        if(!file_exists($controllerPath)){
            die("ERROR FATAL: El archivo del controlador no existe en: " . $controllerPath . 
               "<br>Controlador buscado: '" . $this->controller . "'");
        }
        
        require_once $controllerPath;
        $this->controller = new $this->controller;

        // 2. Verificar si el método existe
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                // Si el método no existe, puede ser un error O
                // puede que sea un parámetro (ej: /caja/index/4)
                // Por ahora, si no lo encuentra, lo ignoramos
                // y asumimos que usará el método 'index'.
            }
        }

        // 3. Obtener los parámetros
        // --- ¡CAMBIO IMPORTANTE AQUÍ! ---
        // Re-indexamos el array $url para que los parámetros
        // siempre empiecen en el índice 0, sin importar
        // si el método se encontró o no.
        $this->params = $url ? array_values($url) : [];

        // 4. Llamar al método del controlador con los parámetros
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Limpia y divide la URL obtenida por .htaccess
     */
    public function parseUrl() {
        if (isset($_GET['url'])) {
            // 1. Quita la barra final '/'
            // 2. Limpia caracteres ilegales
            // 3. Divide la URL en un array usando '/'
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return []; // Retornar un array vacío si no hay 'url'
    }
}
?>
