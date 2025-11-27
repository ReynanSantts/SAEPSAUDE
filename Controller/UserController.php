<?php
namespace Controller;

use Model\Connection;
use Model\ActivityModel;
use Model\UserModel;

class UserController {
    
    private $db;

    public function __construct() {
        $this->db = Connection::getInstance();
    }

    public function showHome() {
        $activityModel = new ActivityModel($this->db);
        
        $page = 1;
        $limit = 4;
        $type = 'corrida';

        $atividades = $activityModel->getActivitiesByType($type, $page, $limit);
        $totalActivities = $activityModel->countActivitiesByType($type);
        $totalPages = ceil($totalActivities / $limit);

        $userStats = ['total_atividades' => 0, 'total_calorias' => 0];
        if (isset($_SESSION['user_id'])) {
            $userStats = $activityModel->getUserStats($_SESSION['user_id']);
        }
        
        $title = "SAEP Saúde - Feed";
        $view_file = 'home_view.php';
        
        require_once dirname(__DIR__) . '/View/template.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            if (empty($email) || empty($senha)) {
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            }

            $userModel = new UserModel($this->db);
            $user = $userModel->findUserByEmail($email);

            if ($user && $senha === $user['senha']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nome_usuario'];
                $_SESSION['user_avatar'] = $user['imagem'];
                
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            } else {
                header('Location: ' . BASE_URL . '/index.php?error=login_failed');
                exit;
            }
        }
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    public function showRegisterPage() {
        $title = "SAEP Saúde - Cadastro";
        $view_file = 'register_view.php';
        require_once dirname(__DIR__) . '/View/template.php';
    }

    public function registerUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $nome_usuario = $_POST['nome_usuario'] ?? '';
            $senha = $_POST['senha'] ?? '';

            if (empty($nome) || empty($email) || empty($nome_usuario) || empty($senha)) {
                header('Location: ' . BASE_URL . '/index.php?action=showRegisterPage&error=Todos os campos são obrigatórios.');
                exit;
            }

            $userModel = new UserModel($this->db);

            if ($userModel->findUserByEmail($email)) {
                header('Location: ' . BASE_URL . '/index.php?action=showRegisterPage&error=Este email já está em uso.');
                exit;
            }

            if ($userModel->createUser($nome, $email, $nome_usuario, $senha)) {
                header('Location: ' . BASE_URL . '/index.php?success=registered');
                exit;
            } else {
                header('Location: ' . BASE_URL . '/index.php?action=showRegisterPage&error=Ocorreu um erro ao criar sua conta. Tente novamente.');
                exit;
            }
        }
        header('Location: ' . BASE_URL . '/index.php?action=showRegisterPage');
        exit;
    }

    public function showActivityPage() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php');
            exit;
        }
        $title = "SAEP Saúde - Minhas Atividades";
        $view_file = 'activity_view.php';
        require_once dirname(__DIR__) . '/View/template.php';
    }

    public function createActivity() {
        header('Location: /index.php?action=showActivityPage');
        exit;
    }

    public function filterActivities() {
        header('Content-Type: application/json');
        
        $type = $_GET['type'] ?? 'corrida';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 4;

        if (!in_array($type, ['corrida', 'caminhada', 'trilha'])) {
            echo json_encode(['error' => 'Tipo de atividade inválido.']);
            exit;
        }

        $activityModel = new \Model\ActivityModel($this->db);
        
        $atividades = $activityModel->getActivitiesByType($type, $page, $limit);
        $totalActivities = $activityModel->countActivitiesByType($type);
        $totalPages = ceil($totalActivities / $limit);

        echo json_encode([
            'activities' => $atividades,
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => $totalPages
            ]
        ]);
        exit;
    }
}
