<?php
session_start();

define('ROOT_PATH', __DIR__);

$baseURL = 'http://' . $_SERVER['HTTP_HOST'] . '/SAEPSAUDE';
define('BASE_URL', $baseURL );

require_once ROOT_PATH . '/Config/Configuration.php';
require_once ROOT_PATH . '/Model/Connection.php';
require_once ROOT_PATH . '/Model/ActivityModel.php';
require_once ROOT_PATH . '/Model/UserModel.php';
require_once ROOT_PATH . '/Controller/UserController.php';

$controller = new Controller\UserController();

$action = $_GET['action'] ?? 'showHome';

switch ($action) {
    case 'login':
        $controller->login();
        break;
    case 'logout':
        $controller->logout();
        break;
    case 'showRegisterPage':
        $controller->showRegisterPage();
        break;
    case 'registerUser':
        $controller->registerUser();
        break;
    case 'filterActivities':
        $controller->filterActivities();
        break;
    case 'showActivityPage':
        $controller->showActivityPage();
        break;
    case 'createActivity':
        $controller->createActivity();
        break;
    case 'showHome':
    default:
        $controller->showHome();
        break;
}
