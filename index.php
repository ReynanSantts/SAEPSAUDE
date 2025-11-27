<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/Config/Configuration.php';

$controllerName = $_GET['controller'] ?? 'User';
$controllerName = ucfirst(strtolower($controllerName)) . 'Controller';

$action = $_GET['action'] ?? 'showHome';

$controllerClassName = "Controller\\" . $controllerName;

if (class_exists($controllerClassName)) {
    $controller = new $controllerClassName();
    
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        http_response_code(404 );
        echo "Erro 404: Ação '$action' não encontrada no controller '$controllerName'.";
    }
} else {
    http_response_code(404 );
    echo "Erro 404: Controller '$controllerClassName' não encontrado. Verifique o nome do arquivo e o namespace.";
}
